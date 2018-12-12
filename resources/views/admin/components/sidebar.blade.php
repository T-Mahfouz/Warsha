<aside>

	<div class="admin-pic_name col-md-12">
		<div class="col-md-4 offset-md-4">
			<img data-toggle='modal' data-target='#edit_admin_profile_modal' style="cursor: pointer;" class="pull-right" src="{{$resource.'images/users/'.Auth::guard('admin')->user()->image}}"/>
		</div>
		<div class="col-md-8 offset-md-2">
			<h4 class="pull-right">{{Auth::guard('admin')->user()->name}}</h4>
		</div>

	</div>
	<div class="clearfix"></div>
	<div class="navigation col-md-12">

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-home') active @endif" href="{{route('admin-home')}}">{{trans('lang.home')}} <i class="fa fa-home"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-admins') active @endif" href="{{route('admin-admins')}}">{{trans('lang.admins')}} <i class="fa fa-black-tie"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-users') active @endif" href="{{route('admin-users')}}">{{trans('lang.users')}} <i class="fa fa-users"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-workshops') active @endif" href="{{route('admin-workshops')}}">{{lang('workshops')}}  <i class="fa fa-users"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-aboutus') active @endif" href="{{route('admin-aboutus')}}">{{lang('aboutus')}}  <i class="fa fa-question-circle"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-messages') active @endif" href="{{route('admin-messages')}}">{{lang('messages')}}  <i class="fa fa-envelope"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-services') active @endif" href="{{route('admin-services')}}">{{lang('services')}}  <i class="fa fa-gear"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-orders') active @endif" href="{{route('admin-orders')}}">{{lang('orders')}}  <i class="fa fa-car"></i></a>



	</div>

</aside>




{{--  Start Modal  --}}
<div class="modal fade" id="edit_admin_profile_modal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="{{route('admin-edit-admin-profile')}}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="modal-body" style="direction: rtl;text-align: right;">
					<h3>تعديل البيانات</h3>
					<hr>

					<input type="hidden" value="{{Auth::guard('admin')->user()->id}}" name="admin_id">
					<div class="form-group">
						<label for="">{{trans('lang.name')}}:</label>
						<input type="text" class="form-control" value="{{Auth::guard('admin')->user()->name}}" name="name">
					</div>
					<div class="form-group">
						<label for="">{{trans('lang.email')}}:</label>
						<input type="text" class="form-control" name="email" value="{{Auth::guard('admin')->user()->email}}">
					</div>
					<div class="form-froup">
						<label for="phone">{{trans('lang.phone')}}</label>
						<input type="text" min="1" max="10" class="form-control" name="phone" placeholder="{{trans('lang.phone')}}" value="{{Auth::guard('admin')->user()->phone}}">
					</div>
					<div class="form-group">
						<label for="">{{trans('lang.password')}}:</label>
						<input type="password" class="form-control" name="password">
					</div>
					<div class="form-group">
						<label for="">{{trans('lang.image')}}:</label>
						<input type="file" class="form-control" name="image">
					</div>
				</div>
				<div class="modal-footer" style="display:block;">

					<button type="submit" class="btn btn-primary">{{trans('lang.edit')}}</button>

					<button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

				</div>
			</form>
		</div>
	</div>
</div>
{{--  End Modal  --}}
