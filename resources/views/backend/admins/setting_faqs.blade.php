@extends('layouts.admin_app')
@section('css')
<style type="text/css">
	span.input-icon{
		display: block !important;
	}
</style>
@endsection
@section('content')
<div class="page-header">
    <h1>
        {{ isset($data['backendlang']['backendlang']['Setting_FAQs']) ? $data['backendlang']['backendlang']['Setting_FAQs'] :'' }}
        <!-- <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            @if(Auth::check())
            	@if(!empty(Auth::user()->f_name) && !empty(Auth::user()->l_name))
            		{{ Auth::user()->f_name }} {{ Auth::user()->l_name }} 
            	@else
            		{{ Auth::user()->email }}
            	@endif
            @endif
        </small> -->
    </h1>
</div>

<form method="POST" action="{{ route('setting_faqs') }}">
    @csrf
    <div class="row">
        <div class="col-12">
            @if(Auth::guard('admin')->check())
            <h4 class="header blue bolder smaller">{{ isset($data['backendlang']['backendlang']['FAQs']) ? $data['backendlang']['backendlang']['FAQs'] :'' }}</h4>
            <div class="row">
                <div class="col-sm-12">
                     <textarea class="form-control" name="setting_faqs_description" id="setting_faqs_description">{!! (!empty($setting)) ? $setting->faqs : '' !!}</textarea>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="clearfix form-actions">
        <div class="col-md-12">
            <button class="btn btn-info" type="submit">
                <i class="ace-icon fa fa-check bigger-110"></i>
                {{ isset($data['backendlang']['backendlang']['Save']) ? $data['backendlang']['backendlang']['Save'] :'' }}
            </button>

            &nbsp; &nbsp;
            <!-- <button class="btn" type="reset">
                <i class="ace-icon fa fa-long-arrow-left bigger-110"></i>
                Back To List
            </button> -->
        </div>
    </div>
</form>
@endsection

@section('js')
<script type="text/javascript">
    CKEDITOR.replace( 'setting_faqs_description' );
</script>
@endsection