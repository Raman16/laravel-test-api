<?php

namespace Tests;

use App\Models\Label;
use App\Models\TaskList;
use App\Models\TodoList;
use App\Models\User;
use App\Models\WebService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    public function setUp():void{
         parent::setUp();
         $this->withoutExceptionHandling();
    }
    
    public function createTodoItem($args = []){
        return TodoList::factory()->create($args);
    }

    public function createTaskItem($args = []){
        return TaskList::factory()->create($args);
    }
    public function createUser($args = []){
        return User::factory()->create($args);
    }
    public function createLabel($args = []){
        return Label::factory()->create($args);
    }
    public function authUser(){
        return Sanctum::actingAs($this->createUser());
    }
    public function createWebService($args = []) {
        return WebService::factory()->create($args);
    }

}
