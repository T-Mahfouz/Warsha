<?php

namespace App\Http\Controllers\Api;

use App\Models\About;
use App\Models\Contact;
use App\Models\Service;
use App\Models\User;
use App\Models\UserService;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            $message = parent::messages('credential_error');
            return $this->jsonResponse(401, $message);
        }

        $user = Auth::guard('api')->user();

        $user->last_seen = Carbon::now();
        $user->firebase = $request->firebase;
        $user->update();

        $auth = $this->respondWithToken($token);

        $data = array_merge($user->toArray(), $auth);

        $message = parent::messages('success_process');
        return $this->jsonResponse(200, $message, $data);
    }
    public function signup(Request $request)
    {
        $notvalid = parent::UserValidator($request);
        if ($notvalid) {
            return parent::jsonResponse(400, $notvalid['ar']);
        }

        $fileName = 'default.png';
        if ($request->hasfile('image')) {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);
            $request->file('image')->move($destination, $fileName);
        }

        $vc = $this->generateCode(4);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'whatsapp' => $request->whatsapp,
            'username' => $request->username,
            'image' => $fileName,
            'password' => bcrypt($request->password),
            'code' => $vc,
            'lat' => $request->lat,
            'lon' => $request->lon,
            'firebase' => $request->firebase,
            'last_seen' => Carbon::now(),
        ]);
        if ($request->has('services')) {
            $services = explode(',', $request->services);
            if (count($services)) {
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
        //$credentials = request([$user->username, $user->password]);
        //Auth::guard('api')->attempt($credentials)

        $token = Auth::guard('api')->login($user);

        $data = array_merge($user->toArray(), $this->respondWithToken($token));

        $message = parent::messages('success_signup');
        return $this->jsonResponse(200, $message, $data);
    }
    protected function respondWithToken($token)
    {
        return [
          'access_token' => $token,
          'token_type' => 'bearer',
          'expires_in' => Auth::guard('api')->factory()->getTTL() * 3600
      ];
    }

    public function aboutus(Request $request)
    {
        $message = parent::messages('success_process');
        return $this->jsonResponse(200, $message, About::first());
    }
    public function services(Request $request)
    {
        $services = Service::all();
        $message = parent::messages('success_process');
        return $this->jsonResponse(200, $message, $services);
    }
    public function sendContactUs(Request $request)
    {
        $notvalid = parent::ContactUsValidator($request);
        if ($notvalid) {
            return parent::jsonResponse(400, $notvalid['ar']);
        }

        $contactus = Contact::create($request->all());

        $message = parent::messages('success_message_sent');
        return parent::jsonResponse(201, $message, $contactus);
    }
}
