<?php

namespace Tests\Unit;

use App\Models\TaskList;
use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_task_belongs_to_a_todo_list ()
    {
        $list = $this->createTodoItem();
        $task = $this->createTaskItem(['todo_list_id'=>$list->id]);    
        $this->assertInstanceOf(TodoList::class, $task->todo_lists);

    }   
}
