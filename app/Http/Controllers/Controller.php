<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public static function jsonResponse($code, $message, $data=[], $jcode=0)
    {
        if ($jcode == 0) {
            $jcode = $code;
        }
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], $jcode);
    }

    public static function generateCode($digits)
    {
        $digits = (int)$digits;
        $vc = rand(pow(10, $digits-1), pow(10, $digits)-1);
        return $vc;
    }

    public static function UserValidator(Request $request)
    {
        $validationErrors = [];
        $fullnameValidator = Validator::make(
            $request->only('name'),
            ['name' => 'required|string|max:255|min:2']
        );

        if ($fullnameValidator->fails()) {
            $errors = $fullnameValidator->errors()->toArray();
            $validationErrors['ar'] = 'الاسم غير صالح';
            $validationErrors['en'] = 'Name not valid!';
        }
        /* ============ Username ========== */
        $usernameValidator = Validator::make(
            $request->only('username'),
            ['username' => 'required|string|max:255|min:2|unique:users,username,'.$request->id]
        );

        if ($usernameValidator->fails()) {
            $errors = $usernameValidator->errors()->toArray();
            $validationErrors['ar'] = 'اسم الدخول غير صحيح أو موجود بالفعل';
            $validationErrors['en'] = 'Username not valid or has been taken before!!';
        }

        /* ============ Phone ========== */
        $phonevalidator = Validator::make(
            $request->only('phone'),
            ['phone' => 'required|digits_between:11,14|unique:users,phone,'.$request->id]
        );
        if ($phonevalidator->fails()) {
            $errors=$phonevalidator->errors()->toArray();
            $validationErrors['ar'] = 'رقم الجوال غير صحيح أو موجود بالفعل';
            $validationErrors['en'] = 'Phone not valid or has been taken before!';
        }
        /* ============ WhatsApp ========== */
        $phonevalidator = Validator::make(
            $request->only('whatsapp'),
            ['whatsapp' => 'sometimes|digits_between:11,14|unique:users,whatsapp,'.$request->id]
        );
        if ($phonevalidator->fails()) {
            $errors=$phonevalidator->errors()->toArray();
            $validationErrors['ar'] = 'رقم الواتساب غير صالح أو موجود بالفعل';
            $validationErrors['en'] = 'WhatsApp is not valid or has been taken before!';
        }

        /* ============ Role ========== */
        $phonevalidator = Validator::make(
            $request->only('role_id'),
            ['role_id' => 'required']
        );
        if ($phonevalidator->fails()) {
            $errors = $phonevalidator->errors()->toArray();
            $validationErrors['ar'] = 'يجب تحديد صلاحيات العميل';
            $validationErrors['en'] = 'You should define Role Type!';
        }

        /* ============ Email ========== */
        $emailvalidator = Validator::make(
            $request->only('email'),
            ['email' => 'required|email|unique:users,email,'.$request->id]
        );
        if ($emailvalidator->fails()) {
            $errors=$emailvalidator->errors()->toArray();
            $validationErrors['ar'] = 'البريد الإلكترونى غير صحيح أو موجود بالفعل';
            $validationErrors['en'] = 'Email not valid or has been taken before!';
        }

        /* ============ Password ========== */
        $passwordvalidator = Validator::make(
            $request->only('password', 'password_confirmation'),
            ['password' => 'required|min:4|confirmed']
        );
        if ($passwordvalidator->fails()) {
            $errors=$passwordvalidator->errors()->toArray();
            $validationErrors['ar'] = ' كلمة المرور يجب أن تكون 4 حروف على الأقل ويجب تأكيد كلمة المرور';
            $validationErrors['en'] = 'Password must be 4 characters as minimum and be confirmed!';
        }
        if($request->role_id == 2) {
            /* ============ Lat ========== */
            $latValidator = Validator::make(
                $request->only('lat'),
                ['lat' => 'required|numeric']
            );
            if ($latValidator->fails()) {
                $errors = $latValidator->errors()->toArray();
                $validationErrors['ar'] = "يجب إدخال الموقع الجغرافى للمكان مثال   45.85927,2.63360";
                $validationErrors['en'] = 'GEO location not valid!';
            }
            /* ============ Lon ========== */
            $lonValidator = Validator::make(
                $request->only('lon'),
                ['lon' => 'required|numeric']
            );
            if ($lonValidator->fails()) {
                $errors = $lonValidator->errors()->toArray();
                $validationErrors['ar'] = "يجب إدخال الموقع الجغرافى للمكان مثال   45.85927,2.63360";
                $validationErrors['en'] = 'GEO location not valid!';
            }
        }

        return $validationErrors;
    }

    public static function OrderValidator(Request $request)
    {
        $validationErrors = [];

        /* ============ Address ========== */
        $valid = Validator::make(
            $request->only('car_type'),
            ['car_type' => 'required|min:3']
        );
        if ($valid->fails()) {
            $errors = $valid->errors()->toArray();
            $validationErrors['ar'] = "يجب تحديد نوع السيارة";
            $validationErrors['en'] = 'Car type not valid!';
        }
        /* ============ Car Model ========== */
        $valid = Validator::make(
            $request->only('car_model'),
            ['car_model' => 'required']
        );
        if ($valid->fails()) {
            $errors = $valid->errors()->toArray();
            $validationErrors['ar'] = "يجب تحديد موديل السيارة";
            $validationErrors['en'] = 'Car model not valid!';
        }
        /* ============ Address ========== */
        $valid = Validator::make(
            $request->only('address'),
            ['address' => 'sometimes|min:10']
        );
        if ($valid->fails()) {
            $errors = $valid->errors()->toArray();
            $validationErrors['ar'] = "العنوان غير صالح";
            $validationErrors['en'] = 'Address not valid!';
        }

        /* ============ Description ========== */
        $valid = Validator::make(
            $request->only('description'),
            ['description' => 'required|min:50']
        );
        if ($valid->fails()) {
            $errors = $valid->errors()->toArray();
            $validationErrors['ar'] = "الوصف غير كافى";
            $validationErrors['en'] = 'Problem description not enough!';
        }
        //
        if(!$request->has('address')) {
            /* ============ Lat ========== */
            $valid = Validator::make(
                $request->only('lat'),
                ['lat' => 'required|numeric']
            );
            if ($valid->fails()) {
                $errors = $valid->errors()->toArray();
                $validationErrors['ar'] = "يجب إدخال الموقع الجغرافى للمكان مثال   45.85927,2.63360";
                $validationErrors['en'] = 'GEO location not valid!';
            }
            /* ============ Lon ========== */
            $valid = Validator::make(
                $request->only('lon'),
                ['lon' => 'required|numeric']
            );
            if ($valid->fails()) {
                $errors = $valid->errors()->toArray();
                $validationErrors['ar'] = "يجب إدخال الموقع الجغرافى للمكان مثال   45.85927,2.63360";
                $validationErrors['en'] = 'GEO location not valid!';
            }
        }

        return $validationErrors;
    }

    public static function AdminValidator(Request $request)
    {
        $validationErrors = [];
        $nameValidator = Validator::make(
            $request->only('name'),
            ['name' => 'required|string|max:255|min:2']
        );

        if ($nameValidator->fails()) {
            $errors = $nameValidator->errors()->toArray();
            $validationErrors['ar'] = 'الاسم غير صالح';
            $validationErrors['en'] = 'Name not valid!';
        }
        /* ============ Phone ========== */
        $phonevalidator = Validator::make(
            $request->only('phone'),
            ['phone' => 'digits_between:11,14|unique:admins,phone,'.$request->id]
        );

        if ($phonevalidator->fails()) {
            $errors=$phonevalidator->errors()->toArray();
            $validationErrors['ar'] = 'رقم الجوال غير صحيح أو موجود بالفعل';
            $validationErrors['en'] = 'Phone not valid or has been taken before!';
        }
        /* ============ Email ========== */
        $emailvalidator = Validator::make(
            $request->only('email'),
            ['email' => 'required|email|unique:admins,email,'.$request->id]
        );

        if ($emailvalidator->fails()) {
            $errors=$emailvalidator->errors()->toArray();
            $validationErrors['ar'] = 'البريد الإلكترونى غير صحيح أو موجود بالفعل';
            $validationErrors['en'] = 'Email not valid or has been taken before!';
        }
        /* ============ Password ========== */
        $passwordvalidator = Validator::make(
            $request->only('password'),
            ['password' => 'required|min:4']
        );
        if ($passwordvalidator->fails()) {
            $errors=$passwordvalidator->errors()->toArray();
            $validationErrors['ar'] = 'كلمة المرور يجب أن تكون 4 حروف على الأقل';
            $validationErrors['en'] = 'Password must be 4 characters as minimum!';
        }

        return $validationErrors;
    }

    public static function ContactUsValidator(Request $request)
    {
        $validationErrors = [];
        $nameValidator = Validator::make(
            $request->only('name'),
            ['name' => 'required|string|max:255|min:2']
        );

        if ($nameValidator->fails()) {
            $errors = $nameValidator->errors()->toArray();
            $validationErrors['ar'] = 'الاسم غير صالح';
            $validationErrors['en'] = 'name not valid!';
        }
        /* ============ Content ========== */
        $contentValidator = Validator::make(
            $request->only('message'),
            ['message' => 'required|string|min:25']
        );

        if ($contentValidator->fails()) {
            $errors = $contentValidator->errors()->toArray();
            $validationErrors['ar'] = 'محتوى الرسالة  غير صالح';
            $validationErrors['en'] = 'Content not valid!';
        }
        /* ============ Email ========== */
        $emailvalidator = Validator::make(
            $request->only('email'),
            ['email' => 'required|email']
        );

        if ($emailvalidator->fails()) {
            $errors=$emailvalidator->errors()->toArray();
            $validationErrors['ar'] = 'البريد الإلكترونى غير صحيح ';
            $validationErrors['en'] = 'Email not valid!';
        }

        return $validationErrors;
    }

    public static function FCMPush($tokens, $title, $body, $type, $data=[])
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $data = array(
            "title" => $title,
            "body"  => $body,
            "type"  => $type,
            "data"  => $data,
            'content_available'=> true,
            'vibrate' => 1,
            'sound' => true,
            'priority'=> 'high'
        );
        $fields = array(
            'to' =>$tokens,
            'notification' => $data
        );
        $fcmApiKey ='AAAAhuUat6Y:APA91bG0pSPiaXoOfGBPtF6vb5S1zoHIGJjY9eMi6dFGf_J3KdAaa_jnqvUL_A4n8vDajjiBfHYUN0yK_5N4h2Wqq7PStcJDcM6D_J4RTz-UqZQyCC5KOvdSQsV3Ae8wO7gjdCE8Glsr';
        $headers = array(
            'Authorization: key=' . $fcmApiKey,
            'Content-Type:application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === false) {
            die('cUrl faild: '.curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public static function messages($code)
    {
        $messages = [
            'success_add' => 'تمت الإضافة بنجاح',
            'success_process' => 'تمت العملية بنجاح',
            'success_signup' => 'تم الاشتراك بنجاح',
            'success_edit_profile' => 'تم التعديل بنجاح',
            'success_message_sent' => 'تم إرسال الرسالة بنجاح',
            'credential_error' => 'خطأ فى الاسم أو الرقم السرى',
            'error_image' => 'الصورة غير صالحة',
            'error_user' => 'هذا العضو غير موجود',
            'error_rate_value' => 'التقييم غير صحيح',
            'error_rate_exist' => 'تم التقييم من قبل',
            'error_offered_exist' => 'تم إضافة عرضك من قبل',
            'error_post_content' => 'يجب إدخال المحتوى',
            'error_post_content_length' => 'المحتوى قصير جدا',
            'not_found' => 'غير موجود',
            'missing_data' => 'البيانات غير مكتملة',
        ];
        return $messages[$code];
    }
}
