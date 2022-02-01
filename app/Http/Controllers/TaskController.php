<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\TaskList;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function index(TodoList $todo_list){
       
        // $tasks = TaskList::where(['todo_list_id'=>$todo_list->id])
        //                   ->get(); 
        $tasks = $todo_list->tasks;              
        return response($tasks,Response::HTTP_OK);
    }

    public function store(TaskRequest $request,TodoList $todo_list){
       
        // $request['todo_list_id'] = $todo_list->id;
        // $task = TaskList::create($request->all());
        //dd($todo_list->tasks);
       
        $task = $todo_list->tasks()->create($request->validated());
        return response($task,Response::HTTP_CREATED);
    }

    public function update(TaskList $task, Request $request){
        $task->update($request->all());
        return response($task,Response::HTTP_OK);
    }
    public function destroy(TaskList $task){
       $task->delete();
       return response('',Response::HTTP_NO_CONTENT);
    }
}
