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

function departure_year(){
    $departure_year = \App\departure_year::all();

    return $departure_year;
}

function departure_month(){
    $departure_month = \App\departure_month::all();

    return $departure_month;
}