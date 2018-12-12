<div class="main-header col-md-12">

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
				<th scope="col">{{trans('lang.title')}}</th>
				<th scope="col">{{trans('lang.content')}}</th>
				<th scope="col">{{trans('lang.options')}}</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{{$aboutus->title}}</td>
				<td><p style="word-wrap: break-word;">{{$aboutus->content}}</p></td>

				<td>
					<button data-toggle="modal" data-target="#edit_aboutus_modal" class="btn btn-primary">{{trans('lang.edit')}}</button>

				</td>
			</tr>

		</tbody>
	</table>
</div>

{{-- EDIT MODAL --}}
<div class="modal" tabindex="-1" role="dialog" id="edit_aboutus_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{route('admin-edit-aboutus')}}" method="post">
				@csrf
				<div class="modal-body">
					<input type="hidden" class="form-control" name="package_id" value="{{$aboutus->id}}">
					<div class="form-group">
						<label for="title">{{lang('title')}}</label>
						<input type="text" class="form-control" name="title" value="{{$aboutus->title}}">
					</div>
					<div class="form-group">
						<label for="title">{{lang('content')}}</label>
						<textarea rows="5" class="form-control" name="content">{{$aboutus->content}}</textarea>
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
