@extends('layouts.app')
@section('content')
@if(!empty($data['setting_header']->quiz_bg_image))
	<div class="page-header" style="background-image: url({{ asset($data['setting_header']->quiz_bg_image) }});">

    </div>
@else
	<div class="breadcrumb">
		<div class="container">
			<h2>{{ isset($data['lang']['lang']['quiz']) ? $data['lang']['lang']['quiz'] :'Quiz' }}</h2>
		</div>
	</div>
@endif
<div class="container my-5 pb-4 quiz-text-black">
	<div class="form-group container-box">
		<h4>{{ isset($data["lang"]["lang"]["user_details"]) ? $data["lang"]["lang"]["user_details"] :"User Details" }}</h4>
		<hr>
		<p>{{ isset($data["lang"]["lang"]["name"]) ? $data["lang"]["lang"]["name"] :"Name" }}: {{ $quiz_record->f_name }}</p>
		<hr>
		{{-- <p>{{ isset($data["lang"]["lang"]["email"]) ? $data["lang"]["lang"]["email"] :"Email" }}: {{ $quiz_record->email }}</p> --}}
		<p>{{ isset($data["lang"]["lang"]["phone"]) ? $data["lang"]["lang"]["phone"] :"Phone" }}: {{ $quiz_record->phone }}</p>
	</div>
	<div class="form-group container-box">
		<h4>{{ isset($data["lang"]["lang"]["quizzes"]) ? $data["lang"]["lang"]["quizzes"] :"Quizzes" }}</h4>
		<hr>
		@foreach($quiz_record->get_quiz_details as $key => $quiz)
			<p>
				{{ isset($data["lang"]["lang"]["quiz"]) ? $data["lang"]["lang"]["quiz"] :"Quiz" }} {{ $key+1 }}: 
				@if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
					@if($_COOKIE['global_language'] == '1')
						{{ !empty($quiz->get_quiz_title->quiz_title_cn) ? $quiz->get_quiz_title->quiz_title_cn : '暂无华文翻译' }}
					@else
						{{ !empty($quiz->get_quiz_title->quiz_title) ? $quiz->get_quiz_title->quiz_title : 'English translation not available' }}
					@endif
				@else
					{{ !empty($quiz->get_quiz_title->quiz_title) ? $quiz->get_quiz_title->quiz_title : 'English translation not available' }}
				@endif
			</p>
			<li>
				{{ isset($data["lang"]["lang"]["answer"]) ? $data["lang"]["lang"]["answer"] :"Answer" }}: 
				@if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
					@if($_COOKIE['global_language'] == '1')
						{{ !empty($quiz->get_quiz_det->answer_cn) ? $quiz->get_quiz_det->answer_cn : '暂无华文翻译' }}
					@else
						{{ !empty($quiz->get_quiz_det->answer) ? $quiz->get_quiz_det->answer : 'English translation not available' }}
					@endif
				@else
					{{ !empty($quiz->get_quiz_det->answer) ? $quiz->get_quiz_det->answer : 'English translation not available' }}
				@endif
			</li>
			<li>
				{{ isset($data["lang"]["lang"]["suggestion"]) ? $data["lang"]["lang"]["suggestion"] :"Suggestion" }}: 
				@if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
					@if($_COOKIE['global_language'] == '1')
						{{ !empty($quiz->get_quiz_det->suggestion_cn) ? $quiz->get_quiz_det->suggestion_cn : '暂无华文翻译' }}
					@else
						{{ !empty($quiz->get_quiz_det->suggestion) ? $quiz->get_quiz_det->suggestion : 'English translation not available' }}
					@endif
				@else
					{{ !empty($quiz->get_quiz_det->suggestion) ? $quiz->get_quiz_det->suggestion : 'English translation not available' }}
				@endif
			</li>
			<hr>
		@endforeach
	</div>
</div>
@endsection