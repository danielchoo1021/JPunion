@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('promotion.promotions.store') }}" id="promotions-form" enctype="multipart/form-data">
@csrf
@include('backend.promotions.form')
</form>

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('promotion.promotions.index') }}" class="btn btn-outline-danger">
			<i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
		</a>

		<button class="btn btn-outline-primary">
			<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Create']) ? $data['backendlang']['backendlang']['Create'] :'' }}</i>
		</button>

	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	// CKEDITOR.replace( 'description');

    $('.date-timepicker1').click(function(e){
        var ele = $(this);

        console.log('123');

        ele.closest('.input-group').find('.dropdown-menu .list-unstyled .collapse.in').addClass('show');
        ele.closest('.input-group').find('.dropdown-menu .list-unstyled .collapse').not('.in').addClass('show');
        ele.closest('.input-group').find('.dropdown-menu .list-unstyled .picker-switch.accordion-toggle').css('display', 'none');
    });

	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	
    	$('#promotions-form').submit();
    });

    $(function(){
        $('.limit_type').click( function(){
            
            var ele = $(this);
            if(ele.val() == '2'){
                $('.times-limit').html('{{ isset($data['backendlang']['backendlang']['User_Able_To_Use']) ? $data['backendlang']['backendlang']['User_Able_To_Use'] :'' }} <input type="text" name="usage_limit" value="{{ isset($promotion) ? $promotion->usage_limit : old("usage_limit") }}"> {{ isset($data['backendlang']['backendlang']['Time_Per_Day']) ? $data['backendlang']['backendlang']['Time_Per_Day'] :'' }}');
            }else if(ele.val() == '3'){
                $('.times-limit').html('{{ isset($data['backendlang']['backendlang']['User_Able_To_Use_Total']) ? $data['backendlang']['backendlang']['User_Able_To_Use_Total'] :'' }} <input type="text" name="usage_limit" value="{{ isset($promotion) ? $promotion->usage_limit : old("usage_limit") }}"> {{ isset($data['backendlang']['backendlang']['Time_s']) ? $data['backendlang']['backendlang']['Time_s'] :'' }}');
            }else{
                $('input[name="usage_limit"]').hide();
            }
        });
    });
    

    $('.free_shipping').click(function(e){
        $('.discount_amount_area').toggle();
    });
    
    $('.product_voucher').click(function(e){
        $('.non-product-voucher').toggle();
    });

</script>
@endsection