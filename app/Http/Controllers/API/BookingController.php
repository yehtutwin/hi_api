<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;
use Validator;

class BookingController extends Controller
{
    public function index()
    {
        $res = new \stdClass();
        try {

            $data = Booking::with('customer', 'services')->paginate(10);

            $res->status = 1;
            $res->message = 'Booking List';
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
                'date' => 'required|date',
                'customer_id' => 'required',
                'car_no' => 'required',
                'duration' => 'required',
                'services' => 'required|array|min:1'
            ]);

            if ($validator->fails()) {
                $res->status = 0;
                $res->errors = $validator->errors();
                $res->message = 'Validation Failed!';

                return response()->json($res);
            }
    
            $booking = Booking::create([
                'date' => date('Y-m-d', strtotime($request->date)),
                'customer_id' => $request->customer_id,
                'car_no' => $request->car_no,
                'duration' => $request->duration,
                'note' => $request->note
            ]);

            $services = $request->services;
            foreach($services as $service) {
                $service = Service::find($service_id);
                if($service) {
                    $booking->services()->attach($service);
                }
            }
    
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

            $booking = Booking::find($id);
            if($booking) {
                $res->status = 1;
                $res->message = 'Booking Detail!';
                $res->data = $booking->load('customer', 'services');
            } else {
                $res->status = 0;
                $res->message = 'Booking Not Found!';
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
    public function update(Request $request, Booking $booking)
    {
        $res = new \stdClass();
        try {

            $validator = Validator::make($request->all(), [
                'date' => 'required|date',
                'customer_id' => 'required',
                'car_no' => 'required',
                'duration' => 'required',
                'services' => 'required|array|min:1'
            ]);

            if ($validator->fails()) {
                $res->status = 0;
                $res->errors = $validator->errors();
                $res->message = 'Validation Failed!';

                return response()->json($res);
            }
    
            $booking->date = date('Y-m-d', strtotime($request->date));
            $booking->customer_id = $request->customer_id;
            $booking->car_no = $request->car_no;
            $booking->duration = $request->duration;
            $booking->note = $request->note;
            $booking->save();

            $booking->services()->detach();
            $services = $request->services;
            foreach($services as $service_id) {
                $service = Service::find($service_id);
                if($service) {
                    $booking->services()->attach($service);
                }
            }
    
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
    public function destroy(Booking $booking)
    {
        $res = new \stdClass();
        try {

            $booking->delete();

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
