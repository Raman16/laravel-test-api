<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Models\Label;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LabelController extends Controller
{

    public function index(){
        return auth()->user()->labels;
    }
    public function store(LabelRequest $request){
       //$label =  Label::create($request->validated());
       $label = auth()->user()
                      ->labels()
                      ->create($request->validated());
       return response($label,Response::HTTP_CREATED);
    }
    public function update(Label $label,LabelRequest $request){
        $label =  $label->update($request->validated());
        return response($label,Response::HTTP_OK);
    }
    public function destroy(Label $label){
        $label->delete();
        return response('',Response::HTTP_NO_CONTENT);
    }
}
