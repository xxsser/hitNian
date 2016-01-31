<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index(){
        return view('admin.report',[
            'count' => self::getCountUser(),
        ]);
    }

    private function getCountUser($range=''){
        if(is_array($range)){
            return \App\Fan::whereBetween('created_at',[$range['from'],$range['to']])->count();
        }
        return \App\Fan::count();
    }
}
