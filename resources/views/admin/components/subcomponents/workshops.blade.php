<div class="main-header col-md-12">

  <form action="" method="get">

    <input class="form-control" style="width: auto;display: inline;" type="text" placeholder="{{trans('lang.search_phone_name_email')}}" name="key" value="{{Request::get('phone')}}">
    <input class="btn btn-success" type="submit" value="{{trans('lang.search')}}">
  </form>

  <button style="bottom: 70px;" class="add-button btn btn-primary" data-toggle='modal' data-target='#add_user_modal'>{{lang('add_workshop')}}</button>



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
        <th scope="col">{{lang('name')}}</th>
        <th scope="col">{{lang('email')}}</th>
        <th scope="col">{{lang('phone')}}</th>
        <th scope="col">{{lang('location')}}</th>
        <!-- <th scope="col">{{lang('status')}}</th> -->
        <th scope="col">{{lang('options')}}</th>
      </tr>
    </thead>
    <tbody>
     @foreach($workshops as $index=>$item)
     <tr>
      <th scope="row">{{$index+1}}</th>
      <td><img class="profile-pic-small" src="{{$resource.'images/users/'.$item->image}}"/>{{$item->firstname.' '.$item->lastname}}</td>
      <td>{{$item->email}}</td>
      <td>{{$item->phone}}</td>
      <td><img src="{{$item->qr_location}}" alt=""> </td>
      <!-- <td><span class="badge badge-{{$item->status?'primary':'dark'}}">{{$item->status?trans('lang.active'):trans('lang.notactive')}}</span></td> -->

      <td>
        <button data-toggle='modal' data-target='#edit_user_{{$item->id}}_modal' class="btn btn-primary">{{trans('lang.edit')}}</button>
        <!-- <button onclick="changeUserStatus({{$item}})" class="btn btn-primary bg-green">
          @if(!$item->status)
          {{trans('lang.changetoactive')}}
          @else
          {{trans('lang.changetonotactive')}}
          @endif
        </button> -->

        <button data-toggle='modal' data-target='#delete_user_{{$item->id}}_modal' class="btn btn-danger">{{trans('lang.delete')}}</button>
      </td>
    </tr>

    {{-- EDIT MODAL --}}
    <div class="modal" tabindex="-1" role="dialog" id="edit_user_{{$item->id}}_modal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{route('admin-edit-workshop')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
              <div class="form-froup">
                <label for="name">{{lang('name')}}</label>
                <input type="hidden" class="form-control" name="user_id" value="{{$item->id}}">
                <input type="text" class="form-control" name="name" value="{{$item->name}}">
              </div>

              <div class="form-froup">
                <label for="email">{{lang('email')}}</label>
                <input type="email" class="form-control" name="email" value="{{$item->email}}">
              </div>
              <div class="form-froup">
                <label for="phone">{{lang('phone')}}</label>
                <input type="text" class="form-control" name="phone" value="{{$item->phone}}">
              </div>
              <div class="form-froup">
                <label for="phone">واتس آب</label>
                <input type="text" class="form-control" name="whatsapp" value="{{$item->whatsapp}}">
              </div>
              <div class="form-froup">
                <label for="phone">{{lang('location')}}</label>
                <input type="text" class="form-control" name="location" value="{{$item->lat.','.$item->lon}}">
              </div>
              <div class="form-froup">
                <label for="username">{{trans('lang.username')}}</label>
                <input type="text" class="form-control" name="username" value="{{$item->username}}">
              </div>
              <div class="form-froup">
                <label for="password">{{lang('password')}}</label>
                <input type="password" class="form-control" name="password">
              </div>
              <div class="form-froup">
                <label for="password_confirmation">{{lang('password_confirmation')}}</label>
                <input type="password" class="form-control" name="password_confirmation">
              </div>

              <div class="form-froup">
                <label for="file">{{lang('image')}}</label>
                <input type="file" class="form-control" name="image">
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
    <div class="modal fade" tabindex="-1" role="dialog" id="delete_user_{{$item->id}}_modal">
    	<div class="modal-dialog" role="document">
    		<div class="modal-content">
    			<div class="modal-header2">

    			</div>

    			<form action="{{route('admin-delete-workshop')}}" method="post">
    				@csrf
    				<div class="modal-body" style="direction: ltr;text-align: left;">
    					هل تريد حذف هذه الورشة؟

    						<input type="hidden" name="user_id" value="{{$item->id}}">
    				</div>
    				<div class="modal-footer">
    					<button type="button" class="btn btn-default" data-dismiss="modal">لا</button>
              &nbsp;
    					<button type="submit" class="btn btn-primary" id="">نعم</button>
    				</div>
    			</form>
    		</div><!-- /.modal-content -->
    	</div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    {{-- END DELETE MODAL --}}

    @endforeach
  </tbody>
</table>
{{$workshops->links()}}
</div>


{{-- ADD MODAL --}}
<div class="modal" tabindex="-1" role="dialog" id="add_user_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('admin-add-workshop')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-froup">
            <label for="username">{{trans('lang.name')}}</label>
            <input type="text" class="form-control" name="name" value="{{old('name')}}">
          </div>
          <div class="form-froup">
            <label for="email">{{trans('lang.email')}}</label>
            <input type="email" class="form-control" name="email" value="{{old('email')}}">
          </div>
          <div class="form-froup">
            <label for="phone">{{trans('lang.phone')}}</label>
            <input type="text" class="form-control" name="phone" value="{{old('phone')}}">
          </div>
          <div class="form-froup">
            <label for="phone">واتس آب</label>
            <input type="text" class="form-control" name="whatsapp" value="{{old('whatsapp')}}">
          </div>
          <div class="form-froup">
            <label for="username">{{trans('lang.location')}}</label>
            <input type="text" class="form-control" name="location" placeholder="24.7243226,46.6341751" value="{{old('location')}}">
          </div>
          <div class="form-froup">
            <label for="username">{{trans('lang.username')}}</label>
            <input type="text" class="form-control" name="username" value="{{old('username')}}">
          </div>
          <div class="form-froup">
            <label for="password">{{trans('lang.password')}}</label>
            <input type="password" class="form-control" name="password">
          </div>
          <div class="form-froup">
            <label for="password_confirmation">{{trans('lang.password_confirmation')}}</label>
            <input type="password" class="form-control" name="password_confirmation">
          </div>

          <div class="form-froup">
            <label for="file">{{trans('lang.image')}}</label>
            <input type="file" class="form-control" name="image">
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



@include('admin.modals.users.user-status')

<script>

  function deleteUserPost(id){
    $.ajax({
      method: 'POST',
      url: '{{route("admin-delete-user-post")}}',
      data:{_token:'{{Session::token()}}',post_id:id}
    }).then(function(response){
      $('#user_post_'+id+'_container').css('display','none');
      alert('تم الحذف بنجاح')
    }).catch(function(error){
      alert('حدث خطأ ما يرجى المحاولة مرة أخرى')
      consol(error.response)
    });

  }
</script>
