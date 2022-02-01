<?php

namespace Tests\Feature;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TodoListTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    private $list;
    public function setUp():void{
      parent::setUp();
      $user = $this->authUser();
      $this->list = $this->createTodoItem(
                           ['name'=>'My List',
                           'user_id'=>$user->id
                           ]);

    }
    public function test_fetch_all_todo_list() {

        $this->createTodoItem();
        //action/perform
        $response = $this->getJson(route('todo-list.index'));

        //assertion/predict
        $this->assertEquals(1,count($response->json()));
        $this->assertEquals('My List',$response->json()['lists'][0]['name']);
    }

    public function test_fetch_single_todo_list(){

       //$response = $this->getJson(route('todo-list.show',2));
       $response = $this->getJson(route('todo-list.show',$this->list->id))
                         ->assertOk()
                         ->json();
              
       $this->assertEquals($response['name'], $this->list->name);

    }

    public function test_store_new_todo_list(){
       
      //preparation
      $list = TodoList::factory()->make();
      //Note:create stores make does not store

      //action
      $response = $this->postJson(route('todo-list.store'),['name'=>$list->name])
           //->assertStatus(201);OR
            ->assertCreated()
            ->json();
           //->assertSuccessful();
      
      //assertion
       $this->assertEquals($list->name,$response['name']);
       $this->assertDatabaseHas('todo_lists',['name'=>$list->name]);

    }

    public function test_while_storing_todo_list_name_field_is_required(){
      $this->withExceptionHandling();
      $response  = $this->postJson(route('todo-list.store'))
                        //->assertNotFound();//Gives us Error status
                        //->assertStatus(422);ORRR
                          ->assertUnprocessable();//422
       $response->assertJsonValidationErrors(['name']);
    }
    public function test_update_todo_list(){
     // $this->withExceptionHandling();
      $this->patchJson(route('todo-list.update',$this->list->id),
                              ['name'=>'Updated Name'])
           ->assertOk();
                 
      $this->assertDatabaseHas('todo_lists',
                       ['id'=>$this->list->id,'name'=>'Updated Name']);
    }

    public function test_delete_todo_list(){

      $this->deleteJson(route('todo-list.destroy',$this->list->id))
           ->assertNoContent();
      $this->assertDatabaseMissing('todo_lists', ['name'=>$this->list->name]);

    }

    public function test_if_todo_list_is_deleted_then_all_its_tasks_will_be_deleted(){

      $list  = $this->createTodoItem();
      $task  = $this->createTaskItem(['todo_list_id'=>$list->id]);
      $task1 = $this->createTaskItem();

      //action
      $list->delete();

      $this->assertDatabaseMissing('todo_lists',['id'=>$list->id]);
      $this->assertDatabaseMissing('task_lists',['id'=>$task->id]);
      $this->assertDatabaseHas('task_lists',['id'=>$task1->id]);

    }
}