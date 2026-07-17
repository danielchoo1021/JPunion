@extends('layouts.admin_app')

@section('content')
<div class="page-header">
    <h1>
        {{ isset($data['backendlang']['backendlang']['Staff_Details']) ? $data['backendlang']['backendlang']['Staff_Details'] :'' }}
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            {{ $staff->f_name }} {{ $staff->l_name }}
        </small>
    </h1>
</div>
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('staff.staffs.update', $staff->id) }}" id="agent-form" enctype="multipart/form-data">
@csrf
@method('PUT')
@include('backend.staffs.form')
</form>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <h3>&nbsp;&nbsp;&nbsp;<i class="fa fa-key"></i> {{ isset($data['backendlang']['backendlang']['Change_New_Password']) ? $data['backendlang']['backendlang']['Change_New_Password'] :'' }}</h3>
      <hr>
      <form method="POST" action="{{ route('saveNewStaffPassword', [$staff->id]) }}" id="change_password-form">
        @csrf
        <div class="modal-body">
              <div class="form-group">
                  <label>{{ isset($data['backendlang']['backendlang']['New_Password']) ? $data['backendlang']['backendlang']['New_Password'] :'' }}</label>
                  <input type="text" name="new_password" class="form-control">
              </div>
              <div class="form-group">
                  <label>{{ isset($data['backendlang']['backendlang']['Confirm_Password']) ? $data['backendlang']['backendlang']['Confirm_Password'] :'' }}</label>
                  <input type="text" name="password_confirmation" class="form-control">
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
          <button type="button" class="btn btn-outline-primary save-password">{{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('staff.staffs.index') }}" class="btn btn-outline-danger">
			<i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
		</a>

		<button class="btn btn-outline-primary">
			<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
		</button>

	</div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    $('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	$('.loading-gif').show();
    	$('#agent-form').submit();
    });

    $('.save-password').click( function(e){
        e.preventDefault();

        var new_password = $('input[name="new_password"]').val();
        var con_password = $('input[name="password_confirmation"]').val();

        if(new_password == con_password){
            
            $('#change_password-form').submit();
        }else{
            alert('{{ isset($data['backendlang']['backendlang']['Password_Not_Match']) ? $data['backendlang']['backendlang']['Password_Not_Match'] :'' }}');
            return false;
        }
    });
</script>

@endsection