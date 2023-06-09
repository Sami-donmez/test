<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::orderBy("id","desc")->get();
        return view('.supplier.list',compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		if( ! $request->ajax()){
		   return view('supplier.create');
		}else{
           return view('supplier.modal.create');
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$validator = Validator::make($request->all(), [
			'supplier_name' => 'required|max:191',
            'company_name' => 'nullable|max:191',
            'vat_number' => 'nullable|max:191',
            'email' => [
                'required',
                'email',
                Rule::unique('suppliers')->where('company_id',company_id()),
            ],
            'phone' => 'required|max:20',
            'address' => 'nullable|max:191',
            'country' => 'nullable|max:50',
            'city' => 'nullable|max:50',
            'state' => 'nullable|max:50',
            'postal_code' => 'nullable|max:20',
		]);

		if ($validator->fails()) {
			if($request->ajax()){
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('suppliers.create')
							->withErrors($validator)
							->withInput();
			}
		}


        $supplier= new Supplier();
	    $supplier->supplier_name = $request->input('supplier_name');
        $supplier->company_name = $request->input('company_name');
        $supplier->vat_number = $request->input('vat_number');
        $supplier->email = $request->input('email');
        $supplier->phone = $request->input('phone');
        $supplier->address = $request->input('address');
        $supplier->country = $request->input('country');
        $supplier->city = $request->input('city');
        $supplier->state = $request->input('state');
        $supplier->postal_code = $request->input('postal_code');

        $supplier->save();

		if(! $request->ajax()){
           return redirect()->route('suppliers.create')->with('success', _lang('Information has been added sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Information has been added sucessfully'),'data'=>$supplier]);
		}

   }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $supplier = Supplier::find($id);
		if(! $request->ajax()){
		    return view('supplier.view',compact('supplier','id'));
		}else{
			return view('supplier.modal.view',compact('supplier','id'));
		}

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $supplier = Supplier::find($id);
		if(! $request->ajax()){
		   return view('supplier.edit',compact('supplier','id'));
		}else{
           return view('supplier.modal.edit',compact('supplier','id'));
		}

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$validator = Validator::make($request->all(), [
			'supplier_name' => 'required|max:191',
            'company_name' => 'nullable|max:191',
            'vat_number' => 'nullable|max:191',
            'email' => [
                'required',
                'email',
                Rule::unique('suppliers')->where('company_id',company_id())->ignore($id),
            ],
            'phone' => 'required|max:20',
            'address' => 'nullable|max:191',
            'country' => 'nullable|max:50',
            'city' => 'nullable|max:50',
            'state' => 'nullable|max:50',
            'postal_code' => 'nullable|max:20',
		]);

		if ($validator->fails()) {
			if($request->ajax()){
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('suppliers.edit', $id)
							->withErrors($validator)
							->withInput();
			}
		}

        $supplier = Supplier::find($id);
		$supplier->supplier_name = $request->input('supplier_name');
        $supplier->company_name = $request->input('company_name');
        $supplier->vat_number = $request->input('vat_number');
        $supplier->email = $request->input('email');
        $supplier->phone = $request->input('phone');
        $supplier->address = $request->input('address');
        $supplier->country = $request->input('country');
        $supplier->city = $request->input('city');
        $supplier->state = $request->input('state');
        $supplier->postal_code = $request->input('postal_code');

        $supplier->save();

		if(! $request->ajax()){
           return redirect()->route('suppliers.index')->with('success', _lang('Information has been updated sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Information has been updated sucessfully'),'data'=>$supplier]);
		}

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();
        return back()->with('success',_lang('Information has been deleted sucessfully'));
    }
}
