<?php

namespace App\Http\Controllers;

use App\Lead;
use App\Models\LeadStatus;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function GuzzleHttp\Promise\task;

class TaskController extends Controller
{
    public function index(){
        $tasks = Task::where('status',1)->get();
        return view('task.list',compact('tasks'));
    }

    public function get_kanban_data() {
        $statuses = TaskStatus::$status;
        $data = [];
        foreach ($statuses as $status){
            $contacts = Task::where('status',$status)->orderBy("tasks.id", "desc")->get();
            $data2 = [];
            $data2['id'] = Str::slug($status);
            $data2['title'] = $status;
            $data2['class'] = "";
            $data2['dragTo'] = [];
            $data2['item']=[];
            foreach ($contacts as $contact){
                $data3 = [];
                $data3['id'] = $contact->id;
                $data3['title'] = view('leads.component.kanban-card',['title'=>$contact->name])->render();
                $data2['item'][]=$data3;
            }
            $data[]=$data2;
        }

        return response()->json(['data'=>$data]);
    }
    public function flow(){
        return view('workflow');
    }


}
