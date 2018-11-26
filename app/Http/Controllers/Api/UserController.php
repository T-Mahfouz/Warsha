<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderImage;
use App\Models\OrderService;
use App\Models\Service;
use App\Models\WorkshopRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','jwt.auth','activated','customer']);
    }

    public function postRequest(Request $request)
    {
        $user = Auth::user();
        $notvalid = parent::OrderValidator($request);
        if ($notvalid) {
            return parent::jsonResponse(400, $notvalid['ar']);
        }
        $request->merge(['user_id' => $user->id]);
        $order = Order::create([
            'user_id' => $user->id,
            'car_type' => $request->car_type,
            'car_model' => $request->car_model,
            'address' => $request->address,
            'description' => $request->description,
            'lat' => $request->lat,
            'lon' => $request->lon,
            'status' => 0,
        ]);

        if ($request->has('services')) {
            $services = explode(',', $request->services);
            if (count($services)) {
                foreach ($services as $serviceID) {
                    $service = Service::find($serviceID);
                    if ($service) {
                        OrderService::create([
                            'order_id' => $order->id,
                            'service_id' => $serviceID,
                        ]);
                    }
                }
            }
        }
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $image) {
                $destination = public_path('images/orders');
                $extension = $image->getClientOriginalExtension();
                $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);
                $image->move($destination, $fileName);
                OrderImage::create([
                      'order_id' => $order->id,
                      'image' => $fileName,
                  ]);
            }
        }
        $order['order_images'] = $order->orderImages();
        $order['order_services'] = $order->orderServices();
        unset($order['images']);
        unset($order['services']);

        $message = parent::messages('success_message_sent');
        return parent::jsonResponse(201, $message, $order);
    }
    public function acceptOffer(Request $request)
    {
        $user = Auth::user();

        $userOrders = $user->orders->pluck('id')->toArray();
        $orderID = $request->order_id;
        $offerID = $request->offer_id;
        if (!$orderID || !$offerID) {
            $message = parent::messages('missing_data');
            return parent::jsonResponse(400, $message);
        }
        $offer = Offer::where([
            'id' => $offerID,
            'order_id' => $orderID,
            'status' => 0,
        ])->first();
        if (!$offer || !in_array($orderID, $userOrders)) {
            $message = parent::messages('not_found');
            return parent::jsonResponse(404, $message);
        }
        $offer->update(['status' => 1]);
        $message = parent::messages('success_process');
        return parent::jsonResponse(200, $message, $offer);
    }
    public function rateWorkshop(Request $request)
    {
        $user = Auth::user();

        $workshopID = $request->workshop_id;
        $workshop = User::find($workshopID);
        if (!$workshop) {
            $message = parent::messages('not_found');
            return parent::jsonResponse(404,$message);
        }
        $rate = (float)$request->rate;
        if (!$rate || $rate > 5 || $rate < 0) {
            $message = parent::messages('error_rate_value');
            return parent::jsonResponse(400,$message);
        }

        $exist = WorkshopRate::where([
            'user_id' => $user->id,
            'workshop_id' => $workshopID
        ])->first();
        if ($exist) {
            $message = parent::messages('error_rate_exist');
            return parent::jsonResponse(400,$message);
        }
        WorkshopRate::create([
            'user_id' => $user->id,
            'workshop_id' => $workshopID,
            'rate' => $rate,
        ]);

        $workshopRate = $workshop->avg_rates();
        $workshop['avg_rate'] = $workshopRate;

        $message = parent::messages('success_process');
        return parent::jsonResponse(200,$message,$workshop);
    }
    public function userTimeline(Request $request)
    {
        $user = Auth::user();
        $orders = Order::orderBy('created_at','DESC')->get();

        $message = parent::messages('success_process');
        return parent::jsonResponse(200,$message,$orders);
    }
}
