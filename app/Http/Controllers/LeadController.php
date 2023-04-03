<?php

namespace App\Http\Controllers;

use App\Lead;
use App\Models\Contact;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class LeadController extends Controller
{
    public function index()
    {
        return view('leads.list');
    }
    public function create(){
        return view('leads.create');
    }
    public function store(Request $request){
        $lead = new Lead();
        $lead->name = $request->name;
        $lead->organization = $request->organization;
        $lead->amount = $request->amount;
        $lead->currency = $request->currency;
        $lead->contact_person = $request->contact_person;
        $lead->phone = $request->phone;
        $lead->email = $request->email;
        $lead->status = "Open voor sessie";
        $lead->company_id = 1;
        $lead->save();
        return redirect('leads')->with('success',"leads added.");
    }

    public function get_table_data() {

        $contacts = Lead::orderBy("leads.id", "desc");

        return Datatables::eloquent($contacts)
            ->addColumn('action', function ($contact) {
                return ''
                    . '<a href="" class="btn btn-primary btn-sm"><i class="ti-eye"></i></a> '
                    . '<a href="" class="btn btn-warning btn-sm"><i class="ti-pencil-alt"></i></a> '
                    . csrf_field()
                    . '<input name="_method" type="hidden" value="DELETE">'
                    . '<button class="btn btn-danger btn-sm btn-remove" type="submit"><i class="ti-trash"></i></button>'
                    . '</form>';
            })
            ->setRowId(function ($contact) {
                return "row_" . $contact->id;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function get_kanban_data() {
        $statuses = LeadStatus::$status;
        $data = [];
        $i=1;
        foreach ($statuses as $status){
            $contacts = Lead::where('status',$status)->orderBy("leads.id", "desc")->get();
            $data2 = [];
            $data2['id'] = Str::slug($status);
            $data2['title'] = $i.".".$status;
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
            $i++;
        }

        return response()->json(['data'=>$data]);
    }

}
