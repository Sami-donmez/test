<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlowController extends Controller
{
    public function salesflow(){
        return view('salesflow');
    }
}
