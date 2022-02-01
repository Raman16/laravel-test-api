<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\WebServiceController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
       Route::apiResource('todo-list', TodoListController::class);
       //Route::apiResource('tasks',TaskController::class);
       // Route::apiResource('todo-list/{todo_list}/tasks',TaskController::class)
       Route::apiResource('todo-list.tasks', TaskController::class)
              ->except('show')
              ->shallow();

       Route::apiResource('label', LabelController::class);

       Route::get('/web-service/connect/{name}', [WebServiceController::class, 'connect'])
              ->name('web-service.connect');
       Route::post('/web-service/callback', [WebServiceController::class, 'callback'])
              ->name('web-service.callback');
       Route::post('/web-service/{web_service}', [WebServiceController::class, 'store'])
               ->name('web-service.store');
});


Route::post('/register', RegisterController::class)
       ->name('user.register');
//[] => should be used only IF there is a function [Controller::class,'index']
//else __invoke() magic method without function works

Route::post('/login', LoginController::class)
       ->name('user.login');

// Route::get('tasks',[TaskController::class,'index'])
//         ->name('task.index');
// Route::post('tasks',[TaskController::class,'store'])
//         ->name('task.store');
// Route::delete('tasks/{task}',[TaskController::class,'destroy'])
//         ->name('task.destroy');
// Route::get('todo-list', [TodoListController::class, 'index'])
//          ->name('todo-list.index');
// Route::get('todo-list/{todolist}',[TodoListController::class,'show'])
//          ->name('todo-list.show');
// //{todo-list} => Model Name `TodoList` converts as todo-list.
// //we cannot use todo-list as variable name in controller hence we use 
// //{todolist} for route modelling.

// Route::post('todo-list',[TodoListController::class,'store'])
//          ->name('todo-list.store');

// Route::patch('todo-list/{todolist}',[TodoListController::class,'update'])
//          ->name('todo-list.update');

// Route::delete('todo-list/{todolist}',[TodoListController::class,'destroy'])
//          ->name('todo-list.delete');