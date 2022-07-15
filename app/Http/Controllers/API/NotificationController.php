<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Notification;
use App\Notifications\InvoicePaid;
use App\Notifications\BookingDone;
use Validator;

class NotificationController extends Controller
{
    public function bookingDone(Request $request)
    {
        $res = new \stdClass();
        try {

            $validator = Validator::make($request->all(), [
                'customer_id' => 'required',
            ]);

            if ($validator->fails()) {
                $res->status = 0;
                $res->errors = $validator->errors();
                $res->message = 'Validation Failed!';

                return response()->json($res, 400);
            }
            
            $booking = '';
            $customer_id = $request->customer_id;
            $customer = Customer::find($customer_id);
            Notification::route('mail', $customer->email)->notify(new BookingDone($booking));

            $res->status = 1;
            $res->message = 'Booking Done Successfully!';

            return response()->json($res, 200);

        } catch (\Exception $e) {
            $res->status = 0;
            $res->message = 'Exception Error!';
            $res->developer_message = $e->getMessage();

            return response()->json($res, 400);
        }
    }

    public function invoicePaid(Request $request)
    {
        $res = new \stdClass();
        try {

            $validator = Validator::make($request->all(), [
                'customer_id' => 'required',
            ]);

            if ($validator->fails()) {
                $res->status = 0;
                $res->errors = $validator->errors();
                $res->message = 'Validation Failed!';

                return response()->json($res, 400);
            }
            
            $invoice = '';
            $customer_id = $request->customer_id;
            $customer = Customer::find($customer_id);
            Notification::route('mail', $customer->email)->notify(new InvoicePaid($invoice));

            $res->status = 1;
            $res->message = 'Invoice Paid Successfully!';

            return response()->json($res, 200);

        } catch (\Exception $e) {
            $res->status = 0;
            $res->message = 'Exception Error!';
            $res->developer_message = $e->getMessage();

            return response()->json($res, 400);
        }
    }
}
