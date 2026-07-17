@extends('layouts.app')

@section('content')
<div class="page-content">
   
    <div class="holder mt-4 mb-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 posts-list">
                    <div class="blog__item">
                        <div class="blog__item__pic" align="center">
                            @if(!empty($blog->image))
                            <img src="{{ asset($blog->image) }}" class="h-501" alt="...">
                            @endif
                        </div>
                        <br>
                        <br>
                        <div class="blog__item__text">
                            @if(!empty($blog->blog_date))
                                <p>
                                    <i class="far fa-calendar"></i>
                                    {{ date('d M, Y', strtotime($blog->blog_date)) }}
                                </p>
                            @endif
                            @php
                                $use_cn = isset($_COOKIE['global_language']) && $_COOKIE['global_language'] == '1';
                                $raw_tags = $use_cn ? $blog->blog_tags_cn : $blog->blog_tags;
                                $tags = is_array($raw_tags) ? $raw_tags : json_decode($raw_tags, true);
                            @endphp
                            @if(!empty($tags))
                                @foreach($tags as $tag)
                                    <p><i class="fa fa-tag" aria-hidden="true"></i> {{ $tag }}</p>
                                @endforeach
                            @endif
                            <br>
                            <h3 class="mb-f-14">
                                <a href="#">
                                    @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                        @if($_COOKIE['global_language'] == '1')
                                            {{ !empty($blog->title_cn) ? $blog->title_cn : '暂无华文翻译' }}
                                        @else
                                            {{ !empty($blog->title) ? $blog->title : '' }}
                                        @endif
                                    @else
                                        {{ !empty($blog->title) ? $blog->title : '' }}
                                    @endif
                                </a>
                            </h3>
                            <div class="text-editor-image mt-3">
                                @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                    @if($_COOKIE['global_language'] == '1')
                                        {!! $blog->description_cn ? htmlspecialchars_decode($blog->description_cn) : '暂无华文翻译' !!}
                                    @else
                                        {!! $blog->description ? htmlspecialchars_decode($blog->description) : '' !!}
                                    @endif
                                @else
                                    {!! $blog->description ? htmlspecialchars_decode($blog->description) : '' !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- @foreach($comments as $comment)
                <div class="container-box form-group">
                    <blockquote class="blockquote">
                      <p class="mb-0">{{ $comment->comment }}</p>
                      <footer class="blockquote-footer">
                        <small>
                          @if(!empty($comment->u_name))
                            {{ $comment->u_name }}
                          @elseif(!empty($comment->a_name))
                            {{ $comment->a_name }}
                          @else
                            {{ $comment->m_name }}
                          @endif
                        </small>
                      </footer>
                    </blockquote>
                </div>
            @endforeach

                <hr>

            <form method="POST" action="{{ route('blog_comment', $blog->id) }}">
                @csrf
                <label>Write down your comment.</label>
                <textarea class="form-control col-md-6" name="comment" placeholder="{{ isset($data["lang"]["lang"]["write_a_comment"]) ? $data["lang"]["lang"]["write_a_comment"] :"Write a comment" }}"></textarea>
                <br>
                <button class="btn">Comment</button>
            </form> -->
        </div>
    </div>
</div>
@endsection