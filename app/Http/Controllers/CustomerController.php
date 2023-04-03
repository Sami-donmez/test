<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('customers.list');
    }

    public function get_table_data() {

        $contacts = Customer::with("group")
            ->orderBy("contacts.id", "desc");

        return Datatables::eloquent($contacts)

            ->editColumn('contact_image', function ($contact) {
                return '<img class="thumb-sm img-thumbnail" src="' . asset('public/uploads/contacts/' . $contact->contact_image) . '">';
            })

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
            ->rawColumns(['action', 'contact_image'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if (!$request->ajax()) {
            return view('customers.create');
        } else {
            return view('customers.modal.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'company_name'  => 'nullable|max:50',
            'contact_name'  => 'required|max:50',
            'contact_email' => 'required|email|max:100',
            'contact_phone' => 'nullable|max:20',
            'city'          => 'nullable|max:50',
        ]);

        if ($validator->fails()) {

            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('contacts.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }


        $contact                = new Customer();
        $contact->company_name  = $request->input('company_name');
        $contact->contact_name  = $request->input('contact_name');
        $contact->contact_email = $request->input('contact_email');
        $contact->contact_phone = $request->input('contact_phone');
        $contact->city          = $request->input('city');
        $contact->zip          = $request->input('zip');
        $contact->contact_address       = $request->input('address');
        $contact->save();

        if (!$request->ajax()) {
            return redirect()->route('customers', $contact->id)->with('success', _lang('New client added sucessfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('New client added sucessfully'), 'data' => $contact]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $contact = Customer::find($id);
        if (!$request->ajax()) {
            return view('customers.contact.view', compact('contact', 'invoices', 'quotations', 'transactions', 'id'));
        } else {
            return view('customers.contact.modal.view', compact('contact', 'invoices', 'quotations', 'transactions', 'id'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $contact = Customer::find($id);
        if (!$request->ajax()) {
            return view('customers.contact.edit', compact('contact', 'id'));
        } else {
            return view('customers.contact.modal.edit', compact('contact', 'id'));
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $contact = Customer::where("id", $id)->where("company_id", company_id())->first();

        $validator = Validator::make($request->all(), [
            'profile_type'  => 'required|max:20',
            'company_name'  => 'nullable|max:50',
            'contact_name'  => 'required|max:50',
            'contact_email' => 'required|email|max:100',
            'contact_phone' => 'nullable|max:20',
            'country'       => 'nullable|max:50',
            'city'          => 'nullable|max:50',
            'state'         => 'nullable|max:50',
            'zip'           => 'nullable|max:20',
            'contact_image' => 'nullable|image||max:5120',

            'name'          => 'required_if:client_login,1|max:191', //User Login Attribute
            'email'         => [
                'required_if:client_login,1',
                Rule::unique('users')->ignore($contact->user_id),
            ], //User Login Attribute
            'password'      => 'nullable|max:20|min:6|confirmed', //User Login Attribute
            'status'        => 'required_if:client_login,1', //User Login Attribute
        ], [
            'name.required_if'     => 'Name is required',
            'email.required_if'    => 'Email is required',
            'password.required_if' => 'Password is required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('contacts.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        if ($request->hasfile('contact_image')) {
            $file          = $request->file('contact_image');
            $contact_image = "contact_image" . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path() . "/uploads/contacts/", $contact_image);
        }

        if ($request->client_login == 1) {
            if ($contact->user_id != NULL) {
                $user = User::find($contact->user_id);
            } else {
                $user = new User();
            }
            $user->name   = $request->input('name');
            $user->email  = $request->input('email');
            $user->status = $request->input('status');
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->user_type  = 'client';
            $user->company_id = company_id();
            $user->save();
        }

        $contact->profile_type  = $request->input('profile_type');
        $contact->company_name  = $request->input('company_name');
        $contact->contact_name  = $request->input('contact_name');
        $contact->contact_email = $request->input('contact_email');
        $contact->contact_phone = $request->input('contact_phone');
        $contact->country       = $request->input('country');
        $contact->city          = $request->input('city');
        $contact->state         = $request->input('state');
        $contact->zip           = $request->input('zip');
        $contact->address       = $request->input('address');
        $contact->facebook      = $request->input('facebook');
        $contact->twitter       = $request->input('twitter');
        $contact->linkedin      = $request->input('linkedin');
        $contact->remarks       = $request->input('remarks');
        $contact->group_id      = $request->input('group_id');
        if ($request->client_login == 1) {
            $contact->user_id = $user->id;
        }
        if ($request->hasfile('contact_image')) {
            $contact->contact_image = $contact_image;
        }

        $contact->save();

        if (!$request->ajax()) {
            return redirect()->route('customers.show', $contact->id)->with('success', _lang('Client information updated sucessfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Client information updated sucessfully'), 'data' => $contact]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $contact = Customer::find($id);
        $contact->delete();
        return back()->with('success', _lang('Information has been deleted sucessfully'));
    }

    public function send_email(Request $request, $id) {
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);
        Overrider::load("Settings");

        $validator = Validator::make($request->all(), [
            'email_subject' => 'required',
            'email_message' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return back()->withErrors($validator)
                    ->withInput();
            }
        }

        $contact = Customer::find($id);

        //Send email
        $subject = $request->input("email_subject");
        $message = $request->input("email_message");

        $mail          = new \stdClass();
        $mail->subject = $subject;
        $mail->body    = $message;

        try {
            Mail::to($contact->contact_email)
                ->send(new GeneralMail($mail));
        } catch (\Exception $e) {
            if (!$request->ajax()) {
                return back()->with('error', _lang('Sorry, Error Occured !'));
            } else {
                return response()->json(['result' => 'error', 'message' => _lang('Sorry, Error Occured !')]);
            }
        }

        if (!$request->ajax()) {
            return back()->with('success', _lang('Email Send Sucessfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Email Send Sucessfully'), 'data' => $contact]);
        }
    }
}
