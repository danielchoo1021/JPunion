<div class="container-box form-group">
    <div class="form-group pb-1">
        <div class="row">
            <div class="col-2">
                <label>
                    {{ isset($data['backendlang']['backendlang']['Question(EN)']) ? $data['backendlang']['backendlang']['Question(EN)'] :''}}: <label class="important-text">*</label>
                </label>
            </div>
            <div class="col-10">
                <input type="text" class="form-control" name="quiz_title" placeholder="{{ isset($data['backendlang']['backendlang']['Question(EN)']) ? $data['backendlang']['backendlang']['Question(EN)'] :''}}" value="{{ isset($quiz) ? $quiz->quiz_title : old('quiz_title') }}">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-2">
                <label>
                   {{ isset($data['backendlang']['backendlang']['Question(CN)']) ? $data['backendlang']['backendlang']['Question(CN)'] :''}}: <label class="important-text">*</label>
                </label>
            </div>
            <div class="col-10">
                <input type="text" class="form-control" name="quiz_title_cn" placeholder="{{ isset($data['backendlang']['backendlang']['Question(CN)']) ? $data['backendlang']['backendlang']['Question(CN)'] :''}}" value="{{ isset($quiz) ? $quiz->quiz_title_cn : old('quiz_title_cn') }}">
            </div>
        </div>
    </div>
</div>
<hr>
<div class="container-box form-group">
    <div class="form-group pb-3">
        <div class="row add-new-answer-list">
            @if(isset($quiz_details) && !$quiz_details->isEmpty())
                @foreach($quiz_details as $key =>  $detail)
                    <div class="pb-4 w-100">
                        <div class="row">
                            <div class="col-2" align="right">
                                <label>
                                    <b>{{ $key+1 }}</b> {{ isset($data['backendlang']['backendlang']['Answer_Suggestion_EN']) ? $data['backendlang']['backendlang']['Answer_Suggestion_EN'] :''}}: <label class="important-text">*</label>
                                </label>
                            </div>
                            <div class="col-10">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="hidden" name="answer[{{ $detail->id }}][id]" value="{{ $detail->id }}">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="answer[{{ $detail->id }}][answer]" placeholder="{{ isset($data['backendlang']['backendlang']['Answer(EN)']) ? $data['backendlang']['backendlang']['Answer(EN)'] :''}}" value="{{ $detail->answer }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="suggestion[{{ $detail->id }}][suggestion]" placeholder="{{ isset($data['backendlang']['backendlang']['Suggestion(EN)']) ? $data['backendlang']['backendlang']['Suggestion(EN)'] :''}}" value="{{ $detail->suggestion }}">
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
                                                <input type="text" class="form-control" name="answer_cn[{{ $detail->id }}][answer_cn]" placeholder="{{ isset($data['backendlang']['backendlang']['Answer(CN)']) ? $data['backendlang']['backendlang']['Answer(CN)'] :''}}" value="{{ $detail->answer_cn }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="suggestion_cn[{{ $detail->id }}][suggestion_cn]" placeholder="{{ isset($data['backendlang']['backendlang']['Suggestion(CN)']) ? $data['backendlang']['backendlang']['Suggestion(CN)'] :''}}" value="{{ $detail->suggestion_cn }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-12 text-center">
                <a href="#" class="btn btn-outline-primary add-new-answer">
                    <i class="bi bi-plus"></i>
                </a>
            </div>
        </div>
    </div>
</div>