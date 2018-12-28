<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Models\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommonAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','jwt.auth','activated']);
    }

    public function refresh(Request $request)
    {
        return Auth::refresh();
        return $this->respondWithToken(Auth::refresh());
    }
    public function me()
    {
        $user = Auth::user();
        if($user->role_id == 2)
            $user['services'] = $user->services;
        $message = parent::messages('success_process');
        return parent::jsonResponse(200,$message,$user);
    }

    public function editProfile(Request $request)
    {
        $user = Auth::user();

        $name = $request->has('name')?$request->name:$user->name;
        $phone = $request->has('phone')?$request->phone:$user->phone;
        $whatsapp = $request->has('whatsapp')?$request->whatsapp:$user->whatsapp;
        $email = $request->has('email')?$request->email:$user->email;
        $lat = $request->has('lat')?$request->lat:$user->lat;
        $lon = $request->has('lon')?$request->lon:$user->lon;
        $firebase = $request->has('firebase')?$request->firebase:$user->firebase;
        $username = $request->has('username')?$request->username:$user->username;

        $oldPassword = $user->password;
        $newPassword = $request->password;
        if (!$newPassword) {
            $request->merge([
                'password' => $oldPassword,
                'password_confirmation' => $oldPassword,
            ]);
        }

        $request->merge([
            'id' => $user->id,
            'role_id' => $user->role_id,
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'phone' => $phone,
        ]);

        $password = ($newPassword)?bcrypt($newPassword):$oldPassword;

        $notvalid = parent::UserValidator($request);
        if ($notvalid) {
            return parent::jsonResponse(400, $notvalid['ar']);
        }

        $fileName = $oldImage = $user->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            if (!in_array(strtolower($extension), ['jpg','jpeg','png'])) {
                return parent::jsonResponse(400, parent::messages('error_image'));
            }
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if ($moved && ($oldImage != 'default.png')) {
                $path = $destination.'/'.$oldImage;
                if (file_exists($path)) {
                    Storage::delete($path);
                }
            }
        }

        $user->name = $name;
        $user->username = $username;
        $user->phone = $phone;
        $user->whatsapp = $whatsapp;
        $user->email = $email;
        $user->lat = $lat;
        $user->lon = $lon;
        $user->image = $fileName;
        $user->firebase = $firebase;
        $user->password = $password;
        $user->last_seen = Carbon::now();

        $user->update();
        if ($request->has('services')) {
            $services = explode(',', $request->services);
            if (count($services)) {
                UserService::where('user_id',$user->id)->delete();
                foreach ($services as $serviceID) {
                    $service = Service::find($serviceID);
                    if ($service) {
                        UserService::create([
                            'user_id' => $user->id,
                            'service_id' => $serviceID,
                        ]);
                    }
                }
            }
        }
        if($user->role_id == 2)
            $user['services'] = $user->services;
        $message = parent::messages('success_edit_profile');
        return $this->jsonResponse(200, $message, $user);
    }
    public function orders(Request $request)
    {
        $user = Auth::user();
        $orders = Order::where('status',0)->get();
        if(count($orders)) {
            foreach($orders as $order) {
                $offers = $order->offers;
                $owner = $order->user;
                if(count($offers)) {
                    foreach($offers as $offer) {
                        $offer['workshop'] = $offer->workshop;
                    }
                }
                $order['offers'] = $offers;
                $order['user'] = $owner;
            }
        }
        $message = parent::messages('success_process');
        return parent::jsonResponse(200,$message,$orders);
    }
    public function offers(Request $request)
    {
        $user = Auth::user();
        $orderID = $request->order_id;
        $order = Order::find($orderID);
        if(!$order) {
            $message = parent::messages('not_found');
            return parent::jsonResponse(404, $message);
        }
        $offers = $order->offers;
        if(count($offers)) {
            foreach($offers as $offer) {
                $offer['workshop'] = $offer->workshop;
            }
        }
        $message = parent::messages('success_process');
        return parent::jsonResponse(200,$message,$offers);
    }
    public function orderInfo(Request $request)
    {
        $user = Auth::user();
        $orderID = $request->order_id;
        $order = Order::find($orderID);
        if(!$order) {
            $message = parent::messages('not_found');
            return parent::jsonResponse(404, $message);
        }
        $order['order_images'] = $order->orderImages();
        $order['order_services'] = $order->orderServices();
        unset($order['images']);
        unset($order['services']);

        $offers = $order->offers;
        if(count($offers)) {
            foreach($offers as $offer) {
                $offer['workshop'] = $offer->workshop;
            }
        }
        $order['offers'] = $offers;
        $order['user'] = $order->user;

        $message = parent::messages('success_process');
        return parent::jsonResponse(200,$message,$order);
    }
    public function userInfo(Request $request)
    {
        $me = Auth::user();
        $userID = $request->user_id;
        $user = User::find($userID);
        if(!$user) {
            $message = parent::messages('not_found');
            return parent::jsonResponse(404, $message);
        }
        $message = parent::messages('success_process');
        return parent::jsonResponse(200,$message,$user);
    }
    ###########################################
    protected function respondWithToken($token)
    {
        return [
      'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => Auth::guard('api')->factory()->getTTL() * 3600
    ];
    }
    public function logout()
    {
        $user = Auth::user();
        $user->last_seen = Carbon::now();
        $user->update();

        Auth::logout();

        $message = 'تم تسجيل الخروج بنجاح';
        return $this->jsonResponse(200, $message);
    }
}
