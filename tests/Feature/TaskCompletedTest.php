<?php

namespace Tests\Feature;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskCompletedTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_a_task_status_can_be_changed() {

       $this->authUser();
       
       $task = $this->createTaskItem();

       $this->patchJson(route('tasks.update',$task->id),['status'=>TaskList::STARTED])  ;

       $this->assertDatabaseHas('task_lists',
                                ['status'=>TaskList::STARTED]);

    }
}
