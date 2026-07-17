@extends('layouts.admin_app')
@section('content')
<div class="form-group container-box">
    <h4>{{ isset($data['backendlang']['backendlang']['User_Details']) ? $data['backendlang']['backendlang']['User_Details'] :'' }}</h4>
    <hr>
    <p>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}: {{ $quiz_record->f_name ?? '-' }}</p>
    <hr>
    {{-- <p>Email: {{ $quiz_record->email }}</p> --}}
    <p>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}: {{ $quiz_record->phone ?? '-' }}</p>
    <hr>
    <p>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}: {{ $quiz_record->created_at ?? '-' }}</p>
</div>
<div class="form-group container-box">
    <h4>{{ isset($data['backendlang']['backendlang']['Quizzes']) ? $data['backendlang']['backendlang']['Quizzes'] :'' }}</h4>
    <hr>
    @foreach($quiz_record->get_quiz_details as $key => $quiz)
        <p>{{ isset($data['backendlang']['backendlang']['Quiz']) ? $data['backendlang']['backendlang']['Quiz'] :'' }} {{ $key+1 }}: {{ $quiz->get_quiz_title->quiz_title }}</p>
        <li>{{ isset($data['backendlang']['backendlang']['Answer']) ? $data['backendlang']['backendlang']['Answer'] :'' }}: {{ $quiz->get_quiz_det->answer ?? '-' }}</li>
        <li>{{ isset($data['backendlang']['backendlang']['Suggestion']) ? $data['backendlang']['backendlang']['Suggestion'] :'' }}: {{ $quiz->get_quiz_det->suggestion ?? '-' }}</li>
        <hr>
    @endforeach
</div>

<div class="submit-form-btn">
    <div class="form-group wizard-actions" align="right">
        <a href="{{ url()->previous() }}" class="btn btn-outline-danger">
            <i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Back_To_List']) ? $data['backendlang']['backendlang']['Back_To_List'] :'' }}</i>
        </a>
    </div>
</div>
@endsection