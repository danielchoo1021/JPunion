@extends('layouts.app')
@section('css')
<style type="text/css">
:root {
    --line-border-fill: #3498DB;
    --line-border-empty: #e0e0e0;
}

* {
    box-sizing: border-box;
}
.progress-container {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin-bottom: 30px;
    min-width: 100%;
    width: 350px;
}

.progress-container::before {
    content: '';
    background-color: var(--line-border-empty);
    position: absolute;
    top: 50%;
    left: 0;
    transform: translateY(-50%);
    height: 4px;
    width: 100%;
    z-index: -1;
}

.progress {
    background-color: var(--line-border-fill);
    position: absolute;
    top: 50%;
    left: 0;
    transform: translateY(-50%);
    height: 4px;
    width: 0%;
    z-index: -1;
    transition: .4s ease;
}

.circle {
    background-color: #fff;
    color: #999;
    border-radius: 50%;
    height: 30px;
    width: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid var(--line-border-empty);
    transition: .4s ease;
}

.circle.active {
    border-color: var(--line-border-fill);
}

.btn {
    background-color: var(--line-border-fill);
    color: #fff;
    border: 0;
    border-radius: 6px;
    cursor: pointer;
    font-family: inherit;
    padding: 8px 30px;
    margin: 5px;
    font-size: 14px;
}

.btn:active {
    transform: scale(0.98);
}

.btn:focus {
    outline: 0;
}

.btn:disabled {
    background-color: var(--line-border-empty);
    cursor: not-allowed;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
@endsection
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
<div class="container" align="center">
	<div class="form-group">
		<h2>
			{{ isset($data["lang"]["lang"]["find_your_ideal_health_solution"]) ? $data["lang"]["lang"]["find_your_ideal_health_solution"] :"Find Your Ideal Health Solution" }}
		</h2>
		<div class="pt-2">
			{{ isset($data["lang"]["lang"]["support_your_unique_needs"]) ? $data["lang"]["lang"]["support_your_unique_needs"] :"Take this short quiz to match with products designed to support your unique needs." }}
		</div>
	</div>
</div>
@if(!$quizes->isEmpty())
<div class="container">
    <div class="progress-container">
        <div class="progress quizs" id="progress"></div>
        @foreach($quizes as $quiz_key => $quiz)
            <div class="circle 
                        {{ ($quiz_key == 0) ? 'active' : '' }} 
                        {{ ($quiz_key == 0) ? 'steped' : '' }}" data-id="{{ $quiz->id }}">
                {{ $quiz_key+1 }}
            </div>
        @endforeach
    </div>

    <form method="POST" action="{{ route('submit_quiz') }}" class="quizes">
        @csrf
        <div class="form-group">
            @if($errors->any())
                <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
            @endif
        </div>
        @foreach($quizes as $quiz_key => $quiz)
            <div class="answers" data-id="{{ $quiz->id }}" style="display: none;">
                <h3 align='center'>
                    @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                        @if($_COOKIE['global_language'] == '1')
                            {{ !empty($quiz->quiz_title_cn) ? $quiz->quiz_title_cn : '暂无华文翻译' }}
                        @else
                            {{ !empty($quiz->quiz_title) ? $quiz->quiz_title : 'English translation not available' }}
                        @endif
                    @else
                        {{ !empty($quiz->quiz_title) ? $quiz->quiz_title : 'English translation not available' }}
                    @endif
                </h3>
                <input type="hidden" name="tid[]" value="{{ $quiz->id }}">
                <hr>
                @foreach($quiz->get_quiz_details as $detail)
                    <div class="container-box form-group answer-selection" style="cursor: pointer;">
                        <div class="row">
                            <div class="col-12">
                                <label class="checkbox">
                                    <input type="checkbox" class="answer" name="answer_amount[]" value="{{ $detail->id }}">
                                    @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
										@if($_COOKIE['global_language'] == '1')
                                            {{ !empty($detail->answer_cn) ? $detail->answer_cn : '暂无华文翻译' }}
										@else
											{{ !empty($detail->answer) ? $detail->answer : 'English translation not available' }}
										@endif
									@else
										{{ !empty($detail->answer) ? $detail->answer : 'English translation not available' }}
									@endif
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
        
        <div class="submit_form_data" style="display: none;">
            <div class="container-box">
                <input type="hidden" name="code" value="{{ !empty(Auth::guard($data['userGuardRole'])->user()->code) ? Auth::guard($data['userGuardRole'])->user()->code : $data['HeaderBuyerCode'] }}">
                <h3>
                    {{ isset($data["lang"]["lang"]["awesome_lets_see_your_result"]) ? $data["lang"]["lang"]["awesome_lets_see_your_result"] :"Awesome! Let`s see your result." }}
                </h3>
                <p>
                    {{ isset($data["lang"]["lang"]["style_analysis_will_be_sent_to_you"]) ? $data["lang"]["lang"]["style_analysis_will_be_sent_to_you"] :"Remember to check your inbox. A detailed personality + style analysis will be sent to you." }}
                </p>
                <div class="form-group">
                    <label>{{ isset($data["lang"]["lang"]["first_name"]) ? $data["lang"]["lang"]["first_name"] :"First Name" }}</label>
                    <input type="text" class="form-control f_name" name="f_name" placeholder="{{ isset($data["lang"]["lang"]["first_name"]) ? $data["lang"]["lang"]["first_name"] :"First Name" }}" value="{{ !empty(Auth::guard($data['userGuardRole'])->user()->f_name) ? Auth::guard($data['userGuardRole'])->user()->f_name : '' }}">
                </div>
                <div class="form-group">
                    <label>{{ isset($data["lang"]["lang"]["phone"]) ? $data["lang"]["lang"]["phone"] :"Phone" }}<span class="important-text">*</span></label>
                    <input type="number" class="form-control phone" name="phone" placeholder="{{ isset($data["lang"]["lang"]["phone"]) ? $data["lang"]["lang"]["phone"] :"Phone" }}" value="{{ !empty(Auth::guard($data['userGuardRole'])->user()->phone) ? Auth::guard($data['userGuardRole'])->user()->phone : '' }}">
                    {{-- 
                    <label>{{ isset($data["lang"]["lang"]["email"]) ? $data["lang"]["lang"]["email"] :"Email" }}<span class="important-text">*</span></label>
                    <input type="text" class="form-control" name="email" value="{{ !empty(Auth::guard($data['userGuardRole'])->user()->email) ? Auth::guard($data['userGuardRole'])->user()->email : '' }}"> 
                    --}}
                </div>
                <div class="form-group" align="center">
                    <a href="#" class="btn -red submit-result set_button set_text">
                        {{ isset($data["lang"]["lang"]["see_my_result"]) ? $data["lang"]["lang"]["see_my_result"] :"See My Result" }}
                    </a>
                </div>
            </div>
        </div>
    </form>


    <!-- <div class="answer_area">

    </div> -->

    <button class="btn -red set_button set_text" id="prev" style="display: none;" disabled>
    	{{ isset($data["lang"]["lang"]["prev"]) ? $data["lang"]["lang"]["prev"] :"Prev" }}
    </button>
    <button class="btn -red set_button set_text" id="next" style="display: none;">
    	{{ isset($data["lang"]["lang"]["next"]) ? $data["lang"]["lang"]["next"] :"Next" }}
    </button>
    <br>
    <br>
    <br>
    <br>
</div>
@endif
@endsection

@section('js')
<script type="text/javascript">
	const progress = document.getElementById('progress')
	const prev = document.getElementById('prev')
	const next = document.getElementById('next')
	const circles = document.querySelectorAll('.circle')
    

	let currentActive = 1


    $('.submit-result').on('click', function(e) {
        e.preventDefault();
        
        var f_name = $('.f_name').val();
        var phone = $('.phone').val();

        if (f_name && phone) {
            $('.quizes').submit();
            toastr.success('{{ isset($data["lang"]["lang"]["quiz_submitted_successfully"]) ? $data["lang"]["lang"]["quiz_submitted_successfully"] :"Quiz Submitted Successfully" }}');
        } else {
            toastr.error('{{ isset($data["lang"]["lang"]["please_fill_in_all_the_info"]) ? $data["lang"]["lang"]["please_fill_in_all_the_info"] :"Please fill in all the information" }}');
        }
    });

	next.addEventListener('click', () => {
	    currentActive++

	    if(currentActive > circles.length){
	        currentActive = circles.length
	    }
	    update()
	})

	prev.addEventListener('click', () => {
	    currentActive--
	    
	    if(currentActive < 1){
	        currentActive = 1
	    }
	    update()
	})

	function update() {
	    circles.forEach((circle, idx)=>{
	        if (idx < currentActive) {
	            circle.classList.add('active')
	        } else {
	            circle.classList.remove('active')
	        }
	    })

	    const actives = document.querySelectorAll('.active')
	    
	    progress.style.width = (actives.length -1) / (circles.length -1) * 100 + '%'

	    if (currentActive === 1) {
	        prev.disabled = true
	        $('#prev').hide()
	    } else if(currentActive === circles.length) {
	        next.disabled = true
	    } else {
	        prev.disabled = false
	        $('#prev').show()
	        next.disabled = false
	    }
	    get_quiz_active()
	}

	function get_quiz_active()
	{
		var ele = $(this);

		var quiz_id = $('.circle.active').filter(':last').data('id');
		$('.answers').hide();
        $('.answers').filter(function(index){ return $(this).data('id') === quiz_id }).show();
	}

    $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function (){
        const authCheck = "{{ Auth::guard('admin')->user()->code ?? Auth::guard('agent')->user()->code ?? Auth::guard('web')->user()->code ?? '' }}";
        const totalQuestions = {{ count($quizes) }};

        $('.answers').on('click', '.answer', function (){
            $('.loading-gif').show();

            const $this = $(this);
            const $group = $this.closest('.answers');

            $group.find('.answer').prop('checked', false);
            $this.prop('checked', true);

            const selectedCount = $('.quizes .answer:checked').length;

            $('#next').click();
            get_quiz_active();

            if(selectedCount === totalQuestions){
                $('.answers').hide();
                $('#prev').hide();

                if(authCheck){
                    // var f_name = $('.f_name').val();
                    // var phone = $('.phone').val();

                    // if (f_name && phone) {
                        $('.quizes').submit();
                        toastr.success('{{ isset($data["lang"]["lang"]["quiz_submitted_successfully"]) ? $data["lang"]["lang"]["quiz_submitted_successfully"] :"Quiz Submitted Successfully" }}');
                    // } else {
                    //     toastr.error('{{ isset($data["lang"]["lang"]["please_fill_in_all_the_info"]) ? $data["lang"]["lang"]["please_fill_in_all_the_info"] :"Please fill in all the information" }}');
                    //     return;
                    // }
                }else{
                    $('.submit_form_data').show();
                }
            }

            $('.loading-gif').hide();
        });
    });

	get_quiz_active();
</script>
@endsection