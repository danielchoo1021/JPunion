@extends('layouts.app')

@section('content')
<!-- <div class="custom-border-bottom py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-0">
                <a href="{{ route('home') }}">Home</a> 
                <span class="mx-2 mb-0">/</span>
                <strong class="text-black">Material</strong>
            </div>
        </div>
    </div>
</div> -->
<div class="holder breadcrumbs-wrap mt-5">
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="{{ route('home') }}">{{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] :'首页'}}</a></li>
            <li>{{ isset($data['lang']['lang']['materials']) ? $data['lang']['lang']['materials'] :'资料库'}}</li>
        </ul>
    </div>
  </div>

<div class="py-3">
    <div class="container">
        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">{{ isset($data['lang']['lang']['knowledge_photo']) ? $data['lang']['lang']['knowledge_photo'] :'知识图'}}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">{{ isset($data['lang']['lang']['videos']) ? $data['lang']['lang']['videos'] :'视频'}}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">{{ isset($data['lang']['lang']['file']) ? $data['lang']['lang']['file'] :'档案'}}</a>
          </li>
        </ul>
        <div class="tab-content" id="pills-tabContent" style="position: initial;">
          <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="row">
                  @foreach($materials as $material)
                    @if($material->type_id == '1')
                    @php
                        $exp = explode(".", $material->images);
                        $file_ext = end($exp);
                    @endphp
                    <div class="col-sm-3 col-6">
                        <div class="form-group">
                            <a href="#" data-toggle="modal" data-target="#myModal{{ $material->id }}" style="display: block;">
                                <!-- <img src="{{ $material->images }}" style="width: 100%;"> -->
                                @if($file_ext == 'mp4')
                                    <video id="myVideo" class="myVideo" style="width: 100%; position: relative;" poster="{{ asset('images/index-media-cover-art-play-button-overlay-5.png') }}">
                                        <source src="{{ asset($material->images) }}" type="video/mp4">
                                    </video>
                                    <a href="{{ asset($material->images) }}" class="btn btn-block btn-primary download-btn btn-xs set_button set_text" download>
                                        {{ isset($data['lang']['lang']['download']) ? $data['lang']['lang']['download'] :'下载'}}
                                    </a>
                                @elseif($file_ext == 'pdf')
                                    <iframe src="{{ asset($material->images) }}" width="100%" style="height:100%"></iframe>
                                    <a href="{{ asset($material->images) }}" class="btn btn-block btn-primary download-btn btn-xs set_button set_text" download>
                                        {{ isset($data['lang']['lang']['download']) ? $data['lang']['lang']['download'] :'下载'}}
                                    </a>
                                @else
                                    <div class="material_images" style="width: 100%; height: 300px; background-image: url({{ $material->images }});
                                                         background-size: 100%; background-repeat: no-repeat; background-position: center;">
                                    </div>
                                    <a href="{{ asset($material->images) }}" class="btn btn-block btn-primary download-btn btn-xs set_button set_text" download>
                                        {{ isset($data['lang']['lang']['download']) ? $data['lang']['lang']['download'] :'下载'}}
                                    </a>
                                @endif
                            </a>
                            <div class="modal fade" id="myModal{{ $material->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <div class="modal-body" style="padding: 0px;">
                                    @if($file_ext == 'mp4')
                                        <video id="myVideo" class="myVideo" style="width: 100%;" controls>
                                            <source src="{{ asset($material->images) }}" type="video/mp4">
                                        </video>
                                    @elseif($file_ext == 'pdf')
                                        <iframe src="{{ asset($material->images) }}" width="100%" style="height:100%"></iframe>
                                    @else
                                        <img src="{{ $material->images }}" style="width: 100%;">
                                    @endif
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
          </div>
          <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="row">
                  @foreach($materials as $material)
                    @if($material->type_id == '2')
                    @php
                        $exp = explode(".", $material->images);
                        $file_ext = end($exp);
                    @endphp
                    <div class="col-sm-3 col-6">
                        <div class="form-group">
                            <a href="#" data-toggle="modal" data-target="#myModal{{ $material->id }}">
                                <!-- <img src="{{ $material->images }}" style="width: 100%;"> -->
                                @if($file_ext == 'mp4')
                                    <video id="myVideo" class="myVideo" style="width: 100%; position: relative;">
                                        <source src="{{ asset($material->images) }}" type="video/mp4">
                                    </video>
                                    <a href="{{ asset($material->images) }}" class="btn btn-block btn-primary download-btn btn-xs set_button set_text" download>
                                        {{ isset($data['lang']['lang']['download']) ? $data['lang']['lang']['download'] :'下载'}}
                                    </a>
                                @elseif($file_ext == 'pdf')
                                    <iframe src="{{ asset($material->images) }}" width="100%" style="height:100%"></iframe>
                                    <a href="{{ asset($material->images) }}" class="btn btn-block btn-primary download-btn btn-xs set_button set_text" download>
                                        {{ isset($data['lang']['lang']['download']) ? $data['lang']['lang']['download'] :'下载'}}
                                    </a>
                                @else
                                    <div class="material_images" style="width: 100%; height: 300px; background-image: url({{ $material->images  }});
                                                         background-size: cover; background-repeat: no-repeat; background-position: center;">
                                    </div>
                                    <a href="{{ asset($material->images) }}" class="btn btn-block btn-primary download-btn btn-xs set_button set_text" download>
                                        {{ isset($data['lang']['lang']['download']) ? $data['lang']['lang']['download'] :'下载'}}
                                    </a>
                                @endif
                            </a>
                            <div class="modal fade" id="myModal{{ $material->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <div class="modal-body" style="padding: 0px;">
                                    @if($file_ext == 'mp4')
                                        <video id="myVideo" class="myVideo" style="width: 100%;" controls>
                                            <source src="{{ asset($material->images) }}" type="video/mp4">
                                        </video>
                                    @elseif($file_ext == 'pdf')
                                        <iframe src="{{ asset($material->images) }}" width="100%" style="height:100%"></iframe>
                                    @else
                                        <img src="{{ $material->images }}" style="width: 100%;">
                                    @endif
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
          </div>
          <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                  @foreach($materials as $material)
                    @if($material->type_id == '3')
                    @php
                        $exp = explode(".", $material->images);
                        $file_ext = end($exp);
                    @endphp
                    <div class="row" style="margin-left: 0; margin-right: 0;">
                        <div class="form-group">
                            <a href="#" data-toggle="modal" data-target="#myModal{{ $material->id }}">
                                <!-- <img src="{{ $material->images }}" style="width: 100%;"> -->
                                @if($file_ext == 'mp4')
                                    <video id="myVideo" class="myVideo" style="width: 100%; position: relative;" poster="{{ asset('images/index-media-cover-art-play-button-overlay-5.png') }}">
                                        <source src="{{ asset($material->images) }}" type="video/mp4">
                                    </video>
                                    <a href="{{ asset($material->images) }}" class="btn btn-block btn-primary download-btn btn-xs set_button set_text" download>
                                        {{ isset($data['lang']['lang']['download']) ? $data['lang']['lang']['download'] :'下载'}}
                                    </a>
                                @elseif($file_ext == 'pdf')
                                    <div class="row">
                                    <!-- <iframe src="{{ asset($material->images) }}" width="100%" style="height:100%"></iframe> -->
                                        <div class="col-xs-4">
                                            <a href="{{ asset($material->images) }}" download="{{ $material->file_name }}">
                                                {{ $material->file_name }}
                                            </a>
                                        </div>
                                        <div class="col-xs-8">
                                            <a href="{{ asset($material->images) }}" class="btn btn-primary download-btn btn-xs set_button set_text" download>
                                                {{ isset($data['lang']['lang']['download']) ? $data['lang']['lang']['download'] :'下载'}}
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="material_images" style="width: 100%; height: 300px; background-image: url({{ $material->images  }});
                                                         background-size: cover; background-repeat: no-repeat; background-position: center;">
                                    </div>
                                    <a href="{{ asset($material->images) }}" class="btn btn-block btn-primary download-btn btn-xs set_button set_text" download>
                                        {{ isset($data['lang']['lang']['download']) ? $data['lang']['lang']['download'] :'下载'}}
                                    </a>
                                @endif
                            </a>
                            <div class="modal fade" id="myModal{{ $material->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <div class="modal-body" style="padding: 0px;">
                                    @if($file_ext == 'mp4')
                                        <video id="myVideo" class="myVideo" style="width: 100%;" controls poster="{{ asset('images/index-media-cover-art-play-button-overlay-5.png') }}">
                                            <source src="{{ asset($material->images) }}" type="video/mp4">
                                        </video>
                                    @elseif($file_ext == 'pdf')
                                        <iframe src="{{ asset($material->images) }}" width="100%" style="height:100%"></iframe>
                                    @else
                                        <img src="{{ $material->images }}" style="width: 100%;">
                                    @endif
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
          </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $('.modal').on('hidden.bs.modal', function () {
        $('.myVideo').trigger('pause');
        
    })
</script>
@endsection