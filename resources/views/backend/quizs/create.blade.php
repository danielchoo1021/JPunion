@extends('layouts.admin_app')

@section('content')

@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif

<form method="POST" action="{{ route('quiz.quizs.store') }}" id="agent-form" enctype="multipart/form-data">
@csrf
@include('backend.quizs.form')
</form>

@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['quiz-insert']))
<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('quiz.quizs.index') }}" class="btn btn-outline-danger">
			<i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :''}}</i>
		</a>

		<button class="btn btn-outline-primary">
			<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Create']) ? $data['backendlang']['backendlang']['Create'] :''}}</i>
		</button>

	</div>
</div>
@endif
@endsection

@section('js')
<script type="text/javascript">
    let newIndex = 0;

    $(document).on('click', '.add-new-answer', function(e) {
        e.preventDefault();
        newIndex++;

        let newRow = `
            <div class="pb-4 w-100">
                <div class="row">
                    <div class="col-2" align="right">
                        <label>
                            <b>({{ isset($data['backendlang']['backendlang']['New']) ? $data['backendlang']['backendlang']['New'] :''}})</b>  {{ isset($data['backendlang']['backendlang']['Answer_Suggestion_EN']) ? $data['backendlang']['backendlang']['Answer_Suggestion_EN'] :''}}: <label class="important-text">*</label>
                        </label>
                    </div>
                    <div class="col-10">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <input type="hidden" name="answer[new_${newIndex}][id]" value="">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="answer[new_${newIndex}][answer]" placeholder="{{ isset($data['backendlang']['backendlang']['Answer(EN)']) ? $data['backendlang']['backendlang']['Answer(EN)'] :''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="suggestion[new_${newIndex}][suggestion]" placeholder="{{ isset($data['backendlang']['backendlang']['Suggestion(EN)']) ? $data['backendlang']['backendlang']['Suggestion(EN)'] :''}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-2" align="right">
                        <label>
                            {{ isset($data['backendlang']['backendlang']['Answer_Suggestion_CN']) ? $data['backendlang']['backendlang']['Answer_Suggestion_CN'] :''}}: <label class="important-text">*</label>
                        </label>
                    </div>
                    <div class="col-10">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="answer_cn[new_${newIndex}][answer_cn]" placeholder="{{ isset($data['backendlang']['backendlang']['Answer(CN)']) ? $data['backendlang']['backendlang']['Answer(CN)'] :''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="suggestion_cn[new_${newIndex}][suggestion_cn]" placeholder="{{ isset($data['backendlang']['backendlang']['Suggestion(CN)']) ? $data['backendlang']['backendlang']['Suggestion(CN)'] :''}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('.add-new-answer-list').append(newRow);
    });

    $('.submit-form-btn .btn-outline-primary').click(function(e){
        e.preventDefault();
        $('.loading-gif').show();
        $('#agent-form').submit();
    });
</script>
@endsection