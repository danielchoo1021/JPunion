@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
@include('backend.products.packages_form')

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('packages_list') }}" class="btn btn-outline-danger">
			<i class="bi bi-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
		</a>

		<button class="btn btn-outline-primary">
			<i class="bi bi-check">{{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
		</button>

	</div>
</div>

@endsection

@section('js')

<script type="text/javascript">
    var url = '{{ route("LoadImage", ":id") }}';
    url = url.replace(':id', '{{ $product->id }}');
    
    $.ajax({
        url: url,
        type: 'get',
        success: function(response){
            $('.product-image-list .row').html(response);
            
        },
    });

    jQuery(function($){
            
        try {
            
            var myDropzone = Dropzone.options.dropzone =
            {

                maxFilesize: 120000,
                renameFile: function(file) {
                    var dt = new Date();
                    var time = dt.getTime();
                   return time+file.name;
                },
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                timeout: 5000,
                dictRemoveFile: 'Remove',
                maxFiles: 100,
                
                dictDefaultMessage :
                // '<span class="bigger-150 bolder"><i class="ace-icon bi bi-caret-right red"></i> {{ isset($data["Blang"]["Blang"]["dnd_files"]) ? $data["Blang"]["Blang"]["dnd_files"] : "拖放文件" }} </span> {{ isset($data["Blang"]["Blang"]["uploads"]) ? $data["Blang"]["Blang"]["uploads"] : "上传" }} \
                // <span class="smaller-80 grey">{{ isset($data["Blang"]["Blang"]["or_click"]) ? $data["Blang"]["Blang"]["or_click"] : "(或 点击)" }}</span> <br /> \
                '<span class="bigger-150 bolder"><i class="ace-icon fa fa-caret-right red"></i> {{ isset($data["backendlang"]["backendlang"]["dnd_files"]) ? $data["backendlang"]["backendlang"]["dnd_files"] :'' }} </span> {{ isset($data["backendlang"]["backendlang"]["To_Upload"]) ? $data["backendlang"]["backendlang"]["To_Upload"] :'' }} \
                <span class="smaller-80 grey"> {{ isset($data["backendlang"]["backendlang"]["(or Click)"]) ? $data["backendlang"]["backendlang"]["(or Click)"] :'' }} </span> <br /> \
                <i class="upload-icon ace-icon fa fa-cloud-upload blue fa-3x"></i>',
                
                thumbnail: function(file, dataUrl) {
                  if (file.previewElement) {
                    $(file.previewElement).removeClass("dz-file-preview");
                    var images = $(file.previewElement).find("[data-dz-thumbnail]").each(function() {
                        var thumbnailElement = this;
                        thumbnailElement.alt = file.name;
                        thumbnailElement.src = dataUrl;
                    });
                    setTimeout(function() { $(file.previewElement).addClass("dz-image-preview"); }, 1);

                  }
                },
                success: function(file, response) 
                {
                    $('.product-image-list .row').html(response);
                },
            };
        
          //simulating upload progress
          var minSteps = 6,
              maxSteps = 60,
              timeBetweenSteps = 100,
              bytesPerStep = 100000;
        
          
        
           
           //remove dropzone instance when leaving this page in ajax mode
           $(document).one('ajaxloadstart.page', function(e) {
                try 
                {
                    myDropzone.destroy();
                } catch(e) {}
           });
        
        } catch(e) {
          alert("{{ isset($data['backendlang']['backendlang']['Dropzone.js does not support older browsers!']) ? $data['backendlang']['backendlang']['Dropzone.js does not support older browsers!'] :'' }}");
        }
        
    });

    $('.product-image-list').on('click', '.product-image-thumbnail .delete-image', function(e){
        e.preventDefault();
        var delete_btn = $(this);
        if(confirm("{{ isset($data['backendlang']['backendlang']['Delete_This_Image']) ? $data['backendlang']['backendlang']['Delete_This_Image'] :'' }}") == true){
            var url = '{{ route("DeleteImage", ":id") }}';
            url = url.replace(':id', $(this).data('id'));
            $.ajax({
                url: url,
                type: 'get',
                success: function(response){
                    delete_btn.closest('.product-image-thumbnail').hide();
                },
            });
        }else{
            return false;
        }
    });

    $('.submit-form-btn .btn-outline-primary').click( function(e){
        e.preventDefault();
        var g;
        var v;
            
        
        $( ".products" ).each(function() {
            if($(this).val()){
                g = 1;
            }
        }); 
            
        
        $( ".vouchers" ).each(function() {
            if($(this).val()){
                v = 1;
            }
        }); 

        if(v == 1 || g == 1){
            $('#product-form').submit();    
        }else{
            alert("{{ isset($data['backendlang']['backendlang']['At least 1 Item / 1 Voucher']) ? $data['backendlang']['backendlang']['At least 1 Item / 1 Voucher'] :'' }}");
        }
        return false;
        
    });

    var descriptionUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token(), "p_id"=> ":p_id", "type" => "1" ]) }}';

    var description = CKEDITOR.instances["description"];
    descriptionUrl = descriptionUrl.replace(':p_id', '{{ $product->id }}');

    if(!description){
        CKEDITOR.replace( 'description',{
          filebrowserUploadUrl: descriptionUrl,
          filebrowserUploadMethod: 'form'
        });

        CKEDITOR.replace( 'free_gift_description',{
          filebrowserUploadUrl: descriptionUrl,
          filebrowserUploadMethod: 'form'
        });
    }

    

    $('.add-shipping-btn').click(function(e){
        e.preventDefault();
        var num = $('.parent-box').find('.products').length;

        var item = '<div class="form-group">\
                    <input type="hidden" name="pid[]" value="">\
                    <div class="row row-parent-box">\
                        <div class="col-md-2">\
                            <div class="form-group">\
                                <select class="form-control products" name="products[]" data-filter="'+num+'">\
                                    <option value="">{{ isset($data["backendlang"]["backendlang"]["Select_Product"]) ? $data["backendlang"]["backendlang"]["Select_Product"] :'' }}</option>\
                                    @foreach($products as $product_s)\
                                    @if($product_s->packages != 1)\
                                    <option value="{{ $product_s->id }}">{{ $product_s->product_name }}</option>\
                                    @endif\
                                    @endforeach\
                                </select>\
                            </div>\
                            <div class="form-group option-list">\
                            </div>\
                            <div class="form-group second-option-list">\
                            </div>\
                        </div>\
                        <div class="col-md-2">\
                            <input type="input" name="qty[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}" onkeypress="return isNumberKey(event)">\
                        </div>\
                        <div class="col-md-2">\
                            <input type="input" name="unit_price[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Total_Cost']) ? $data['backendlang']['backendlang']['Total_Cost'] :'' }}" onkeypress="return isNumberKey(event)">\
                        </div>\
                        <div class="col-md-2">\
                            <a href="#" class="del-packages important-text">\
                                <i class="bi bi-trash fa-2x"></i>\
                            </a>\
                        </div>\
                    </div>\
                </div>';

        $('.parent-box').append(item);
    });

    $('.add-voucher-btn').click(function(e){
        e.preventDefault();
        var num = $('.voucher-parent-box').find('.vouchers').length;

        var item = '<div class="form-group">\
                    <input type="hidden" name="vpid[]" value="">\
                    <div class="row voucher-row-parent-box">\
                        <div class="col-md-2">\
                            <div class="form-group">\
                                <select class="form-control vouchers" name="vouchers[]" data-filter="'+num+'">\
                                    <option value="">{{ isset($data["backendlang"]["backendlang"]["Select_Vouchers"]) ? $data["backendlang"]["backendlang"]["Select_Vouchers"] :'' }}</option>\
                                    @foreach($vouchers as $voucher)\
                                    <option value="{{ $voucher->id }}">\
                                        {{ $voucher->promotion_title }}\
                                    </option>\
                                    @endforeach\
                                </select>\
                            </div>\
                        </div>\
                        <div class="col-md-2">\
                            <input type="input" name="voucher_qty[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}" onkeypress="return isNumberKey(event)">\
                        </div>\
                        <div class="col-md-2">\
                            <a href="#" class="del-packages important-text">\
                                <i class="bi bi-trash fa-2x"></i>\
                            </a>\
                        </div>\
                    </div>\
                </div>';

        $('.voucher-parent-box').append(item);
    });

    $('.parent-box').on('change', '.products', function(){
        $('.loading-gif').show();
        var ele = $(this);
        var fd = new FormData();
            fd.append('product_id', ele.val());
            fd.append('num', ele.data('filter'));

        $.ajax({
            url: '{{ route("getProducts") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                $('.loading-gif').hide();
                ele.closest('.row-parent-box').find('.second-option-list').hide();
                if(response['variation_enable'] == '1'){
                    
                    $.ajax({
                        url: '{{ route("getOption") }}',
                        type: 'post',
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function(response){
                            $('.loading-gif').hide();
                            ele.closest('.row-parent-box').find('.option-list').show();
                            ele.closest('.row-parent-box').find('.option-list').html(response);
                            ele.closest('.row-parent-box').find('input[name="unit_price[]"]').val('');
                        }
                    });
                }else{
                    ele.closest('.row-parent-box').find('.option-list').hide();
                    ele.closest('.row-parent-box').find('input[name="unit_price[]"]').val(response['price']);
                }
            }
        });
    });

    $('.parent-box').on('change', '.variation_option', function(e){
        $('.loading-gif').show();
        var ele = $(this);
        var fd = new FormData();
            fd.append('vid', ele.val());
            fd.append('num', ele.data('filter'));

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
                    ele.closest('.row-parent-box').find('.second-option-list').show();
                    ele.closest('.row-parent-box').find('.second-option-list').html(response);
                    ele.closest('.row-parent-box').find('input[name="unit_price[]"]').val('');
                }
            }
        });
    })

    $('.parent-box').on('change', '.second_variation_option', function(e){
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
    })


    $('.event_date').daterangepicker({
        'applyClass' : 'btn-sm btn-success',
        'cancelClass' : 'btn-sm btn-outline-danger',
        locale: {
            applyLabel: "{{ isset($data['backendlang']['backendlang']['Apply']) ? $data['backendlang']['backendlang']['Apply'] :'' }}",
            cancelLabel: "{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}",
        }
    });

    $('input[name="event_time_available"]').click( function(){
        if($(this).is(":checked")){
            $('.event_area').show();
        }else{
            $('.event_area').hide();
        }
    });

    $(document).ready( function(){
        if($('input[name="event_time_available"]').is(":checked")){
            $('.event_area').show();
        }else{
            $('.event_area').hide();
        }
    });

    $('.category_id').change(function(){
        $('.loading-gif').show();
        var ele = $(this);

        var fd = new FormData();
        fd.append('cid', ele.val());
        fd.append('pid', '{{ $product->id }}');

        $.ajax({
           url: '{{ route("getItemCode") }}',
           type: 'post',
           data: fd,
           contentType: false,
           processData: false,
           success: function(response){
                $('.loading-gif').hide();
                if(response != 'null'){
                    $('.hidden_item_code').val(response);
                    $('.item_code').html("{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :'' }}: "+response);
                }else{
                    $('.hidden_item_code').val('');
                    $('.item_code').html(' ');
                }
           },
        });

        $.ajax({
             url: '{{ route("GetSubCategory") }}',
             type: 'post',
             data: fd,
             contentType: false,
             processData: false,
             success: function(response){
                  $('.loading-gif').hide();
                  $('.sub_category').html(response);

                  $('.sub_category_id').change( function(e){

                      var fd = new FormData();
                          fd.append('cid', $('.category_id').val());
                          fd.append('scid', $(this).val());

                      $.ajax({
                         url: '{{ route("getSubItemCode") }}',
                         type: 'post',
                         data: fd,
                         contentType: false,
                         processData: false,
                         success: function(response){
                              $('.loading-gif').hide();
                              $('.item_code').html(' ');
                              if(response != 'null'){
                                  $('.hidden_item_code').val(response);
                                  $('.sub_item_code').html("{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :'' }}: "+response);
                              }else{
                                  $('.sub_item_code').html(' ');
                              }
                         },
                      });
                  });
             },
          });
    });

$('.parent-box').on('click', '.del-packages', function(e){
    e.preventDefault();
    $('.loading-gif').show();

    var ele = $(this);

    var id = ele.data('id');

    var fd = new FormData();
        fd.append('id', id);

    if(confirm("{{ isset($data['backendlang']['backendlang']['Delete?']) ? $data['backendlang']['backendlang']['Delete?'] :'' }}") == true){
        if(id){
            $.ajax({
                url: '{{ route("delete_packages") }}',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response){
                    $('.loading-gif').hide();
                    if(response == "ok"){
                        ele.closest('.row-parent-box').remove();
                    }else{
                        toastr.error(response)
                    }
                },
            });
        }else{
            ele.closest('.row-parent-box').remove();
        }
    }else{
        $('.loading-gif').hide();
    }
});

$('.voucher-parent-box').on('click', '.del-packages', function(e){
    e.preventDefault();
    $('.loading-gif').show();

    var ele = $(this);

    var id = ele.data('id');

    var fd = new FormData();
        fd.append('id', id);

    if(confirm("{{ isset($data['backendlang']['backendlang']['Delete?']) ? $data['backendlang']['backendlang']['Delete?'] :'' }}") == true){
        if(id){
            $.ajax({
                url: '{{ route("delete_packages") }}',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response){
                    $('.loading-gif').hide();
                    if(response == "ok"){
                        ele.closest('.row-parent-box').remove();
                    }else{
                        toastr.error(response)
                    }
                },
            });
        }else{
            ele.closest('.row-parent-box').remove();
        }
    }else{
        $('.loading-gif').hide();
    }
});

$(function() { 
    $("#imageListId").sortable({ 
        update: function(event, ui) { 
                getIdsOfImages();
            } //end update 
    }); 
}); 

function getIdsOfImages() { 
    $('.loading-gif').show();
    var values = []; 
    var  a = 0;
    $('.product-image-thumbnail').each(function(index) { 
        a++;
        var mid = $(this).data('id');

        var fd = new FormData();
            fd.append('mid', mid);
            fd.append('number', a);

        $.ajax({
           url: '{{ route("SortImage") }}',
           type: 'post',
           data: fd,
           contentType: false,
           processData: false,
           success: function(response){
                $('.loading-gif').hide();
                toastr.success("{{ isset($data['backendlang']['backendlang']['Updated']) ? $data['backendlang']['backendlang']['Updated'] :'' }}");
           },
        });
    }); 
    
}
</script>

@endsection