<?php

namespace App\Http\Controllers\Admin;

use App\Models\About;
use App\Models\Admin;
use App\Models\Contact;
use App\Models\User;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderImage;
use App\Models\OrderService;
use App\Models\Service;
use App\Models\WorkshopRate;
use App\Models\UserService;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        \App::setLocale('ar');
        Carbon::setLocale('ar');

        $this->middleware('auth:admin', [
            'except'=>['login','submitLogin']
        ]);
        $this->middleware('verified', [
            'except'=>['login','submitLogin']
        ]);
    }

    public function home(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $users = User::where('role_id',1)->get();
        $admins = Admin::all();
        $workshops = User::where('role_id',2)->get();
        $orders = Order::all();
        $services = Service::all();

        return view('admin.home')->with([
            'admin' => $admin,
            'admins' => $admins,
            'users' => $users,
            'workshops' => $workshops,
            'orders' => $orders,
            'services' => $services,
        ]);
    }

    ######## Admins
    public function admins(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $admins = new Admin;
        if ($request->has('key') && $request->key != null) {
            $admins = $admins->where('name', 'LIKE', '%'.$request->key.'%')
            ->orWhere('phone', 'LIKE', '%'.$request->key.'%')
            ->orWhere('email', 'LIKE', '%'.$request->key.'%');
        }
        $admins = $admins->paginate(10);

        return view('admin.admins')->with([
            'admins' => $admins,
        ]);
    }
    public function deleteAdmin(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $adminID = $request->admin_id;
        $admin = Admin::find($adminID);

        if (!$admin || $adminID == $me->id) {
            return response()->json('غير موجود');
        }

        $admin->delete();

        return response()->json('تم الحذف بنجاح.');
    }
    public function editAdmin(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $adminID = $request->admin_id;
        $admin = Admin::find($adminID);

        if (!$admin) {
            session()->flash('feedback', 'غير موجود');
            return redirect()->back();
        }
        if ($me->role != 2) {
            session()->flash('feedback', 'غير مصرح لك');
            return redirect()->back();
        }


        $name = $request->has('name')?$request->name:$admin->name;
        $email = $request->has('email')?$request->email:$admin->email;
        $phone = $request->has('phone')?$request->phone:$admin->phone;
        $password = ($request->has('password') && $request->password)?$request->password:$admin->password;

        $flagPassword = $request->password;

        $request->merge([
            'id' => $admin->id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
        ]);



        $notvalid = parent::AdminValidator($request);
        if ($notvalid) {
            session()->flash('feedback', $notvalid['ar']);
            return redirect()->back();
        }

        if ($flagPassword) {
            $password = bcrypt($flagPassword);
        }

        $fileName = $oldImage = $admin->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if ($moved) {
                $file = $destination.'/'.$oldImage;
                if (file_exists($file)) {
                    if ($oldImage != 'default.png') {
                        Storage::delete($oldImage);
                        //public_path().'/uploads/'.$files->filePathColumnName
                      //unlink($file);
                    }
                }
            }
        }

        $admin->name = $name;
        $admin->email = $email;
        $admin->phone = $phone;
        $admin->password = $password;
        $admin->image = $fileName;
        $admin->update();

        session()->flash('feedback', 'تم التعديل بنجاح');
        return redirect()->back();
    }
    public function activateAdmin(Request $request)
    {
        $me = Auth::guard('admin')->user();

        if ($me->role != 2) {
            session()->flash('feedback', 'غير مصرح لك');
            return redirect()->back();
        }

        $adminID = $request->admin_id;
        $admin = Admin::find($adminID);
        if (!$admin) {
            session()->flash('feedback', 'المستخدم غير  موجود!!');
            return redirect()->back();
        }

        $status = 1;
        if ($user->status) {
            $status = 0;
        }
        $admin->status = $status;
        $admin->update();

        session()->flash('feedback', 'تم التعديل بنجاح.');

        return redirect()->back();
    }
    public function addAdmin(Request $request)
    {
        $me = Auth::guard('admin')->user();

        if ($me->role != 2) {
            session()->flash('feedback', 'غير موجود');
            return redirect()->back();
        }

        $notValid = parent::adminValidator($request);
        if ($notValid) {
            session()->flash('feedback', $notValid['ar']);
            return redirect()->back();
        }

        $name = $request->name;
        $role = 1;
        $email = $request->email;
        $phone = $request->phone;
        $status = 1;
        $password = bcrypt($request->password);


        $fileName = null;
        if ($request->hasfile('image')) {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $request->file('image')->move($destination, $fileName);
        }


        $admin = Admin::create([
            'name' => $name,
            'role' => $role,
            'email' => $email,
            'phone' => $phone,
            'status' => $status,
            'password' => $password,
            'image' => $fileName,
            'code' => null,
            'firebase' => null,
            'last_seen' => Carbon::now(),
        ]);

        session()->flash('feedback', 'تمت الإضافة بنجاح.');
        return redirect()->back();
    }
    public function editMe(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $adminID = $request->admin_id;
        $admin = Admin::find($adminID);

        if (!$admin || $adminID != $me->id) {
            session()->flash('feedback', 'غير موجود');
            return redirect()->back();
        }


        $name = $request->has('name')?$request->name:$admin->name;
        $email = $request->has('email')?$request->email:$admin->email;
        $phone = $request->has('phone')?$request->phone:$admin->phone;
        $password = ($request->has('password') && $request->password)?$request->password:$admin->password;

        $flagPassword = $request->password;

        $request->merge([
            'id' => $admin->id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
        ]);



        $notvalid = parent::AdminValidator($request);
        if ($notvalid) {
            session()->flash('feedback', $notvalid['ar']);
            return redirect()->back();
        }

        if ($flagPassword) {
            $password = bcrypt($flagPassword);
        }

        $fileName = $oldImage = $admin->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if ($moved) {
                $file = $destination.'/'.$oldImage;
                if (file_exists($file)) {
                    if ($oldImage != 'default.png') {
                        unlink($file);
                    }
                }
            }
        }

        $admin->name = $name;
        $admin->email = $email;
        $admin->phone = $phone;
        $admin->password = $password;
        $admin->image = $fileName;
        $admin->update();

        session()->flash('feedback', 'تم التعديل بنجاح');
        return redirect()->back();
    }
    ######## Users
    public function users(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $allusers = User::where('role_id',1)->get();
        $users = User::where('role_id',1);
        if ($request->has('key') && $request->key != null) {
            $users = $users->where('name', 'LIKE', '%'.$request->key.'%')
            ->orWhere('phone', 'LIKE', '%'.$request->key.'%')
            ->orWhere('email', 'LIKE', '%'.$request->key.'%');
        }

        $users = $users->paginate(10);
        //return $categories;
        return view('admin.users')->with([
            'users' => $users,
            'allusers' => $allusers
        ]);
    }
    public function deleteUser(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $userID = $request->user_id;
        $user = User::find($userID);

        if (!$user) {
            session()->flash('feedback', 'هذا المسنخدم غير موجود!!');
            return redirect()->back();
        }
        $orders = $user->orders;

        OrderImage::whereIn('order_id', $orders->pluck('id')->toArray())->delete();
        OrderService::whereIn('order_id', $orders->pluck('id')->toArray())->delete();
        WorkshopRate::where('user_id',$userID)->delete();
        Order::whereIn('id',$orders->pluck('id')->toArray())->delete();

        $user->delete();

        session()->flash('feedback', 'تم الحذف بنجاح.');
        return redirect()->back();
    }
    public function activateUser(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $userID = $request->user_id;
        $user = User::find($userID);
        if (!$user) {
            session()->flash('feedback', 'المستخدم غير  موجود!!');
            return redirect()->back();
        }

        $status = 1;
        if ($user->status) {
            $status = 0;
        }
        $user->status = $status;
        $user->update();

        session()->flash('feedback', 'تم التعديل بنجاح.');

        return redirect()->back();
    }
    public function editUser(Request $request)
    {
        $me = Auth::guard('admin')->user();
        $userID = $request->user_id;
        $user = User::find($userID);
        if (!$user) {
            session()->flash('feedback', 'غير موجود');
            return redirect()->back();
        }

        $phone = $request->has('phone')?$request->phone:$user->phone;
        $whatsapp = $request->has('whatsapp')?$request->whatsapp:$user->whatsapp;
        $name = $request->has('name')?$request->name:$user->name;
        $username = $request->has('username')?$request->username:$user->username;
        $email = $request->has('email')?$request->email:$user->email;

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
            'name' => $name,
            'role_id' => 1,
            'username' => $username,
            'email' => $email,
            'phone' => $phone,
            'whats' => $whatsapp,
        ]);

        $notvalid = parent::UserValidator($request);
        if ($notvalid) {
            session()->flash('feedback', $notvalid['ar']);
            return redirect()->back();
        }

        $password = ($newPassword)?bcrypt($newPassword):$oldPassword;

        $fileName = $oldImage = $user->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if ($moved) {
                $file = $destination.'/'.$oldImage;
                if (file_exists($file)) {
                    if ($oldImage != 'default.png') {
                        unlink($file);
                    }
                }
            }
        }

        $user->name = $name;
        $user->email = $email;
        $user->phone = $phone;
        $user->whatsapp = $whatsapp;
        $user->image = $fileName;
        $user->username = $username;
        $user->password = $password;
        $user->update();

        session()->flash('feedback', 'تم التعديل بنجاح');
        return redirect()->back();
    }
    public function addUser(Request $request)
    {
        $request->merge(['role_id' => 1]);
        $notvalid = parent::UserValidator($request);
        if ($notvalid) {
            session()->flash('feedback', $notvalid['ar']);
            return redirect()->back()->withInput(Input::all());
        }

        $fileName = 'default.png';
        if ($request->hasfile('image')) {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);
            $request->file('image')->move($destination, $fileName);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'role_id' => 1,
            'email' => $request->email,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'image' => $fileName,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'status' => 1,
        ]);


        session()->flash('feedback', 'تمت الإضافة بنجاح.');
        return redirect()->back();
    }

    ######## Workshops
    public function workshops(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $allworkshops = User::where('role_id',2)->get();
        $workshops = User::where('role_id',2);
        if ($request->has('key') && $request->key != null) {
            $workshops = $workshops->where('name', 'LIKE', '%'.$request->key.'%')
            ->orWhere('phone', 'LIKE', '%'.$request->key.'%')
            ->orWhere('email', 'LIKE', '%'.$request->key.'%');
        }

        $workshops = $workshops->paginate(10);
        foreach($workshops as $workshop) {
          $url = urlencode("https://www.google.com/maps/search/?api=1&query=".$workshop->lat.",".$workshop->lon);
          $qr = "http://chart.apis.google.com/chart?chs=200x200&&cht=qr&chl=".$url;
          $workshop['qr_location'] = $qr;
        }
        return view('admin.workshops')->with([
            'workshops' => $workshops,
            'allworkshops' => $allworkshops,
        ]);
    }
    public function deleteWorkshop(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $workshopID = $request->user_id;
        $workshop = User::find($workshopID);

        if (!$workshop) {
            session()->flash('feedback', 'هذا المسنخدم غير موجود!!');
            return redirect()->back();
        }

        WorkshopRate::where('workshop_id',$workshopID)->delete();
        UserService::where('user_id',$workshopID)->delete();
        Offer::where('user_id',$workshopID)->delete();

        $workshop->delete();

        session()->flash('feedback', 'تم الحذف بنجاح.');
        return redirect()->back();
    }
    public function activateWorkshop(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $userID = $request->user_id;
        $user = User::find($userID);
        if (!$user) {
            session()->flash('feedback', 'المستخدم غير  موجود!!');
            return redirect()->back();
        }

        $status = 1;
        if ($user->status) {
            $status = 0;
        }
        $user->status = $status;
        $user->update();

        session()->flash('feedback', 'تم التعديل بنجاح.');

        return redirect()->back();
    }
    public function editWorkshop(Request $request)
    {
        $me = Auth::guard('admin')->user();
        $userID = $request->user_id;
        $user = User::find($userID);
        if (!$user) {
            session()->flash('feedback', 'غير موجود');
            return redirect()->back();
        }

        $phone = $request->has('phone')?$request->phone:$user->phone;
        $whatsapp = $request->has('whatsapp')?$request->whatsapp:$user->whatsapp;
        $name = $request->has('name')?$request->name:$user->name;
        $username = $request->has('username')?$request->username:$user->username;
        $email = $request->has('email')?$request->email:$user->email;

        $oldPassword = $user->password;
        $newPassword = $request->password;
        if (!$newPassword) {
            $request->merge([
                'password' => $oldPassword,
                'password_confirmation' => $oldPassword,
            ]);
        }
        $location = explode(',',$request->location);
        if(count($location) < 2) {
            session()->flash('feedback','موقع الورشة غير صالح');
            return redirect()->back()->withInput(Input::all());
        }
        $lat = (float)$location[0];
        $lon = (float)$location[1];

        $request->merge([
            'id' => $user->id,
            'name' => $name,
            'role_id' => 2,
            'lat' => $lat,
            'lon' => $lon,
            'username' => $username,
            'email' => $email,
            'phone' => $phone,
            'whats' => $whatsapp,
        ]);

        $notvalid = parent::UserValidator($request);
        if ($notvalid) {
            session()->flash('feedback', $notvalid['ar']);
            return redirect()->back();
        }

        $password = ($newPassword)?bcrypt($newPassword):$oldPassword;

        $fileName = $oldImage = $user->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if ($moved) {
                $file = $destination.'/'.$oldImage;
                if (file_exists($file)) {
                    if ($oldImage != 'default.png') {
                        unlink($file);
                    }
                }
            }
        }

        $user->name = $name;
        $user->email = $email;
        $user->phone = $phone;
        $user->whatsapp = $whatsapp;
        $user->image = $fileName;
        $user->lat = $lat;
        $user->lon = $lon;
        $user->username = $username;
        $user->password = $password;
        $user->update();

        session()->flash('feedback', 'تم التعديل بنجاح');
        return redirect()->back();
    }
    public function addWorkshop(Request $request)
    {
        $location = explode(',',$request->location);
        if(count($location) < 2) {
            session()->flash('feedback','موقع الورشة غير صالح');
            return redirect()->back()->withInput(Input::all());
        }
        $lat = (float)$location[0];
        $lon = (float)$location[1];

        $request->merge([
            'role_id' => 2,
            'lat' => $lat,
            'lon' => $lon,
        ]);
        $notvalid = parent::UserValidator($request);
        if ($notvalid) {
            session()->flash('feedback', $notvalid['ar']);
            return redirect()->back()->withInput(Input::all());
        }

        $fileName = 'default.png';
        if ($request->hasfile('image')) {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);
            $request->file('image')->move($destination, $fileName);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'role_id' => 2,
            'email' => $request->email,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'image' => $fileName,
            'lat' => $lat,
            'lon' => $lon,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'status' => 1,
        ]);
        session()->flash('feedback', 'تمت الإضافة بنجاح.');
        return redirect()->back();
    }

    ######## About Us
    public function aboutus(Request $request)
    {
        $aboutus = About::first();

        return view('admin.aboutus')->with([
            'aboutus' => $aboutus
        ]);
    }
    public function editAboutus(Request $request)
    {
        $aboutus = About::first();
        $title = $request->title;
        $content = $request->content;
        if (!$title || !$content) {
            session()->flash('feedback', 'يجب إدخال جميع الحقول');
            return redirect()->back();
        }
        if (!$aboutus) {
            About::create([
                'title' => $title,
                'content' => $content,
            ]);
        } else {
            $aboutus->title = $title;
            $aboutus->content = $content;
            $aboutus->update();
        }
        session()->flash('feedback', 'تم التعديل بنجاح.');
        return redirect()->back();
    }


    ######## Contact
    public function contactus(Request $request)
    {
        $contacts = Contact::orderBy('created_at','DESC');
        $contacts = $contacts->paginate(10);

        return view('admin.contacts')->with([
            'contacts' => $contacts
        ]);
    }
    public function deleteContactus(Request $request)
    {
        $messages_id = $request->messages_id;
        $contact = Contact::find($messages_id);

        if (!$contact) {
            return response()->json('غير  موجود');
        }

        $contact->delete();

        return response()->json('تم الحذف');
    }

    ######## Services
    public function services(Request $request)
    {
        $page = $request->page;
        $items = 10;

        $services = Service::paginate($items);
        return view('admin.services')->with([
            'services' => $services,
            'page' => $page,
            'items' => $items,
        ]);
    }
    public function addService(Request $request)
    {
        if($request->name == null) {
            session()->flash('feedback','يجب إدخال جميع البيانات');
            return redirect()->back();
        }

        $fileName = '';
        if ($request->hasfile('image')) {
            $destination = public_path('images/services');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);
            $moved = $request->file('image')->move($destination, $fileName);
        }

        Service::create([
            'name' => $request->name,
            'icon' => $fileName,
        ]);

        session()->flash('feedback','تمت الإضافة بنجاح');
        return redirect()->back();
    }
    public function editService(Request $request)
    {
        $serviceID = $request->service_id;
        $service = Service::find($serviceID);
        if(!$service) {
            session()->flash('feedback','الخدمة غير موجودة');
            return redirect()->back();
        }
        if($request->name == null) {
            session()->flash('feedback','يجب إدخال جميع البيانات');
            return redirect()->back();
        }

        $fileName = $oldIcon = $service->icon;
        if ($request->hasfile('image')) {
            $destination = public_path('images/services');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if ($moved) {
                $file = $destination.'/'.$oldIcon;
                if (file_exists($file) && is_file($file)) {
                    unlink($file);
                }
            }
        }

        $service->name = $request->name;
        $service->icon = $fileName;
        $service->update();

        session()->flash('feedback','تم التعديل بنجاح');
        return redirect()->back();
    }
    public function deleteService(Request $request)
    {
        $serviceID = $request->service_id;
        $service = Service::find($serviceID);
        if(!$service) {
            session()->flash('feedback','الخدمة غير موجودة');
            return redirect()->back();
        }

        OrderService::where('service_id',$serviceID)->delete();
        UserService::where('service_id',$serviceID)->delete();
        $service->delete();

        session()->flash('feedback','تم الحذف بنجاح');
        return redirect()->back();
    }

    ######## Orders
    public function orders(Request $request)
    {
        $page = $request->page;
        if(!$page)
            $page = 1;
        $items = 10;

        $orders = Order::orderBy('created_at','DESC');
        $orders = $orders->paginate($items);
        foreach($orders as $order) {
            $order['offers'] = Offer::where('order_id',$order->id)->orderBy('created_at','DESC')->get();
            $url = urlencode("https://www.google.com/maps/search/?api=1&query=".$order->lat.",".$order->lon);
            $qr = "http://chart.apis.google.com/chart?chs=200x200&&cht=qr&chl=".$url;
            $order['qr_location'] = $qr;
        }
        return view('admin.orders')->with([
            'orders' => $orders,
            'page' => $page,
            'items' => $items,
        ]);
    }
    public function deleteOrder(Request $request)
    {
        $orderID = $request->order_id;
        $order = Order::find($orderID);
        if(!$order) {
            session()->flash('feedback','هذا الطلب غير موجود');
            return redirect()->back();
        }

        OrderImage::where('order_id',$orderID)->delete();
        OrderService::where('order_id',$orderID)->delete();
        Offer::where('order_id',$orderID)->delete();
        $order->delete();

        session()->flash('feedback','تم الحذف بنجاح');
        return redirect()->back();
    }
    public function deleteOffer(Request $request)
    {
        $offerID = $request->offer_id;
        $offer = Offer::find($offerID);
        if(!$offer) {
            return parent::jsonResponse(404,'not found');
        }
        $offer->delete();
        return parent::jsonResponse(200,'done.');
    }

    public function login(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin-home');
        }

        return view('admin.login');
    }
    public function submitLogin(Request $request)
    {
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], true)) {
            $admin = Auth::guard('admin')->user();

            return redirect()->route('admin-home');
        }


        return redirect()->back()->withInput(Input::all());
    }

    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        Auth::guard('admin')->logout();
        return redirect()->route('admin-login');
    }
}
