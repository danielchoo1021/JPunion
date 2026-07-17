@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
@include('backend.products.promo_item_form')

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('promotion_item_list') }}" class="btn btn-default">
			<i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
		</a>

		<button class="btn btn-primary">
			<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
		</button>

	</div>
</div>

@endsection

@section('js')

<script type="text/javascript">
    $('.submit-form-btn .btn-primary').click( function(e){
      e.preventDefault();
      
      $('#product-form').submit();
    });
    
    $('.parent-row').on('change', '.products', function(){
        $('.loading-gif').show();
        var ele = $(this);

        var num = ele.closest('.row-parent-box').find('.row_num').val();

        var fd = new FormData();
            fd.append('product_id', ele.val());
            fd.append('num', num);

        $.ajax({
            url: '{{ route("getProducts") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                $('.loading-gif').hide();
                if(response['variation_enable'] == '1'){
                    $.ajax({
                        url: '{{ route("getOptionPricing") }}',
                        type: 'post',
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function(response){
                            $('.loading-gif').hide();
                            // ele.closest('.row-parent-box').find('.option-list').html(response);
                            ele.closest('.row-parent-box').find('.pricing-list').html(response);
                        }
                    });
                }else{
                    // ele.closest('.row-parent-box').find('input[name="unit_price[]"]').val(response['price']);
                    ele.closest('.row-parent-box').find('.pricing-list').html('<div class="col-4">\
                            <input type="hidden" class="form-control" name="variation_enable[]" value="0">\
                            <input type="text" class="form-control" name="customer_price_'+ num +'[]" placeholder="{{ isset($data['backendlang']['backendlang']['Customer_Price']) ? $data['backendlang']['backendlang']['Customer_Price'] :'' }}">\
                        </div>\
                        <div class="col-4">\
                            <input type="text" class="form-control" name="customer_special_price_'+ num +'[]" placeholder="{{ isset($data['backendlang']['backendlang']['Customer_Special_Price_2_quantity_or_more']) ? $data['backendlang']['backendlang']['Customer_Special_Price_2_quantity_or_more'] :'' }}">\
                        </div>\
                        @foreach($agent_levels as $agent_level)\
                        <div class="col-4">\
                            <input type="text" class="form-control" name="agent_price_'+num+'[]" placeholder="{{ $agent_level->agent_lvl }}">\
                            <input type="hidden" class="form-control" name="agent_id_'+num+'[]" value="{{ $agent_level->id }}">\
                        </div>\
                        @endforeach');
                }
            }
        });
    });

    $('.parent-row').on('change', '.variation_option', function(e){
        $('.loading-gif').show();
        var ele = $(this);

        var num = ele.closest('.row-parent-box').find('.row_num').val();

        var fd = new FormData();
            fd.append('vid', ele.val());
            fd.append('num', num);

        $.ajax({
            url: '{{ route("getOptionDetail") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                $('.loading-gif').hide();
                // alert(response);
                if(response['id']){
                    ele.closest('.row-parent-box').find('input[name="unit_price[]"]').val(response['variation_price']);
                }else{
                    ele.closest('.row-parent-box').find('.second-option-list').html(response);
                }
            }
        });
    })

    $('.parent-row').on('change', '.second_variation_option', function(e){
        $('.loading-gif').show();
        var ele = $(this);
        var fd = new FormData();
            fd.append('vid', ele.val());

        $.ajax({
            url: '{{ route("getSecondOptionDetail") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                $('.loading-gif').hide();
                ele.closest('.row-parent-box').find('input[name="unit_price[]"]').val(response['variation_price']);
            }
        });
    });

    $('.add-row-btn').click(function(e){
        e.preventDefault();
        var num = $('.total_num').val();
        num = parseFloat(num) + 1;
        $('.total_num').val(num);
        var add_new_row = '<div class="row row-parent-box" style="display: flex; justify-content: center;">\
                              <div class="col-6">\
                                <div class="form-group">\
                                <input type="hidden" class="row_num" name="row_num[]" value="'+num+'">\
                                  <select class="form-control products" name="products[]">\
                                    <option value="">{{ isset($data["backendlang"]["backendlang"]["Select_Product"]) ? $data["backendlang"]["backendlang"]["Select_Product"] :'' }}</option>\
                                    @foreach($products as $product)\
                                    <option value="{{ $product->id }}">\
                                      {{ $product->product_name }}\
                                    </option>\
                                    @endforeach\
                                  </select>\
                                </div>\
                                <div class="form-group option-list">\
                                </div>\
                                <div class="form-group second-option-list">\
                                </div>\
                              </div>\
                              <div class="col-6">\
                                <input type="hidden" class="form-control" name="variation_enable[]">\
                                <input type="text" class="form-control" name="customer_price_'+ num +'[]">\
                              </div>\
                              <div class="col-6">\
                                <input type="text" class="form-control" name="customer_special_price_'+ num +'[]">\
                              </div>\
                              @foreach($agent_levels as $agent_level)\
                              <div class="col-6">\
                                <input type="text" class="form-control" name="agent_price_'+num+'[]">\
                                <input type="hidden" class="form-control" name="agent_id_'+num+'[]" value="{{ $agent_level->id }}">\
                              </div>\
                              @endforeach\
                           </div>';

        $('.parent-row').append(add_new_row);
    });

    $('.parent-row').on('click', '.delete-details-item', function(e){
        e.preventDefault();

        var ele = $(this);
        var id = ele.data('id');

        if(confirm("{{ isset($data['backendlang']['backendlang']['Delete_This_Row']) ? $data['backendlang']['backendlang']['Delete_This_Row'] :'' }}") == true){
            $('.loading-gif').show();
            var fd = new FormData();
                fd.append('id', id);

            $.ajax({
                url: '{{ route("DeletePromoDetail") }}',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response){
                    $('.loading-gif').hide();
                    location.reload();
                }
            });
        }

    });
</script>

@endsection