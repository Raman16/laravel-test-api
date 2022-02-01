<?php

namespace Tests\Unit;

use App\Models\TaskList;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpParser\ErrorHandler\Collecting;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_a_todo_list_can_has_many_tasks()
    {
        $list = $this->createTodoItem();
        $task = $this->createTaskItem(['todo_list_id'=>$list->id]);

        $this->assertInstanceOf(Collection::class,$list->tasks);
        $this->assertInstanceOf(TaskList::class, $list->tasks->first());
    }
}
