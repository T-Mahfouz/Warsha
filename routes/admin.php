<?php

use Illuminate\Http\Request;




Route::get('test',function(){
		$cat = App\Category::first();
		return $cat->childrenIDs();
});


view()->share('resource',url('').'/');

Route::group(['prefix' => 'admin'],function(){

	Route::get('home','Admin\AdminController@home')->name('admin-home');

	######## Admins
	Route::get('admins','Admin\AdminController@admins')->name('admin-admins');
	Route::post('deleteadmin','Admin\AdminController@deleteAdmin')->name('admin-delete-admin');
	Route::post('activateAdmin','Admin\AdminController@activateAdmin')->name('admin-change-admin-status');
	Route::post('editadmin','Admin\AdminController@editAdmin')->name('admin-edit-admin');
	Route::post('addadmin','Admin\AdminController@addAdmin')->name('admin-add-admin');

	Route::post('editMe','Admin\AdminController@editMe')->name('admin-edit-admin-profile');

	######## Users
	Route::get('users','Admin\AdminController@users')->name('admin-users');
	Route::post('deleteuser','Admin\AdminController@deleteUser')->name('admin-delete-user');
	Route::post('deleteuserpost','Admin\AdminController@deleteUserPost')->name('admin-delete-user-post');
	Route::post('activateuser','Admin\AdminController@activateUser')->name('admin-change-user-status');
	Route::post('editUser','Admin\AdminController@editUser')->name('admin-edit-user');
	Route::post('addUser','Admin\AdminController@addUser')->name('admin-add-user');
	Route::post('sendNotifications','Admin\AdminController@sendNotifications')->name('admin-send-notifications');

	######## Workshops
	Route::get('workshops','Admin\AdminController@workshops')->name('admin-workshops');
	Route::post('deleteworkshop','Admin\AdminController@deleteWorkshop')->name('admin-delete-workshop');
	Route::post('activateworkshop','Admin\AdminController@activateWorkshop')->name('admin-change-workshop-status');
	Route::post('editWorkshop','Admin\AdminController@editWorkshop')->name('admin-edit-workshop');
	Route::post('addWorkshop','Admin\AdminController@addWorkshop')->name('admin-add-workshop');


	######## About Us
	Route::get('aboutus','Admin\AdminController@aboutus')->name('admin-aboutus');
	Route::post('editaboutus','Admin\AdminController@editAboutus')->name('admin-edit-aboutus');

	######## Messages
	Route::get('messages','Admin\AdminController@contactus')->name('admin-messages');
	Route::post('messages','Admin\AdminController@deleteContactus')->name('admin-delete-contactus');
	Route::post('addreply','Admin\AdminController@addReply')->name('admin-add-reply');

	######## Services
	Route::get('services','Admin\AdminController@services')->name('admin-services');
	Route::post('delete-service','Admin\AdminController@deleteService')->name('admin-delete-service');
	Route::post('add-service','Admin\AdminController@addService')->name('admin-add-service');
	Route::post('edit-service','Admin\AdminController@editService')->name('admin-edit-service');

	######## Orders
	Route::get('orders','Admin\AdminController@orders')->name('admin-orders');
	Route::post('delete-order','Admin\AdminController@deleteOrder')->name('admin-delete-order');
	Route::post('delete-offer','Admin\AdminController@deleteOffer')->name('admin-delete-offer');



	Route::get('/','Admin\AdminController@login')->name('admin-login');
	Route::post('submit-login','Admin\AdminController@submitLogin')->name('admin-submit-login');


	Route::get('logout','Admin\AdminController@logout')->name('admin-logout');

});
