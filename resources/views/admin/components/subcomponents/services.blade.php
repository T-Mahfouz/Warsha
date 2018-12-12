<div class="main-header col-md-12">
	<!-- <h3>باقات التميز</h3> -->
	<button data-toggle='modal' data-target='#add_service_modal' class="add-button btn btn-primary">
		إضافة خدمة
	</button>
</div>

<div class="main-body col-md-12">

	@if(session()->has('feedback'))
	<div class="alert alert-info">
		{{session()->get('feedback')}}
	</div>
	@endif

	<table class="table table-hover">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">{{trans('lang.service_name')}}</th>
				<th scope="col">{{trans('lang.icon')}}</th>
				<th scope="col">{{trans('lang.options')}}</th>
			</tr>
		</thead>
		<tbody>

			@foreach($services as $index=>$item)

			<tr>
				<th scope="row">{{($page-1)*$items + ($index+1)}}</th>
				<td>{{$item->name}}</td>
				<td><img style="height:50px;width:50px;" src="{{$resource.'images/services/'.$item->icon}}" alt=""></td>
				<td>
						<button data-toggle="modal" data-target="#edit_service_{{$item->id}}_modal" class="btn btn-primary">{{trans('lang.edit')}}</button>
						<button data-toggle="modal" data-target="#delete_service_{{$item->id}}_modal" class="btn btn-danger">{{trans('lang.delete')}}</button>
				</td>
			</tr>

			{{-- EDIT MODAL --}}
			<div class="modal" tabindex="-1" role="dialog" id="edit_service_{{$item->id}}_modal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form action="{{route('admin-edit-service')}}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="modal-body">
								<input type="hidden" class="form-control" name="service_id" value="{{$item->id}}">
								<div class="form-group">
									<label for="title">{{lang('service_name')}}</label>
									<input type="text" class="form-control" name="name" value="{{$item->name}}">
								</div>
								<div class="form-group">
									<label for="title">{{lang('icon')}}</label>
									<input type="file" name="image" value="">
								</div>

							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-success">{{trans('lang.edit')}}</button>&nbsp;
								<button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.cancel')}}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			{{-- END EDIT MODAL --}}

			{{-- DELETE MODAL --}}
			<div class="modal" tabindex="-1" role="dialog" id="delete_service_{{$item->id}}_modal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form action="{{route('admin-delete-service')}}" method="post">
							@csrf
							<div class="modal-body">
								<input type="hidden" class="form-control" name="service_id" value="{{$item->id}}">
								هل تريد حذف هذه الخدمة؟

							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-success">{{trans('lang.delete')}}</button>&nbsp;
								<button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.cancel')}}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			{{-- END DELETE MODAL --}}

			@endforeach

		</tbody>
	</table>
	{{ $services }}

	{{-- ADD MODAL --}}
	<div class="modal" tabindex="-1" role="dialog" id="add_service_modal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="{{route('admin-add-service')}}" method="post" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<div class="form-group">
							<label for="title">{{lang('service_name')}}</label>
							<input type="text" class="form-control" name="name" value="{{old('name')}}">
						</div>
						<div class="form-group">
							<label for="title">{{lang('icon')}}</label>
							<input type="file" name="image" value="">
						</div>

					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success">{{trans('lang.add')}}</button>&nbsp;
						<button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.cancel')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	{{-- END ADD MODAL --}}

</div>

<script>
	var token = '{{Session::token()}}'
	var delete_message_url = '{{route("admin-delete-contactus")}}'
</script>
