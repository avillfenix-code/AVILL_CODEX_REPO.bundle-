<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\OrderUpdateMail;
use App\Models\Order;

class PreviewMailController extends Controller
{
    //
    public function index(Request $request)
    {

        return;
        //New vendor mail
        // $vendor = App\Models\Vendor::first();
        // return new App\Mail\NewVendorMail($vendor);
        // NEW USER MAIL
        // $user = App\Models\User::first();
        // return new App\Mail\NewAccountMail($user, "password");

        //vendor custom settings
        // $order = App\Models\Order::where('vendor_id', 174)->first();
        // return driverSearchRadius($order);

        $slug = $request->slug ?? "";
        $id = $request->id;
        if ($slug == 'taxi') {
            $order = Order::whereHas('taxi_order')
                ->when($id, function ($query) use ($id) {
                    return $query->where('id', $id);
                })->first();
        } else if ($slug == 'package') {
            $order = Order::whereHas('package_type')
                ->when($id, function ($query) use ($id) {
                    return $query->where('id', $id);
                })->first();
        } else if ($slug == 'service') {
            $order = Order::whereHas('order_service')
                ->when($id, function ($query) use ($id) {
                    return $query->where('id', $id);
                })->first();
        } else {
            //order update mail
            $order = Order::whereHas('products')
                ->when($id, function ($query) use ($id) {
                    return $query->where('id', $id);
                })->first();
        }
        // return $order;
        // $order = App\Models\Order::whereHas('taxi_order')->first();
        // $order = App\Models\Order::whereHas('package_type')->first();
        return new OrderUpdateMail($order);
    }
}
