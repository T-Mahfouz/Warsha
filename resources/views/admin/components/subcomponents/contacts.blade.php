<div class="main-header col-md-12">
	<!-- <h3>باقات التميز</h3> -->


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
				<th scope="col">{{trans('lang.name')}}</th>
				<th scope="col">{{trans('lang.email')}}</th>
				<th scope="col">{{trans('lang.content')}}</th>
				<th scope="col">{{trans('lang.sendat')}}</th>
			</tr>
		</thead>
		<tbody>

			@foreach($contacts as $index=>$item)

			<tr>
				<th scope="row">{{$index+1}}</th>
				<td>{{$item->name}}</td>
				<td>{{$item->email}}</td>
				<td><p style="word-wrap: break-word;">{{$item->message}}</p></td>
				<td>{{$item->created_at->diffForHumans()}}</td>
			</tr>

			@endforeach

		</tbody>
	</table>
	{{ $contacts }}
</div>

<script>
	var token = '{{Session::token()}}'
	var delete_message_url = '{{route("admin-delete-contactus")}}'
</script>
