<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $res = new \stdClass();
        try {

            $data = Customer::all();

            $res->status = 1;
            $res->message = 'Customer List';
            $res->data = $data;

            return response()->json($res, 200);

        } catch (\Exception $e) {
            $res->status = 0;
            $res->message = 'Exception Error!';
            $res->developer_message = $e->getMessage();

            return response()->json($res, 400);
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
        $res = new \stdClass();
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|min:5|max:50',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8',
            ]);

            if ($validator->fails()) {
                $res->status = 0;
                $res->errors = $validator->errors();
                $res->message = 'Validation Failed!';

                return response()->json($res);
            }
    
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
    
            $res->status = 1;
            $res->message = 'Created Successfully!';
            $res->data = $request->all();

            return response()->json($res, 200);

        } catch (\Exception $e) {
            $res->status = 0;
            $res->message = 'Exception Error!';
            $res->developer_message = $e->getMessage();

            return response()->json($res, 400);
        }
    } 

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $res = new \stdClass();
        try {

            $customer = Customer::find($id);
            if($customer) {
                $res->status = 1;
                $res->message = 'Customer Detail!';
                $res->data = $customer;
            } else {
                $res->status = 0;
                $res->message = 'Customer Not Found!';
            }

            return response()->json($res, 200);

        } catch (\Exception $e) {
            $res->status = 0;
            $res->message = 'Exception Error!';
            $res->developer_message = $e->getMessage();

            return response()->json($res, 400);
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Customer $customer)
    {
        $res = new \stdClass();
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|min:5|max:50',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8',
            ]);

            if ($validator->fails()) {
                $res->status = 0;
                $res->errors = $validator->errors();
                $res->message = 'Validation Failed!';

                return response()->json($res);
            }
    
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->password = bcrypt($request->password);
            $customer->save();
    
            $res->status = 1;
            $res->message = 'Updated Successfully!';
            $res->data = $request->all();

            return response()->json($res, 200);

        } catch (\Exception $e) {
            $res->status = 0;
            $res->message = 'Exception Error!';
            $res->developer_message = $e->getMessage();

            return response()->json($res, 400);
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(Customer $customer)
    {
        $res = new \stdClass();
        try {

            $customer->delete();

            $res->status = 1;
            $res->message = 'Deleted Successfully!';

            return response()->json($res, 200);

        } catch (\Exception $e) {
            $res->status = 0;
            $res->message = 'Exception Error!';
            $res->developer_message = $e->getMessage();

            return response()->json($res, 400);
        }
    }
}
