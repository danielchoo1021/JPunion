@extends('layouts.admin_app')

@section('content')
<div class="page-header">
    <h1>
        {{ isset($data['backendlang']['backendlang']['Setting_New_Customer_Promotions']) ? $data['backendlang']['backendlang']['Setting_New_Customer_Promotions'] :'' }}
        <!-- <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small> -->
    </h1>
</div>
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('setting_new_customer_promotion_save') }}" id="new-customer-promotions-form" enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2">
                    {{ isset($data['backendlang']['backendlang']['Enable_Promotion']) ? $data['backendlang']['backendlang']['Enable_Promotion'] :'' }}
                </div>
                <div class="col-sm-10">
                    <input type="checkbox" name="active" value="1" {{ (isset($setting) && $setting->active == '1') ? 'checked' : ''  }}>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2">
                    {{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }}: <span class="important-text">*</span>
                </div>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="promotion_title" value="{{ isset($setting) ? $setting->promotion_title : old('promotion_title') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }} *">
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2">
                   {{ isset($data['backendlang']['backendlang']['Upload_Image']) ? $data['backendlang']['backendlang']['Upload_Image'] :'' }}: 
                </div>
                <div class="col-sm-10">
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <br>
                    @if(isset($setting->image))
                    <img src="{{ asset($setting->image) }}" style="width: 100px;">
                    @else
                    <img src="{{ asset($data['admin']->ecommerce_logo) }}" style="width: 100px;">
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-2">
                    {{ isset($data['backendlang']['backendlang']['Discount_Code']) ? $data['backendlang']['backendlang']['Discount_Code'] :'' }}: <span class="important-text">*</span>
                </div>
                <div class="col-sm-10">
                    <input type="text" name="discount_code" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Discount_Code']) ? $data['backendlang']['backendlang']['Discount_Code'] :'' }} *" value="{{ isset($setting) ? $setting->discount_code : old('discount_code') }}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-2">
                    {{ isset($data['backendlang']['backendlang']['discount_amount']) ? $data['backendlang']['backendlang']['discount_amount'] :'' }}: <span class="important-text">*</span>
                </div>
                <div class="col-sm-2">
                    <select class="form-control" name="amount_type">
                        @php
                            $selectedValue = (isset($setting)) ? $setting->amount_type : old('amount_type');
                        @endphp
                        <option {{ $selectedValue == 'Percentage' ? 'selected' : '' }} value="Percentage">{{ isset($data['backendlang']['backendlang']['Percenatge']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
                        <option {{ $selectedValue == 'Amount' ? 'selected' : '' }} value="Amount">(RM) {{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
                    </select>
                </div>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="amount" value="{{ isset($setting) ? $setting->amount : old('amount') }}" onkeypress="return isNumberKey(event)">
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-4">
                            {{ isset($data['backendlang']['backendlang']['Duration_Days']) ? $data['backendlang']['backendlang']['Duration_Days'] :'' }}: <span class="important-text">*</span>
                        </div>
                        <div class="col-sm-8">
                                <input type="text" class="form-control" name="duration" 
                                       value="{{ isset($setting) && !empty($setting->duration) ? $setting->duration : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Days']) ? $data['backendlang']['backendlang']['Days'] :'' }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2">
                    {{ isset($data['backendlang']['backendlang']['Usage_Limit_Optional']) ? $data['backendlang']['backendlang']['Usage_Limit_Optional'] :'' }}:
                </div>
                @php
                    $checkedValue = (isset($setting)) ? $setting->limit_type : old('limit_type');
                @endphp
                <div class="col-sm-10">
                    <label>
                        <input name="limit_type" type="radio" value="1" class="ace limit_type" {{ $checkedValue == '1' ? 'checked' : '' }}  checked />
                        <span class="lbl"> {{ isset($data['backendlang']['backendlang']['None_Until_Promotion_End']) ? $data['backendlang']['backendlang']['None_Until_Promotion_End'] :'' }}</span>
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                        <input name="limit_type" type="radio" value="2" class="ace limit_type" {{ $checkedValue == '2' ? 'checked' : '' }} />
                        <span class="lbl"> {{ isset($data['backendlang']['backendlang']['Daily']) ? $data['backendlang']['backendlang']['Daily'] :'' }}</span>
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                        <input name="limit_type" type="radio" value="3" class="ace limit_type" {{ $checkedValue == '3' ? 'checked' : '' }} />
                        <span class="lbl"> {{ isset($data['backendlang']['backendlang']['Per_User']) ? $data['backendlang']['backendlang']['Per_User'] :'' }} </span>
                    </label>
                    <br>
                    <div class="times-limit">
                    </div>
                    
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="row">
                <div class="col-sm-2">
                    {{ isset($data['backendlang']['backendlang']['Products_Optional']) ? $data['backendlang']['backendlang']['Products_Optional'] :'' }}:
                </div>
                <div class="col-sm-10">
                    <select class="selectpicker form-control" data-live-search="true" multiple name="products[]">
                        @php
                            $promotion_products = isset($setting) ? explode(',', $setting->products) : [];
                        @endphp
                        @foreach($products as $product)
                            <option {{in_array($product->id, $promotion_products ?: []) ? "selected": ""}} value="{{ $product->id }}" data-tokens="{{ $product->id }}">
                                {{ $product->product_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>      
    </div>
</div>
</form>

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('promotion.promotions.index') }}" class="btn btn-outline-danger">
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

    var a = '{{ $checkedValue }}';
    
    if(a){
        $('.limit_type').filter(function(){return this.value==a}).click();
    }
	// CKEDITOR.replace( 'description');

	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	
    	$('#new-customer-promotions-form').submit();
    });


    $(function(){
        $('.limit_type').click( function(){
            var ele = $(this);
            if(ele.val() == '2'){
                $('.times-limit').html('{{ isset($data['backendlang']['backendlang']['User_Able_To_Use']) ? $data['backendlang']['backendlang']['User_Able_To_Use'] :'' }} <input type="text" name="usage_limit" value="{{ isset($setting) ? $setting->usage_limit : old("usage_limit") }}"> {{ isset($data['backendlang']['backendlang']['Time_Per_Day']) ? $data['backendlang']['backendlang']['Time_Per_Day'] :'' }}');
            }else if(ele.val() == '3'){
                $('.times-limit').html('{{ isset($data['backendlang']['backendlang']['User_Able_To_Use_Total']) ? $data['backendlang']['backendlang']['User_Able_To_Use_Total'] :'' }} <input type="text" name="usage_limit" value="{{ isset($setting) ? $setting->usage_limit : old("usage_limit") }}"> {{ isset($data['backendlang']['backendlang']['Time_s']) ? $data['backendlang']['backendlang']['Time_s'] :'' }}');
            }else{
                $('input[name="usage_limit"]').hide();
            }
        });

    });
</script>
@endsection