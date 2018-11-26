<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WorkshopController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','jwt.auth','activated','workshop']);
    }

    public function postOffer(Request $request)
    {
        $user = Auth::user();

        $orderID = $request->order_id;
        $order = Order::where(['id' => $orderID,'status' => 0])->first();
        if(!$order) {
            $message = parent::messages('not_found');
            return parent::jsonResponse(404, $message);
        }

        $exist = Offer::where([
            'user_id' => $user->id,
            'order_id' => $orderID,
        ])->first();
        if($exist) {
            $message = parent::messages('error_offered_exist');
            return parent::jsonResponse(400, $message);
        }

        $content = $request->content;
        if(!$content) {
            $message = parent::messages('error_post_content');
            return parent::jsonResponse(400, $message);
        }
        $offer = Offer::create([
            'user_id' => $user->id,
            'order_id' => $orderID,
            'content' => $content,
            'status' => 0,
        ]);
        $message = parent::messages('success_add');
        return parent::jsonResponse(201, $message,$offer);
    }
    public function acceptedOffers(Request $request)
    {
        $user = Auth::user();

        $offers = Offer::where([
            'user_id' => $user->id,
            'status' => 1,
        ])->get();
        if(count($offers)) {
            foreach($offers as $offer) {
                $offer['order'] = $offer->order;
            }
        }
        $message = parent::messages('success_process');
        return parent::jsonResponse(200, $message,$offers);
    }
    public function allOffers(Request $request)
    {
        $user = Auth::user();

        $offers = $user->offers;
        //$offers = Offer::where('user_id',$user->id)->get();
        if(count($offers)) {
            foreach($offers as $offer) {
                $offer['order'] = $offer->order;
            }
        }
        $message = parent::messages('success_process');
        return parent::jsonResponse(200, $message,$offers);
    }
    public function workshopTimeline(Request $request)
    {
        $user = Auth::user();
        $services = $user->services->pluck('service_id')->toArray();

        $orders = new Order;

        if(in_array(0,$services))
            $orders = $orders->orderBy('created_at','DESC')->get();
        else{
            $orderIDs = OrderService::whereIn('service_id',$services)->pluck('order_id')->toArray();
            $orderIDs = array_unique($orderIDs);
            $orders = $orders->whereIn('id',$orderIDs)->orderBy('created_at','DESC')->get();
        }
        $message = parent::messages('success_process');
        return parent::jsonResponse(200,$message,$orders);
    }
}
