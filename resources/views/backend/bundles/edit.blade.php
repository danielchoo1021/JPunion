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
</style>
@endsection
@section('content')
<div class="page-header">
    <h1>
        {{ isset($data['backendlang']['backendlang']['Bundle_Details']) ? $data['backendlang']['backendlang']['Bundle_Details'] :'' }}
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            {{ $bundle->bundle_name }}
        </small>
    </h1>
</div>
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
@include('backend.bundles.form')

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('bundle.bundles.index') }}" class="btn btn-outline-danger">
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
	// CKEDITOR.replace( 'description',{
	//                     filebrowserUploadUrl: descriptionUrl,
	//                     filebrowserUploadMethod: 'form'
	//                 });
  var descriptionUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token(), "p_id"=> ":p_id", "type" => "1" ]) }}';

	var description = CKEDITOR.instances["description"];
  descriptionUrl = descriptionUrl.replace(':p_id', '{{ $bundle->id }}');

  if(!description){
      CKEDITOR.replace( 'description',{
          filebrowserUploadUrl: descriptionUrl,
          filebrowserUploadMethod: 'form'
      });
  }

  $('.submit-form-btn .btn-outline-primary').click( function(e){
      e.preventDefault();
      

      var v_enable =  $('.variation_enable').val();
      

      $('#bundle-form').submit();
  });
  
  $('.add-shipping-btn').click(function(e){
        e.preventDefault();
        var ele = $(this);
        var item = '<div class="row">\
                      <div class="col-sm-6">\
                        <select class="form-control" name="product_id[]">\
                          <option value="">{{ isset($data['backendlang']['backendlang']['Select_Product']) ? $data['backendlang']['backendlang']['Select_Product'] :'' }}</option>\
                          @foreach($products as $product)\
                          <option value="{{ $product->id }}">{{ $product->product_name }}</option>]\
                          @endforeach\
                        </select>\
                      </div>\
                    </div>\
                    <hr>';

        ele.closest('.parent-row').find('.child-row').append(item);

    });
</script>
@endsection