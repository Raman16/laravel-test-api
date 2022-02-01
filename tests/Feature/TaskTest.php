<?php
namespace Tests\Feature;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
class TaskTest extends TestCase {
    
    use RefreshDatabase;

    public function setUp():void {
        parent::setUp();
        $this->authUser();
    }
    public function test_fetch_all_tasks_of_a_todo_list()    {


        $list = $this->createTodoItem();
        $task  = $this->createTaskItem(['todo_list_id'=>$list->id]);

       // $this->createTaskItem(['todo_list_id'=>2]);
        $response = $this->getJson(route('todo-list.tasks.index',$list->id))
                         ->assertOk()
                         ->json();  
                     
        $this->assertEquals(1, count($response));
        $this->assertEquals($task->title,$response[0]['title']);
       
    }

    public function test_store_a_task_for_a_todo_list(){
        $list = $this->createTodoItem();
        $task =  TaskList::factory()->make();
        $label = $this->createLabel();
        $this->postJson(route('todo-list.tasks.store',$list->id),
               [
                   'title'=>$task->title,
                   'label_id'=>$label->id
               ])
             ->assertCreated();
        $this->assertDatabaseHas('task_lists',
                    [
                        'title'=>$task->title,
                        'todo_list_id'=>$list->id,
                        'label_id'=>$label->id
                    ]);
    }
    public function test_store_a_task_for_a_todo_list_without_label(){
        $list = $this->createTodoItem();
        $task =  TaskList::factory()->make();
        $this->postJson(route('todo-list.tasks.store',$list->id),
               [
                   'title'=>$task->title,
               ])
             ->assertCreated();
        $this->assertDatabaseHas('task_lists',
                    [
                        'title'=>$task->title,
                        'todo_list_id'=>$list->id,
                        'label_id'=>null
                    ]);
    }

    public function test_delete_a_task_from_database(){
        $task = $this->createTaskItem();
        $this->deleteJson(route('tasks.destroy',$task->id))
                          ->assertNoContent();
        $this->assertDatabaseMissing('task_lists',['title'=>$task->title]);
    }

    public function test_update_a_task_of_a_todo_list(){
        $task = $this->createTaskItem();
        $this->patchJson(route('tasks.update',$task->id),
                              ['title'=>'Updated Item'])
            ->assertOk();
        $this->assertDatabaseHas('task_lists',['title'=>'Updated Item']);
    }

    
}
