<?php
namespace App\Traits;

use App\employee;
use Carbon\Carbon;

trait LogsTraits {
    public function page_access_log($emp_id, $page, $action){
        $emp = employee::find($emp_id, ['fname', 'lname']);
        info($emp->fname.' '.$emp->lname.' | '.$action.' | '.$page.' | '.Carbon::now());
    }
};