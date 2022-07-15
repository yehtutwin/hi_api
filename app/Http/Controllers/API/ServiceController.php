<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Validator;

class ServiceController extends Controller
{
    public function index()
    {
        $res = new \stdClass();
        try {

            $data = Service::all();

            $res->status = 1;
            $res->message = 'Service List';
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
                'name' => 'required|min:5|max:100',
                'description' => 'required'
            ]);

            if ($validator->fails()) {
                $res->status = 0;
                $res->errors = $validator->errors();
                $res->message = 'Validation Failed!';

                return response()->json($res);
            }
    
            $service = Service::create([
                'name' => $request->name,
                'description' => $request->description
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

            $service = Service::find($id);
            if($service) {
                $res->status = 1;
                $res->message = 'Service Detail!';
                $res->data = $service;
            } else {
                $res->status = 0;
                $res->message = 'Service Not Found!';
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
    public function update(Request $request, Service $service)
    {
        $res = new \stdClass();
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|min:5|max:100',
                'description' => 'required'
            ]);

            if ($validator->fails()) {
                $res->status = 0;
                $res->errors = $validator->errors();
                $res->message = 'Validation Failed!';

                return response()->json($res);
            }
    
            $service->name = $request->name;
            $service->description = $request->description;
            $service->save();
    
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
    public function destroy(Service $service)
    {
        $res = new \stdClass();
        try {

            $service->delete();

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
