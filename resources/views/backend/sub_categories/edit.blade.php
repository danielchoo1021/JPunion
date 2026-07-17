@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('sub_category.sub_categories.update', $sub_category->id) }}" id="sub-categories-form">
@csrf
@method('PUT')
@include('backend.sub_categories.form')
</form>

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('sub_category.sub_categories.index') }}" class="btn btn-outline-danger">
			<i class="bi bi-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
		</a>

		<button class="btn btn-outline-primary">
			<i class="bi bi-check">  {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
		</button>

	</div>
</div>

@endsection

@section('js')
<script type="text/javascript">
	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	
    	$('#sub-categories-form').submit();
    });
</script>
@endsection