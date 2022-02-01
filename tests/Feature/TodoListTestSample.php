<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
      $this->list = TodoList::factory()->create(['name'=>'My List']);
    }
    public function test_fetch_all_todo_list()
    {
        //preparation/prepare
        //TodoList::factory()->create();//fails
        //TodoList::factory()
                    //->count(2)
                    //->create(['name'=>'My List']);//Fails

        //[below `name` overrides the `faker` text inside the factory method]
       // TodoList::factory()->create(['name'=>'My List']);//passes the below test
       
        //action/perform
        $response = $this->getJson(route('todo-list.store'));
        //dd(count($response->json()));

        //assertion/predict
        $this->assertEquals(1,count($response->json()));
        $this->assertEquals('My List',$response->json()['lists'][0]['name']);
    }

    public function test_fetch_single_todo_list(){

       //preparation
       $list =TodoList::factory()->create();//passes the below test
       
       //action
       //$response = $this->getJson(route('todo-list.show',2));
       $response = $this->getJson(route('todo-list.show',$list->id))
                         ->assertOk()
                         ->json();
              
       //assertion
       //$response->assertStatus(200);OR
       //$response->assertOk();
       //dd($response->json());
       $this->assertEquals($response['name'], $list->name);
    }
}