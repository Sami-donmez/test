<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(){
        return view('calendar.index');
    }
    public function data(){

    }
    public function show($id){

    }
    public function store(Request  $request){
        $calendar = new Calendar();
        $calendar->user_id = auth()->id();
        $calendar->title = $request->title;
        $calendar->detail = $request->detail;
        $calendar->type = "Leave";
        $calendar->url = $request->url;
        $calendar->start_date = $request->start;
        $calendar->finish_date = $request->finish;
        $calendar->save();
        return response()->json(['success'=>'ok','message'=>""]);
    }
    public function update($id,Request  $request){
        $calendar = Calendar::find($id);
        $calendar->user_id = auth()->id();
        $calendar->title = $request->title;
        $calendar->detail = $request->detail;
        $calendar->type = "Leave";
        $calendar->url = $request->url;
        $calendar->start_date = $request->start;
        $calendar->finish_date = $request->finish;
        $calendar->save();
        return response()->json(['success'=>'ok','message'=>""]);
    }
    public function delete($id){
        $calendar = Calendar::find($id);
        $calendar->delete();
        return response()->json(['success'=>'ok','message'=>""]);
    }
}
