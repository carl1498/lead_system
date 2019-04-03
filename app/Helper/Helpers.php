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

function onLoadBranch(){
    $id = Auth::user()->id;
    $employee = \App\employee::find($id);
    $branch = \App\branch::where('id', $employee->branch_id)->first();

    return $branch->name;
}

function departure_year(){
    $departure_year = \App\departure_year::orderBy('name')->get();

    return $departure_year;
}

function departure_month(){
    $departure_month = \App\departure_month::all();

    return $departure_month;
}

//Access Pages

function canAccessAll(){
    $id = Auth::user()->emp_id;
    $user = \App\User::with('employee.role')->find($id);
    $authorized = ['President', 'Finance Director', 'HR Head', 'IT Officer'];

    foreach($authorized as $auth){
        if($user->employee->role->name == $auth){
            return true;
        }
    }
    return false;
}

function canAccessStudents(){
    $id = Auth::user()->emp_id;
    $user = \App\User::with('employee.role')->find($id);
    $id = Auth::user()->emp_id;
    $user = \App\User::with('employee.role')->find($id);
    $authorized = ['President', 'Finance Director', 'Admin', 'Manager', 'OIC', 'HR Head',
        'HR', 'IT Officer', 'Marketing Manager', 'Marketing Head', 'Marketing Officer',
        'Documentation Head', 'Documentation Officer', 'Language Head'];

    foreach($authorized as $auth){
        if($user->employee->role->name == $auth){
            return true;
        }
    }
    return false;
}

function canAccessStudentList(){
    $id = Auth::user()->emp_id;
    $user = \App\User::with('employee.role')->find($id);
    $authorized = ['Admin', 'Manager', 'OIC', 'HR', 'Marketing Manager', 'Marketing Head', 
        'Marketing Officer', 'Documentation Head', 'Documentation Officer', 'Language Head'];

    foreach($authorized as $auth){
        if($user->employee->role->name == $auth){
            return true;
        }
    }
    return false;
}

function canEditStudentList(){
    $id = Auth::user()->emp_id;
    $user = \App\User::with('employee.role')->find($id);
    $authorized = ['Melba', 'Jovelyn'];

    foreach($authorized as $auth){
        if($user->employee->fname == $auth){
            return true;
        }
    }
    return false;
}

function canEditLanguageStudent(){
    $id = Auth::user()->emp_id;
    $user = \App\User::with('employee.role')->find($id);
    $authorized = ['HR Head'];

    foreach($authorized as $auth){
        if($user->employee->fname == $auth){
            return true;
        }
    }
    return false;
}

function canAccessStudentSettings(){
    $id = Auth::user()->emp_id;
    $user = \App\User::with('employee.role')->find($id);
    $authorized = ['President', 'Finance Director', 'HR Head', 'IT Officer', 'Language Head'];

    foreach($authorized as $auth){
        if($user->employee->role->name == $auth){
            return true;
        }
    }
    return false;
}

function canAccessEmployees(){
    $id = Auth::user()->emp_id;
    $user = \App\User::with('employee.role')->find($id);
    $authorized = ['HR'];

    foreach($authorized as $auth){
        if($user->employee->role->name == $auth){
            return true;
        }
    }
    return false;
}

function canAccessBooks(){
    $id = Auth::user()->emp_id;
    $user = \App\User::with('employee.role')->find($id);
    $authorized = ['HR', 'Language Head'];

    foreach($authorized as $auth){
        if($user->employee->role->name == $auth){
            return true;
        }
    }
    return false;
}

function canAccessInvoice(){
    $id = Auth::user()->emp_id;
    $user = \App\User::with('employee.role')->find($id);
    $authorized = ['Language Head'];
    
    foreach($authorized as $auth){
        if($user->employee->role->name == $auth){
            return true;
        }
    }
    return false;
}

function canAccessBookManagement(){
    $id = Auth::user()->emp_id;
    $user = \App\User::with('employee.role')->find($id);
    $authorized = ['HR', 'Language Head'];
    
    foreach($authorized as $auth){
        if($user->employee->role->name == $auth){
            return true;
        }
    }
    return false;
}