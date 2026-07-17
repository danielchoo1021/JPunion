@extends('layouts.admin_app')

@section('css')
<style type="text/css">

      
    /* imagelistId styling */
    #imageListId { 
        margin: 0; 
        padding: 0; 
        list-style-type: none; 
    } 
       
    #imageListId div { 
        margin: 0 4px 4px 4px; 
        padding: 0.4em; 
        display: inline-block; 
    } 
      
    /* Output order styling */
    #outputvalues { 
        margin: 0 2px 2px 2px; 
        padding: 0.4em; 
        padding-left: 1.5em; 
        width: 250px; 
        border: 2px solid dark-green; 
        background: gray; 
    } 
       
    .listitemClass { 
        border: 1px solid #006400; 
        width: 350px; 
    } 
       
    .height { 
        height: 10px; 
    } 

    .table-bordered input {
        width: fit-content;
    }
</style>
@endsection
@section('content')

@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
@include('backend.products.form')

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('product.products.index') }}" class="btn btn-outline-danger">
			<i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
		</a>

		<button class="btn btn-outline-primary">
			<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Create']) ? $data['backendlang']['backendlang']['Create'] :'' }}</i>
		</button>

	</div>
</div>

@endsection

@section('js')

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript">
	// CKEDITOR.replace( 'description',{
	//                     filebrowserUploadUrl: descriptionUrl,
	//                     filebrowserUploadMethod: 'form'
	//                 });

	var descriptionUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token(), "p_id"=> ":p_id", "type" => "1" ]) }}';
  descriptionUrl = descriptionUrl.replace(':p_id', '1');

  var description = CKEDITOR.instances["description"];

  if(!description){
      CKEDITOR.replace( 'description',{
          filebrowserUploadUrl: descriptionUrl,
          filebrowserUploadMethod: 'form'
      });
  }

  var testimonial = CKEDITOR.instances["testimonial"];

  if(!testimonial){
      CKEDITOR.replace( 'testimonial',{
          filebrowserUploadUrl: descriptionUrl,
          filebrowserUploadMethod: 'form'
      });
  }

	$.ajax({
        url: '{{ route("LoadImage", "0") }}',
        type: 'get',
        success: function(response){
            $('.product-image-list .row').html(response);
        },
    });

    $('.product-image').on('click', '.delete-image-btn', function(e){
        e.preventDefault();
        alert(123);
    });

</script>

<script type="text/javascript">
    jQuery(function($){
            
        try {
            
            var myDropzone = Dropzone.options.dropzone =
            {

                maxFilesize: 5000,
                renameFile: function(file) {
                    var dt = new Date();
                    var time = dt.getTime();
                   return time+file.name;
                },
                acceptedFiles: ".jpeg,.jpg,.png,.gif,.mp4",
                timeout: 5000,
                dictRemoveFile: 'Remove',
                maxFiles: 100,
                dataType:'json',
                dictDefaultMessage :
                // '<span class="bigger-150 bolder"><i class="ace-icon fa fa-caret-right red"></i> Drop files</span> to upload \
                // <span class="smaller-80 grey">(or click)</span> <br /> \
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

    $('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	
    	$('#product-form').submit();
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

    $('.category_id').change(function(){
        $('.loading-gif').show();
        var ele = $(this);

        var fd = new FormData();
        fd.append('cid', ele.val());
        fd.append('pid', '');

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
</script>

@if(!empty(old('category_id')))
<script type="text/javascript">
    $('.category_id').trigger('change');
</script>
@endif

<script>

$('.add-variation').click( function(e){
    e.preventDefault();
    $('.variation-tab').show();
    $('.non-variation-tab').hide();

    $('.variation_enable').val(1);
});

$('.delete-variation').click( function(e){
    e.preventDefault();

    $('.variation-tab').hide();
    $('.non-variation-tab').show();
    $('.variation_enable').val(0);
});

$('#add-row-btn').click( function(e){
    e.preventDefault();

    $(this).closest('.variation_box').find('.parent_variation').append('<div class="form-group child-row">\
                                                                          <div class="row">\
                                                                            <div class="col-md-2">\
                                                                              <input type="text" name="variation_name[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Variation_Name']) ? $data['backendlang']['backendlang']['Variation_Name'] :'' }}">\
                                                                            </div>\
                                                                            <div class="col-md-2">\
                                                                              <input type="text" name="variation_price[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Price']) ? $data['backendlang']['backendlang']['Price'] :'' }}">\
                                                                            </div>\
                                                                            <div class="col-md-2">\
                                                                              <input type="text" name="variation_special_price[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Special_Price']) ? $data['backendlang']['backendlang']['Special_Price'] :'' }}">\
                                                                            </div>\
                                                                            <div class="col-md-2">\
                                                                              <input type="text" name="variation_birthday_price[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Member_Birthday_Price']) ? $data['backendlang']['backendlang']['Member_Birthday_Price'] :'' }}">\
                                                                            </div>\
                                                                            <div class="col-md-2">\
                                                                              <input type="text" name="variation_agent_price[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Agent_Price']) ? $data['backendlang']['backendlang']['Agent_Price'] :'' }}">\
                                                                            </div>\
                                                                            <div class="col-md-2">\
                                                                              <input type="text" name="variation_agent_special_price[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Agent_Special_Price']) ? $data['backendlang']['backendlang']['Agent_Special_Price'] :'' }}">\
                                                                            </div>\
                                                                            <div class="col-md-2">\
                                                                              <input type="text" name="variation_weight[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Product_Variation']) ? $data['backendlang']['backendlang']['Product_Variation'] :'' }}" value="">\
                                                                            </div>\
                                                                            <div class="col-md-2">\
                                                                              <input type="text" name="variation_stock[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Stock']) ? $data['backendlang']['backendlang']['Stock'] :'' }}">\
                                                                            </div>\
                                                                          </div>\
                                                                        </div>');
});

$('.add-v1-option').click( function(e){
    e.preventDefault();
    var ele = $(this);
    var total = $('.variation_option').length;
    
    var v2_enable = $('input[name="variation_two_enable"]').val();

    ele.closest('.row').find('.variation-parent-row').append('<div class="form-group variation-child-row">\
                                                                <input type="text" class="form-control variation_option" name="variation_option[]" placeholder=" {{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}" data-id="'+total+'">\
                                                              </div>');
    if(v2_enable == 1){
      var display = "";
    }else{
      var display = "display: none;";
    }

    var second_variation_enable = $('input[name="variation_two_enable"]').val();
    var total_variation_option = $('.second_variation_option').length;
    var addon;
    var a;
    
    if(total_variation_option > 0 && second_variation_enable == 1){
        for(a=1; a<=(parseFloat(total_variation_option)-1); a++){

          addon += '<tr class="added-v2-option_'+a+' added" data-id="'+total+'">\
                    <td class="variation_option_two_display_'+a+' variation_two">\
                        <input type="hidden" class="variation_option_two_value_'+a+'" name="variation_option_two_value_'+total+'[]" value="'+$('.second_variation_option_'+a+'').val()+'">\
                        <span>'+$('.second_variation_option_'+a+'').val()+'</span>\
                        <br>\
                        <input type="file" class="variation_option_two_image_'+total+' form-control" name="variation_option_two_image_'+total+'[]">\
                    </td>\
                    @if(Request::segment(2) != "point_product_list")\
                    <td><input type="text" name="retail_price_'+total+'[]" class="form-control">\
                    </td>\
                    <td><input type="text" name="retail_special_price_'+total+'[]" class="form-control">\
                    </td>\
                    @endif\
                    <td><input type="text" name="customer_price_'+total+'[]" class="form-control">\
                    </td>\
                    <td><input type="text" name="customer_special_price_'+total+'[]" class="form-control">\
                    </td>\
                    <td><input type="text" name="birthday_price_'+total+'[]" class="form-control">\
                    </td>\
                    <td><input type="text" name="birthday_special_price_'+total+'[]" class="form-control">\
                    </td>\
                    @foreach($agent_levels as $key => $agentlvl)\
                    <td>\
                      <input type="text" name="agent_level_price_'+total+'_'+a+'[]" class="form-control">\
                      <input type="hidden" name="variation_agent_level_'+total+'_'+a+'[]" value="{{ $agentlvl->id }}" class="form-control">\
                      <input type="hidden" name="variation_agent_level_id_'+total+'_'+a+'[]" value="" class="form-control">\
                    </td>\
                    <td>\
                      <input type="text" name="agent_level_special_price_'+total+'_'+a+'[]" class="form-control">\
                    </td>\
                    <td>\
                      <input type="text" name="agent_level_birthday_price_'+total+'_'+a+'[]" class="form-control">\
                    </td>\
                    <td>\
                      <input type="text" name="agent_level_birthday_special_price_'+total+'_'+a+'[]" class="form-control">\
                    </td>\
                    @endforeach\
                    <td><input type="text" name="variation_costing_price_'+total+'[]" class="form-control"></td>\
                    @if(Request::segment(2) != "point_product_list")\
                    <td><input type="text" name="get_point_'+total+'[]" class="form-control"></td>\
                    @endif\
                    <td><input type="text" name="weight_'+total+'[]" class="form-control"></td>\
                    <td><input type="text" name="stock_'+total+'[]" class="form-control"></td>\
                  </tr>';
        }
    }

    var rowspan = total_variation_option;
    var firstValue = $('.second_variation_option_0').val();
    firstValue = (firstValue != '') ? firstValue : '{{ isset($data["backendlang"]["backendlang"]["Option"]) ? $data["backendlang"]["backendlang"]["Option"] :'' }}';

    $('.variation-list-child-row').append('<tr data-id="'+total+'">\
                                              <td class="variation_option_display_'+total+' first_variation" rowspan="'+rowspan+'">\
                                                  <span>{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}</span>\
                                                  <br>\
                                                  <input type="file" name="variation_image_'+total+'[]" class="form-control">\
                                              </td>\
                                              <td class="variation_option_two_display_0 variation_two" style="'+display+'">\
                                                <input type="hidden" class="variation_option_two_value_0" name="variation_option_two_value_'+total+'[]" value="'+firstValue+'">\
                                                <span>'+firstValue+'</span>\
                                                <br>\
                                                <input type="file" class="variation_option_two_image_'+total+' form-control" name="variation_option_two_image_'+total+'[]">\
                                              </td>\
                                              @if(Request::segment(2) != "point_product_list")\
                                              <td><input type="text" name="retail_price_'+total+'[]" class="form-control">\
                                              </td>\
                                              <td><input type="text" name="retail_special_price_'+total+'[]" class="form-control">\
                                              </td>\
                                              @endif\
                                              <td><input type="text" name="customer_price_'+total+'[]" class="form-control">\
                                              </td>\
                                              <td><input type="text" name="customer_special_price_'+total+'[]" class="form-control">\
                                              </td>\
                                              <td><input type="text" name="birthday_price_'+total+'[]" class="form-control">\
                                              </td>\
                                              <td><input type="text" name="birthday_special_price_'+total+'[]" class="form-control">\
                                              </td>\
                                              @foreach($agent_levels as $key => $agentlvl)\
                                              <td>\
                                                  <input type="text" name="agent_level_price_'+total+'_0[]" class="form-control">\
                                                  <input type="hidden" name="variation_agent_level_'+total+'_0[]" value="{{ $agentlvl->id }}" class="form-control">\
                                                  <input type="hidden" name="variation_agent_level_id_'+total+'_0[]" value="" class="form-control">\
                                              </td>\
                                              <td>\
                                                <input type="text" name="agent_level_special_price_'+total+'_0[]" class="form-control">\
                                              </td>\
                                              <td>\
                                                <input type="text" name="agent_level_birthday_price_'+total+'_0[]" class="form-control">\
                                              </td>\
                                              <td>\
                                                <input type="text" name="agent_level_birthday_special_price_'+total+'_0[]" class="form-control">\
                                              </td>\
                                              @endforeach\
                                              <td><input type="text" name="variation_costing_price_'+total+'[]" class="form-control"></td>\
                                              @if(Request::segment(2) != "point_product_list")\
                                              <td><input type="text" name="get_point_'+total+'[]" class="form-control"></td>\
                                              @endif\
                                              <td><input type="text" name="weight_'+total+'[]" class="form-control"></td>\
                                              <td><input type="text" name="stock_'+total+'[]" class="form-control"></td>\
                                            </tr>'+addon);


});

$('.variation-parent-row').on('keyup', '.variation_option', function(e){
    e.preventDefault();

    var ele = $(this);
    var num = ele.data('id');

    $('.variation_option_display_'+num+'').find('span').html(ele.val());
});

$('.variation-parent-row').on('keyup', '.second_variation_option', function(e){
    e.preventDefault();

    var ele = $(this);
    var num = ele.data('id');

    $('.variation_option_two_display_'+num+'').find('span').html(ele.val());
    $('.variation_option_two_value_'+num+'').val(ele.val());
});

$('input[name="variation_title"]').keyup(function(){
    var ele = $(this);

    $('.variation_title').html(ele.val());
});

$('input[name="variation_two_title"]').keyup(function(){
    var ele = $(this);

    $('.variation_two_title').html(ele.val());
});

$('.hide_variation_two_area .btn').click(function(e){
    e.preventDefault();

    var ele = $(this);

    $('.variation_two_area').show();
    $('.hide_variation_two_area').hide();

    $('.variation_two').show();

    $('input[name="variation_two_enable"]').val(1);

    // $('.first_variation').closest('tr').find('input[type="text"]').each(function(){
    //     var name = $(this).attr('name');
    //     $(this).attr('name', name+'[]');
    // });
});

$('.close-variation-two').click(function(e){
    e.preventDefault();

    var ele = $(this);

    $('.variation_two_area').hide();
    $('.hide_variation_two_area').show(); 
    $('.variation_two').hide();

    $('input[name="variation_two_enable"]').val(0);

    // $('.first_variation').closest('tr').find('input[type="text"]').each(function(){
    //     var name = $(this).attr('name');
    //     $(this).attr('name', name+'[]');
    // });
});

$('.add-v2-option').click(function(e){
    e.preventDefault();

    
    var ele = $(this);
    var total = $('.second_variation_option').length;
    var rowspantotal = total;
    // alert(total);

    ele.closest('.row').find('.variation-parent-row').append('<div class="form-group variation-child-row">\
                                                                <input type="text" class="form-control second_variation_option second_variation_option_'+total+'" name="variation_two_option[]" placeholder="{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}" data-id="'+total+'">\
                                                              </div>');

    $('.first_variation').attr('rowspan', parseFloat(rowspantotal)+1);
    var totalFirstVar = $('.first_variation').length;
    var avo = $('.added').length;

    if(avo > 0){

      $('.added-v2-option_'+(parseFloat(total)-1)).after(function(){
          return '<tr class="added-v2-option_'+total+' added" data-id="'+$(this).data("id")+'">\
                    <td class="variation_option_two_display_'+total+' variation_two">\
                        <input type="hidden" class="variation_option_two_value_'+total+'" name="variation_option_two_value_'+$(this).data("id")+'[]">\
                        <span>{{ isset($data["backendlang"]["backendlang"]["Option"]) ? $data["backendlang"]["backendlang"]["Option"] :'' }}</span>\
                        <br>\
                        <input type="file" class="variation_option_two_image_'+total+' form-control" name="variation_option_two_image_'+total+'[]">\
                    </td>\
                    @if(Request::segment(2) != "point_product_list")\
                    <td><input type="text" name="retail_price_'+$(this).data("id")+'[]" class="form-control">\
                    </td>\
                    <td><input type="text" name="retail_special_price_'+$(this).data("id")+'[]" class="form-control">\
                    </td>\
                    @endif\
                    <td><input type="text" name="customer_price_'+$(this).data("id")+'[]" class="form-control">\
                    </td>\
                    <td><input type="text" name="customer_special_price_'+$(this).data("id")+'[]" class="form-control">\
                    </td>\
                    <td><input type="text" name="birthday_price_'+$(this).data("id")+'[]" class="form-control">\
                    </td>\
                    <td><input type="text" name="birthday_special_price_'+$(this).data("id")+'[]" class="form-control">\
                    </td>\
                    @foreach($agent_levels as $key => $agentlvl)\
                    <td>\
                        <input type="text" name="agent_level_price_'+$(this).data("id")+'_'+total+'[]" class="form-control">\
                        <input type="hidden" name="variation_agent_level_'+$(this).data("id")+'_'+total+'[]" value="{{ $agentlvl->id }}" class="form-control">\
                        <input type="hidden" name="variation_agent_level_id_'+$(this).data("id")+'_'+total+'[]" value="" class="form-control">\
                    </td>\
                    <td>\
                      <input type="text" name="agent_level_special_price_'+$(this).data("id")+'_'+total+'[]" class="form-control">\
                    </td>\
                    <td>\
                      <input type="text" name="agent_level_birthday_price_'+$(this).data("id")+'_'+total+'[]" class="form-control">\
                    </td>\
                    <td>\
                      <input type="text" name="agent_level_birthday_special_price_'+$(this).data("id")+'_'+total+'[]" class="form-control">\
                    </td>\
                    @endforeach\
                    <td><input type="text" name="variation_costing_price_'+$(this).data("id")+'[]" class="form-control"></td>\
                    @if(Request::segment(2) != "point_product_list")\
                    <td><input type="text" name="get_point_'+$(this).data("id")+'[]" class="form-control"></td>\
                    @endif\
                    <td><input type="text" name="weight_'+$(this).data("id")+'[]" class="form-control"></td>\
                    <td><input type="text" name="stock_'+$(this).data("id")+'[]" class="form-control"></td>\
                  </tr>';
      })

    }else{
      $('.first_variation').closest('tr').after(function(){

          return '<tr class="added-v2-option_'+total+' added" data-id="'+$(this).data("id")+'">\
                    <td class="variation_option_two_display_'+total+' variation_two">\
                        <input type="hidden" class="variation_option_two_value_'+total+'" name="variation_option_two_value_'+$(this).data("id")+'[]">\
                        <span>{{ isset($data["backendlang"]["backendlang"]["Option"]) ? $data["backendlang"]["backendlang"]["Option"] :'' }}</span>\
                        <br>\
                        <input type="file" class="variation_option_two_image_'+total+' form-control" name="variation_option_two_image_'+$(this).data('id')+'[]">\
                    </td>\
                    @if(Request::segment(2) != "point_product_list")\
                    <td><input type="text" name="retail_price_'+$(this).data("id")+'[]" class="form-control">\
                    </td>\
                    <td><input type="text" name="retail_special_price_'+$(this).data("id")+'[]" class="form-control">\
                    </td>\
                    @endif\
                    <td><input type="text" name="customer_price_'+$(this).data("id")+'[]" class="form-control">\
                    </td>\
                    <td><input type="text" name="customer_special_price_'+$(this).data("id")+'[]" class="form-control">\
                    </td>\
                    <td><input type="text" name="birthday_price_'+$(this).data("id")+'[]" class="form-control">\
                    </td>\
                    <td><input type="text" name="birthday_special_price_'+$(this).data("id")+'[]" class="form-control">\
                    </td>\
                    @foreach($agent_levels as $key => $agentlvl)\
                    <td>\
                        <input type="text" name="agent_level_price_'+$(this).data("id")+'_'+total+'[]" class="form-control">\
                        <input type="hidden" name="variation_agent_level_'+$(this).data("id")+'_'+total+'[]" value="{{ $agentlvl->id }}" class="form-control">\
                        <input type="hidden" name="variation_agent_level_id_'+$(this).data("id")+'_'+total+'[]" value="" class="form-control">\
                    </td>\
                    <td>\
                      <input type="text" name="agent_level_special_price_'+$(this).data("id")+'_'+total+'[]" class="form-control">\
                    </td>\
                    <td>\
                      <input type="text" name="agent_level_birthday_price_'+$(this).data("id")+'_'+total+'[]" class="form-control">\
                    </td>\
                    <td>\
                      <input type="text" name="agent_level_birthday_special_price_'+$(this).data("id")+'_'+total+'[]" class="form-control">\
                    </td>\
                    @endforeach\
                    <td><input type="text" name="variation_costing_price_'+$(this).data("id")+'[]" class="form-control"></td>\
                    @if(Request::segment(2) != "point_product_list")\
                    <td><input type="text" name="get_point_'+$(this).data("id")+'[]" class="form-control"></td>\
                    @endif\
                    <td><input type="text" name="weight_'+$(this).data("id")+'[]" class="form-control"></td>\
                    <td><input type="text" name="stock_'+$(this).data("id")+'[]" class="form-control"></td>\
                  </tr>';
      })
      // $('.first_variation').closest('tr').after();      
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
                toastr.sucess("{{ isset($data['backendlang']['backendlang']['Updated']) ? $data['backendlang']['backendlang']['Updated'] :'' }}");
           },
        });
    }); 
    
}  
</script>

@endsection