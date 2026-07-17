@extends('layouts.app')
@section('content')
<div class="breadcrumb">
    <div class="container">
        <h2>{{ isset($data['lang']['lang']['locator']) ? $data['lang']['lang']['locator'] :'商店地点' }}</h2>
    </div>
</div>

<section class="blog spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <img src="{{ asset('images/map.png') }}" style="max-width: 100%;">
                </div>
            </div>
        </div>

        <div class="container my-3">
            <div class="form-group">
                <div class="row">
                    @foreach($corporates as $state)
                        <div class="col-6" align="center" style="margin-bottom: 2em;">
                            <a href="{{ route('locator_detail', $state->get_state->id) }}" class="btn btn-primary" style="width: 20rem; white-space: initial;">
                                {{ $state->get_state->name }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection