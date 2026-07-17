@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('update_deal') }}" id="promotions-form" enctype="multipart/form-data">
@csrf
@include('backend.promotions.add_on_deal_form')
</form>

<div class="submit-form-btn">
    <div class="form-group wizard-actions" align="right">
        <a href="{{ route('add_on_deal') }}" class="btn btn-default">
            <i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
        </a>

        <button class="btn btn-primary">
            <i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Create']) ? $data['backendlang']['backendlang']['Create'] :'' }}</i>
        </button>

    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="add_main_product">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title">{{ isset($data['backendlang']['backendlang']['Choose_Product']) ? $data['backendlang']['backendlang']['Choose_Product'] :'' }}</h5>

      </div>
      <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>
                            <input type="checkbox" name="check_all" id="check_all">
                        </th>
                        <th>{{ isset($data['backendlang']['backendlang']['product']) ? $data['backendlang']['backendlang']['product'] :'' }}</th>
                   
                    </tr>
                    <tbody class="product_listing">
                      
                    </tbody>
                </table>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</button>
        <button type="button" class="btn btn-primary save_btn">{{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="add_sub_product">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title">{{ isset($data['backendlang']['backendlang']['Choose_Addon_Product']) ? $data['backendlang']['backendlang']['Choose_Addon_Product'] :'' }}</h5>

      </div>
      <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th width="50">
                            <input type="checkbox" name="check_all_sub" id="check_all_sub">
                        </th>
                        <th>{{ isset($data['backendlang']['backendlang']['product']) ? $data['backendlang']['backendlang']['product'] :'' }}</th>
                 
                    </tr>
                    <tbody class="sub_product_listing"></tbody>
                </table>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</button>
        <button type="button" class="btn btn-primary sub_items_save">{{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    // CKEDITOR.replace( 'description');

    $('.submit-form-btn .btn-primary').click( function(e){
        e.preventDefault();
        
        $('#promotions-form').submit();
    });

      $('.add_main_product_btn').click(function(e){
            $('.loading-gif').show();
            e.preventDefault();

            var fd = new FormData();
                fd.append('add_on_id', $('#deal_id').val());

            $.ajax({
                type: 'post',
                url: '{{ route("product_listing") }}',
                data: fd,
                contentType: false,
                processData: false,
                success:function(data)
                {   
                    $('.product_listing').html(data);
                    // $('#add_main_product').modal('show');
                    $('.loading-gif').hide();
                }
            });
       
    });

    $('#update_all').click(function(){
        $('.loading-gif').show();
        var discount = $('#add_on_discount').val();
        var limit = $('#purchase_limits').val(); 
        var deal_id = $('#deal_id').val();   
        $.ajax({
            type: 'post',
            url: '{{route("update_all_sub_item")}}',
            data:{discount:discount,limit:limit,deal_id:deal_id},
            success:function(data){
            if (data.status == 1) {
                     $('.loading-gif').hide();
                    for(i=0; i<=data.batch_count; i++){
                        $('#add_on_discount_'+i+'').val(data.batch_discount);
                        $('#purchase_limits_'+i+'').val(data.batch_purchase_limit);
                        var price = $('#hidden_price_'+i+'').val();
                        var cal = price - (price * (data.batch_discount / 100));
                        $('#add_on_price_'+i+'').val(cal);
                    }
                }
            }
        });
    });

    $('.add_on_product_btn').click(function(e){
        $('.loading-gif').show();
        e.preventDefault();

        var fd = new FormData();
            fd.append('add_on_id', $('#deal_id').val());

        $.ajax({
            type: 'post',
            url: '{{ route("add_on_product_listing") }}',
            data: fd,
            contentType: false,
            processData: false,
            success:function(data)
            {   
                $('.sub_product_listing').html(data);
                // $('#add_sub_product').modal('show');
                $('.loading-gif').hide();
            }
        });
       
    });

    $('#check_all').click(function(){
        $(".product_check").prop("checked", this.checked);
    });


    // $('.variation_option_'+i+'').on('change',function(){
    //     alert(i);
    //     return false;
    // });

    $('.check_all_sub_items').click(function(){
        $('.sub_item_check').prop('checked',this.checked);
    });

    $('#check_all_sub').click(function(){
        $('.check_sub_items').prop('checked',this.checked);
    });

    $(document).on('click','.save_btn',function(){
        var product_arr = [];
        var add_on_id = "{{!empty($add_on_deal->id) ? $add_on_deal->id : '' }}";
        var variation_arr = [];
        var sec_variation_arr = [];
        var items_arr = [];

        $('.product_check:checked').each(function(){
            product_arr.push($(this).data('id'));
        });


        $('.check_product_sec_variation:checked').each(function(){
            sec_variation_arr.push($(this).data('id'));
            variation_arr.push($(this).attr('id'));
          
        });


        $('.product_variations:checked').each(function(){
            items_arr.push($(this).data('id'));
        });

        if(product_arr <= 0){
            alert("{{ isset($data['backendlang']['backendlang']['Please_Select_Products']) ? $data['backendlang']['backendlang']['Please_Select_Products'] :'' }}");
            return false;
        }else{
            if (confirm("{{ isset($data['backendlang']['backendlang']['Are_You_Sure_Want_To_Add_Those_Item']) ? $data['backendlang']['backendlang']['Are_You_Sure_Want_To_Add_Those_Item'] :'' }}") == true) {
                $('.loading-gif').show();
                    var product = product_arr.join(',');
                    var variation = variation_arr.join(',');
                    var sec_variation = sec_variation_arr.join(',');
                    var item_variation = items_arr.join(',');
                $.ajax({
                    type: 'post',
                    url: '{{route("save_add_on_deal_item")}}',
                   data: {product:product,variation:variation,sec_variation:sec_variation,item_variation:item_variation,add_on_id:add_on_id},
                    success:function(data)
                    {
                        if (data.status == 1) {
                            location.reload();

                            // $.ajax({
                            //     type: 'get',
                            //     url: '{{route("display_deal_item")}}',
                            //     data: {add_on_id:data.add_on_id},
                            //     success:function(data){
                            //         $('.loading-gif').hide();
                            //         // $('#add_main_product').modal('hide');
                            //         $('#add_main_product').hide();
                            //         $('.modal-backdrop').hide();
                            //         $('body').removeClass('modal-open');
                            //         $('#display_deal_items').html(data);
                            //     }
                            // });
                            
                        }else if(data.status == 97){
                            toastr.warning(data.msg);
                            $('.loading-gif').hide();
                            location.reload();
                        }
                    }
                });
            }
        }
    });

        $(document).on('click','.second_variation_id',function(){
  
        var ids = $(this).data('row');
        var ele = $('tbody tr td .check_sub_items[data-id="' + ids + '"]');

        if ($(this).is(':checked')) {
            ele.prop('checked','checked',true);
        }else{
            ele.prop('checked',false);
        }
      
    });

    $(document).on('click','.check_product_variation',function(){
  
        var ids = $(this).data('row');
        var ele = $('tbody tr td .check_sub_items[data-id="' + ids + '"]');
     
        if ($(this).is(':checked')) {
            ele.prop('checked','checked',true);
        }else{
           ele.prop('checked',false);
        }
      
    });

    $(document).on('click','.product_variations',function(){
  
        var ids = $(this).data('row');
        var ele = $('tbody tr td .product_check[data-id="' + ids + '"]');
     
        if ($(this).is(':checked')) {
            ele.prop('checked','checked',true);
        }else{
           ele.prop('checked',false);
        }
      
    });


    $(document).on('click','.check_product_sec_variation',function(){
  
        var ids = $(this).data('row');
        var ele = $('tbody tr td .product_check[data-id="' + ids + '"]');

        if ($(this).is(':checked')) {
            ele.prop('checked','checked',true);
        }else{
            ele.prop('checked',false);
        }
      
    });

    $(document).on('click','.sub_items_save',function(){
        var product_arr = [];
        var variation_arr = [];
        var second_variations_arr = [];
        var item_variation = [];

        var add_on_id = "{{!empty($add_on_deal->id) ? $add_on_deal->id : ''}}";

        $('.check_sub_items:checked').each(function(){
            product_arr.push($(this).data('id'));
        });



        $('.second_variation_id:checked').each(function(){
            second_variations_arr.push($(this).data('id'));
            
            variation_arr.push($(this).attr('id'));
        });


        $('.check_product_variation:checked').each(function(){
            item_variation.push($(this).data('id'));
        });


        if(product_arr <= 0){
            alert("{{ isset($data['backendlang']['backendlang']['Please_Select_Products']) ? $data['backendlang']['backendlang']['Please_Select_Products'] :'' }}");
            return false;
        }else{
            if (confirm("{{ isset($data['backendlang']['backendlang']['Are_You_Sure_You_Want_To_Add_Those_Items']) ? $data['backendlang']['backendlang']['Are_You_Sure_You_Want_To_Add_Those_Items'] :'' }}") == true) {
                $('.loading-gif').show();
                var product = product_arr.join(',');
                var variation = variation_arr.join(',');
                var second_variation = second_variations_arr.join(',');
                var item_variation = item_variation.join(',');
 
                $.ajax({
                    type: 'post',
                    url: '{{route("save_sub_item_deal")}}',
                    data: {product:product,variation:variation,second_variation:second_variation,item_variation:item_variation,add_on_id:add_on_id},
                    success:function(data)
                    {

                        if (data.status == 1) {
                            location.reload();

                            // $.ajax({
                            //     type: 'get',
                            //     url: '{{route("display_sub_item")}}',
                            //     data: {add_on_id:data.add_on_id},
                            //     success:function(data){
                            //         $('.loading-gif').hide();
                            //         // $('#add_sub_product').modal('hide');
                            //         $('#add_sub_product').hide();
                            //         $('.modal-backdrop').hide();
                            //         $('body').removeClass('modal-open');
                            //         $('#display_deal_sub_items').html(data);
                            //         $('#batch_settings').show();
                            //     }
                            // });
                            
                        }else if(data.status == 97){
                            toastr.warning(data.msg);
                            $('.loading-gif').hide();
                          
                        }
                        console.log(data);
                    }
                });
            }
        }
    });

    $('#update_selected').click(function(){
        var sub_items_arr = [];
        var discount = $('#add_on_discount').val();
        var limit = $('#purchase_limits').val(); 
        $('.sub_item_check:checked').each(function(){
            sub_items_arr.push($(this).data('id'));
        });

        if(sub_items_arr <= 0){
            alert("{{ isset($data['backendlang']['backendlang']['Please_Select_Records']) ? $data['backendlang']['backendlang']['Please_Select_Records'] :'' }}");
            return false;
        }else{
            if (confirm("{{ isset($data['backendlang']['backendlang']['Are_You_Sure_Want_To_Update_The_Selected_Product']) ? $data['backendlang']['backendlang']['Are_You_Sure_Want_To_Update_The_Selected_Product'] :'' }}") == true) {
                $('.loading-gif').show();
                var product = sub_items_arr.join(',');
                $.ajax({
                    type: 'post',
                    url: '{{route("update_selected_sub_item")}}',
                    data: {product:product,discount:discount,limit:limit},
                    success:function(data)
                    {
                        if (data == 1) {
                            $('.loading-gif').hide();
                            location.reload();
                        }else{
                            toastr.error(data);
                            return false;
                        }   
                        // console.log(data);
                    }
                });
            }
        }
    });

    $(document).on('click','.remove_sub_item',function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var url = '{{route("remove_sub_items",":id")}}';
        url = url.replace(':id',id);
        if (confirm("{{ isset($data['backendlang']['backendlang']['Are_You_Sure_Remove_This_Item']) ? $data['backendlang']['backendlang']['Are_You_Sure_Remove_This_Item'] :'' }}") == true) {
            $.ajax({
                type: 'get',
                url: url,
                success:function(data){
                    if (data == 1) {
                       // $(this).closest("tr").hide();
                       location.reload();
                    }
                }
            });
        }
    });

    $(document).on('click','.remove_item',function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var url = '{{route("remove_items",":id")}}';
        url = url.replace(':id',id);
        if (confirm("{{ isset($data['backendlang']['backendlang']['Are_You_Sure_Remove_This_Item']) ? $data['backendlang']['backendlang']['Are_You_Sure_Remove_This_Item'] :'' }}") == true) {
            $.ajax({
                type: 'get',
                url: url,
                success:function(data){
                    if (data == 1) {
                       // $(this).closest("tr").hide();
                       location.reload();
                    }
                }
            });
        }
    });

    $(document).on('blur','.add_on_discount',function(){
        var num = 0;
        var count = {{$count_sub_items}};
        for(i=0; i<=count; i++){
            var add_on_discount = $('#add_on_discount_'+i+'').val();
            var hidden_price = $('#hidden_price_'+i+'').val();
            var cal = hidden_price - (hidden_price * (add_on_discount / 100));
            $('#add_on_price_'+i+'').val(cal.toFixed(2));
        }
    });

    $(document).on('change','#purchase_limits',function(){
        
    });
    
    $('.date-timepicker1').click(function(e){
        var ele = $(this);

        console.log('123');

        ele.closest('.input-group').find('.dropdown-menu .list-unstyled .collapse.in').addClass('show');
        ele.closest('.input-group').find('.dropdown-menu .list-unstyled .collapse').not('.in').addClass('show');
        ele.closest('.input-group').find('.dropdown-menu .list-unstyled .picker-switch.accordion-toggle').css('display', 'none');
    });

    $(document).on('blur', '.add_on_price', function(){
        var ele = $(this);
        var modify_price = ele.val();
        var original_price = ele.closest('td').find('.hidden_price').val();

        var diff_price = original_price - modify_price;
        var fraction = diff_price / original_price;
        var percentage = fraction * 100;      

        ele.closest('tr').find('.add_on_discount').val(percentage.toFixed(2));
    });

    

    const myDatePicker = flatpickr("#start_date", {
        defaultDate: "{{ $add_on_deal->start_date }}",
        enableTime: true,
    });

    const myDatePicker2 = flatpickr("#end_date", {
        defaultDate: "{{ $add_on_deal->end_date }}",
        enableTime: true,
    });
</script>
@endsection