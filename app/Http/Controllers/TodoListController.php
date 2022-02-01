<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListRequest;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TodoListController extends Controller
{
    public function index()
    {
        //dd(auth()->user());
        //$list = TodoList::whereUserId(Auth::id())->get();
        $list = auth()->user()->todo_lists;
        return response(['lists' => $list]);
        
    }
    public function show(TodoList $todo_list)
    {
        return response($todo_list);
    }
    public function store(TodoListRequest $request)
    {
        $request->validate(['name'=>'required']);
        $request['user_id'] = Auth::id();;
     
        //$list = TodoList::create($request->all());
        $list =  auth()->user()
                       ->todo_lists()
                       ->create($request->all());
        //return $list //laravel also sends 201 status
        return response($list, Response::HTTP_CREATED);//201
    }

    public function update(TodoList $todo_list,TodoListRequest $request){
        $request->validate(['name'=>'required']);
        $todo_list->update($request->all());
        return response($todo_list);
    }
    public function destroy(TodoList $todo_list){
        $todo_list->delete();
        return response('',Response::HTTP_NO_CONTENT);
    }
}
