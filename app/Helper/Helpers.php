<?php

function onLoadName(){
    $id = Auth::user()->emp_id;
    $onLoadName = \App\employee::find($id);

    return $onLoadName->fname.' '.$onLoadName->lname;
}

function onLoadPosition(){
    $id = Auth::user()->id;
    $user = \App\User::with('employee.role')->where('id', $id)->first();

    return $user->employee->role->name;
}