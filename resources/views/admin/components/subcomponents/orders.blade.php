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
				<th scope="col">#</th>
				<th scope="col">{{trans('lang.customername')}}</th>
				<th scope="col">{{trans('lang.cartype')}}</th>
				<th scope="col">{{trans('lang.carmodel')}}</th>
				<th scope="col">{{trans('lang.work_address')}}</th>
				<th scope="col">{{trans('lang.offers')}}</th>
				<th scope="col">{{trans('lang.options')}}</th>
			</tr>
		</thead>
		<tbody>

			@foreach($orders as $index=>$item)

			<tr>
				<th scope="row">{{($page-1)*$items + ($index+1)}}</th>
				<td>{{$item->user->name}}</td>
				<td>{{$item->car_type}}</td>
				<td>{{$item->car_model}}</td>
				<td>{{$item->address}}</td>
				<td>{{count($item->offers)}}</td>
				<td>
						<button data-toggle="modal" data-target="#view_offers_{{$item->id}}_modal" class="btn btn-primary">{{trans('lang.offers')}}</button>
						<button data-toggle="modal" data-target="#view_order_{{$item->id}}_modal" class="btn btn-info">{{trans('lang.details')}}</button>
						<button data-toggle="modal" data-target="#delete_order_{{$item->id}}_modal" class="btn btn-danger">{{trans('lang.delete')}}</button>
				</td>
			</tr>

			{{-- OFFERS MODAL --}}
			<div class="modal" tabindex="-1" role="dialog" id="view_offers_{{$item->id}}_modal">
				<div class="modal-dialog" role="document" style="width: 70%;max-width: 70%;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
							<div class="modal-body">
									@foreach($item->offers as $offer)
									<div id="offer_{{$offer->id}}" style="border-bottom: 2px solid gray;background: #fafafa;margin:5px;">
											<div class="form-group">
												<label for="">
														<img style="height:50px;width:50px;border-radius:50%;" src="{{$resource.'images/users/'.$offer->workshop->image}}" alt="">
														{{$offer->workshop->name}}
														<br>
														<p style="color:#ccc;margin-top: 20px;font-size: 12px;">{{$offer->created_at->diffForHumans()}}</p>
												</label>
												<label onclick="removeOffer(this)" id="{{$offer->id}}" for="" style="float: left;margin-left: 10px;margin-top: 5px;">
														<i class="fa fa-close" style="color:#dc3545;cursor:pointer;"></i>
												</label>
											</div>

											<div class="form-group">
												<label for="">{{trans('lang.content')}}</label>
												<textarea readonly rows="8" class="form-control">{{$offer->content}}</textarea>
											</div>

											<div class="form-group">
												<label for="title">{{lang('phone')}}</label>
												<input readonly class="form-control" value="{{$offer->workshop->phone}}">
											</div>
											<div class="form-group">
												<label for="title">{{lang('whatsapp')}}</label>
												<input readonly class="form-control" value="{{$offer->workshop->whatsapp}}">
											</div>
											<div class="form-group">
												<label for="title">{{lang('email')}}</label>
												<input readonly class="form-control" value="{{$offer->workshop->email}}">
											</div>
									</div>
									@endforeach
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-success" data-dismiss="modal">{{trans('lang.cancel')}}</button>
							</div>
					</div>
				</div>
			</div>
			{{-- END OFFERS MODAL --}}

			{{-- VIEW MODAL --}}
			<div class="modal" tabindex="-1" role="dialog" id="view_order_{{$item->id}}_modal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
							<div class="modal-body">
									<div class="form-group">
										<label for="">{{trans('lang.work_location')}}</label>
										<img style="height:75px;width:75px;" src="{{$item->qr_location}}" alt="" />
									</div>
									<div class="form-group">
										<label for="">{{trans('lang.order_description')}}</label>
										<textarea readonly rows="8" class="form-control">{{$item->description}}</textarea>
									</div>
									<hr>
									بيانات الإتصال بالعميل:
									<hr>
									<div class="form-group">
										<label for="title">{{lang('phone')}}</label>
										<input readonly class="form-control" value="{{$item->user->phone}}">
									</div>
									<div class="form-group">
										<label for="title">{{lang('whatsapp')}}</label>
										<input readonly class="form-control" value="{{$item->user->whatsapp}}">
									</div>
									<div class="form-group">
										<label for="title">{{lang('email')}}</label>
										<input readonly class="form-control" value="{{$item->user->email}}">
									</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-success" data-dismiss="modal">{{trans('lang.cancel')}}</button>
							</div>
					</div>
				</div>
			</div>
			{{-- END VIEW MODAL --}}

			{{-- DELETE MODAL --}}
			<div class="modal" tabindex="-1" role="dialog" id="delete_order_{{$item->id}}_modal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form action="{{route('admin-delete-order')}}" method="post">
							@csrf
							<div class="modal-body">
								<input type="hidden" class="form-control" name="order_id" value="{{$item->id}}">
								هل تريد حذف هذا الطلب؟

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
	{{ $orders }}


</div>

<script>
	var token = '{{Session::token()}}'
	var delete_offer_url = '{{route("admin-delete-offer")}}'

	function removeOffer(e) {
			id = e.id;
			$('#offer_'+id).css('display','none');
			$.ajax({
					method: 'POST',
					url: delete_offer_url,
					data: {offer_id:id,_token:token}
			}).done( response => {
					//console.log(response);
			}).fail( error => {
					//console.error(error);
			});
	}
</script>
