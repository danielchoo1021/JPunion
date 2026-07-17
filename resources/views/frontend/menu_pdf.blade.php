@extends('layouts.app')

@section('content')
<iframe src="{{ url($website_setting->menu) }}" width="100%" style="height:100vh !important;"></iframe>
@endsection