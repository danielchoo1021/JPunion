@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('merchant.merchants.update', $merchant->id) }}" id="agent-form" enctype="multipart/form-data">
@csrf
@method('PUT')
@include('backend.merchants.form')
</form>

<div
    class="modal fade text-left"
    id="primary"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel160"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
        role="document"
    >
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5
                    class="modal-title white"
                    id="myModalLabel160"
                >
                   <th>{{ isset($data['backendlang']['backendlang']['Change_New_Password']) ? $data['backendlang']['backendlang']['Change_New_Password'] :'' }}</th>
                </h5>
                <button
                    type="button"
                    class="close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <i data-feather="x"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('saveNewPassword', [$merchant->id]) }}" id="change_password-form">
                @csrf
                <div class="modal-body">
                      <div class="form-group">
                          <label><th>{{ isset($data['backendlang']['backendlang']['New_Password']) ? $data['backendlang']['backendlang']['New_Password'] :'' }}</th></label>
                          <input type="text" name="new_password" class="form-control">
                      </div>
                      <div class="form-group">
                          <label><th>{{ isset($data['backendlang']['backendlang']['Confirm_Password']) ? $data['backendlang']['backendlang']['Confirm_Password'] :'' }}</th></label>
                          <input type="text" name="password_confirmation" class="form-control">
                      </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-light-secondary"
                        data-bs-dismiss="modal"
                    >
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block"
                        ><th>{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</th></span
                        >
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary save-password ms-1"
                        data-bs-dismiss="modal"
                    >
                        <i
                            class="bx bx-check d-block d-sm-none"
                        ></i>
                        <span class="d-none d-sm-block"
                            ><th>{{ isset($data['backendlang']['backendlang']['Save_Password']) ? $data['backendlang']['backendlang']['Save_Password'] :'' }}</th></span
                        >
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('merchant.merchants.index') }}" class="btn btn-outline-danger">
			<i class="bi bi-ban"> <th>{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</th></i>
		</a>

        @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-edit']))
		<button class="btn btn-outline-primary">
			<i class="bi bi-check"> <th>{{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</th></i>
		</button>
        @endif

	</div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    $('.submit-form-btn .btn-outline-primary').click( function(e){
    	   e.preventDefault();
    	   $('.loading-gif').show();

           var email = $('.email').val();

            if(IsEmail(email)==false){
                toastr.error("{{ isset($data['backendlang']['backendlang']['Please_Enter_Email_Format']) ? $data['backendlang']['backendlang']['Please_Enter_Email_Format'] :'' }}");
                return false;
            }
    	   $('#agent-form').submit();
    });

    $('.save-password').click( function(e){
        e.preventDefault();

        var new_password = $('input[name="new_password"]').val();
        var con_password = $('input[name="password_confirmation"]').val();

        if(new_password == con_password){
            
            $('#change_password-form').submit();
        }else{
            alert("{{ isset($data['backendlang']['backendlang']['Password_Not_Match']) ? $data['backendlang']['backendlang']['Password_Not_Match'] :'' }}");
            return false;
        }
    });

    var descriptionUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token(), "p_id"=> ":p_id", "type" => "1" ]) }}';
        descriptionUrl = descriptionUrl.replace(':p_id', '1');

    var description = CKEDITOR.instances["description"];

    if(!description){
        CKEDITOR.replace( 'description',{
            filebrowserUploadUrl: descriptionUrl,
            filebrowserUploadMethod: 'form'
        });
    }

    $('.active_period').keyup(function(e){

        var ele = $(this);

        var fd = new FormData();
            fd.append('merchant', '{{ isset($merchant) ? $merchant->code : "" }}');
            fd.append('period', ele.val());

        $.ajax({
            url: '{{ route("get_merchant_expired_date") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                $('.loading-gif').hide();
                // toastr.success('Reset password successful');
                $('.expired_date').html(response);
                $('.datepicker').val(response);
                console.log(response)
            },
        });
    });

    $('.datepicker').change(function(e){


        var ele = $(this);
        var selectedDate = new Date(ele.val());

        var created_at = "{{ $merchant->created_at }}";
        var today = new Date(created_at);
        // if()
        var timeDiff = selectedDate.getTime() - today.getTime();
        var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

        // console.log(daysDiff);

        var fd = new FormData();
            fd.append('merchant', '{{ isset($merchant) ? $merchant->code : "" }}');
            fd.append('period', daysDiff);

        $.ajax({
            url: '{{ route("get_merchant_expired_date") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                $('.loading-gif').hide();
                // toastr.success('Reset password successful');
                $('.expired_date').html(response);
                // $('.datepicker').html(response);
                $('.active_period').val(daysDiff)
                // console.log(response)
            },
        });
    });
    
    $('.active_period').trigger('keyup');
</script>

@endsection