<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use App\Quiz;
use App\QuizDetail;
use App\QuizRecord;
use App\QuizRecordDetail;

use Validator, Redirect, Toastr, DB, File, Auth;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizs = Quiz::where('status', '!=', '3')->orderBy('created_at', 'desc');
        $queries = [];
        $columns = [
            'quiz_title', 'status'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                
                $quizs = $quizs->where($column, 'like', "%".request($column)."%");

                $queries[$column] = request($column);
            }
        }

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $quizs = $quizs->paginate($per_page)->appends($queries);
        return view('backend.quizs.index', ['quizs'=>$quizs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.quizs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $validator = Validator::make($request->all(), [
            'quiz_title' => ['required'],
            'quiz_title_cn' => ['required'],
        ]);
    
        if($validator->fails()){
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        try{
            \DB::beginTransaction();

            $quiz = new Quiz();
            $quiz->quiz_title = $request->quiz_title;
            $quiz->quiz_title_cn = $request->quiz_title_cn;
            $quiz->save();

            $answers = $request->input('answer');
    
            $answers = array_filter($answers, function($answer){
                return !empty($answer['answer']);
            });
            
            if(count($answers) < 2){
                throw new \Exception('Please key in at least 2 answers.');
            }

            foreach ($answers as $key => $answer){
                if(isset($answer['id'])){
                    $quizDetail = QuizDetail::find($answer['id']);
                    if($quizDetail){
                        $quizDetail->answer = $answer['answer'];
                        $quizDetail->suggestion = $request->input("suggestion.$key.suggestion");
                        $quizDetail->answer_cn = $request->input("answer_cn.$key.answer_cn");
                        $quizDetail->suggestion_cn = $request->input("suggestion_cn.$key.suggestion_cn");
                        $quizDetail->save();
                    }
                }else{
                    $quizDetail = new QuizDetail();
                    $quizDetail->quiz_id = $quiz->id;
                    $quizDetail->answer = $answer['answer'];
                    $quizDetail->suggestion = $request->input("suggestion.$key.suggestion");
                    $quizDetail->answer_cn = $request->input("answer_cn.$key.answer_cn");
                    $quizDetail->suggestion_cn = $request->input("suggestion_cn.$key.suggestion_cn");
                    $quizDetail->save();
                }
            }

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all)->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all)->withErrors($e->getMessage());
        }
        

        Toastr::success($translation_data['backendlang']['backendlang']['Create_Successfully'] ?? "Create Successfully");
        return redirect()->route('quiz.quizs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quiz = Quiz::find($id);
        $quiz_details = QuizDetail::where('quiz_id', $id)->get();

        return view('backend.quizs.edit', ['quiz'=>$quiz,
                                           'quiz_details'=>$quiz_details]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $translation_data = GlobalController::get_translations();
        $validator = Validator::make($request->all(), [
            'quiz_title' => ['required'],
            'quiz_title_cn' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        try {
            \DB::beginTransaction();

            $quiz = Quiz::find($id);
            if(!$quiz){
                throw new \Exception( $translation_data['backendlang']['backendlang']['Quiz not found.'] ?? 'Quiz not found.');
            }

            $quiz->quiz_title = $request->quiz_title;
            $quiz->quiz_title_cn = $request->quiz_title_cn;
            $quiz->save();

            $answers = $request->input('answer');

            $answers = array_filter($answers, function($answer){
                return !empty($answer['answer']);
            });

            if(count($answers) < 2){
                throw new \Exception($translation_data['backendlang']['backendlang']['Please key in at least 2 answers.']  ?? 'Please key in at least 2 answers.');
            }

            foreach($answers as $key => $answer){
                if(isset($answer['id'])){
                    $quizDetail = QuizDetail::find($answer['id']);
                    if ($quizDetail) {
                        $quizDetail->answer = $answer['answer'];
                        $quizDetail->suggestion = $request->input("suggestion.$key.suggestion");
                        $quizDetail->answer_cn = $request->input("answer_cn.$key.answer_cn");
                        $quizDetail->suggestion_cn = $request->input("suggestion_cn.$key.suggestion_cn");
                        $quizDetail->save();
                    }
                }else{
                    $quizDetail = new QuizDetail();
                    $quizDetail->quiz_id = $quiz->id;
                    $quizDetail->answer = $answer['answer'];
                    $quizDetail->suggestion = $request->input("suggestion.$key.suggestion");
                    $quizDetail->answer_cn = $request->input("answer_cn.$key.answer_cn");
                    $quizDetail->suggestion_cn = $request->input("suggestion_cn.$key.suggestion_cn");
                    $quizDetail->save();
                }
            }

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all)->withErrors($e->getMessage());
        }catch (\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all)->withErrors($e->getMessage());
        }

       Toastr::success( $translation_data['backendlang']['backendlang']['Quiz updated successfully!'] ?? 'Quiz updated successfully!');
        return redirect()->route('quiz.quizs.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function quiz_records_index()
    {
        $records = QuizRecord::where('status', '!=', '3')->orderBy('created_at', 'desc');
        
        $queries = [];
        $columns = [
            'code', 'phone', 'code_desc', 'code_asc', 'f_name', 'f_name_desc', 'f_name_asc', 'email', 'email_desc', 'email_asc', 'status', 'status_asc', 'status_desc', 'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'per_page'){
                    $records = $records->paginate($per_page);
                }elseif($column == 'code'){
                    $records = $records->where('quiz_records.code', 'like', "%".request($column)."%");
                }elseif($column == 'phone'){
                    $records = $records->where('quiz_records.phone', 'like', "%".request($column)."%");
                }elseif($column == 'code_desc'){
                    $records = $records->orderBy('quiz_records.code', 'desc');
                }elseif($column == 'code_asc'){
                    $records = $records->orderBy('quiz_records.code', 'asc');
                }elseif($column == 'f_name'){
                    $records = $records->where('quiz_records.f_name', 'like', "%".request($column)."%");
                }elseif($column == 'f_name_desc'){
                    $records = $records->orderBy('quiz_records.f_name', 'desc');
                }elseif($column == 'f_name_asc'){
                    $records = $records->orderBy('quiz_records.f_name', 'asc');
                }elseif($column == 'email'){
                    $records = $records->where('quiz_records.email', 'like', "%".request($column)."%");
                }elseif($column == 'email_desc'){
                    $records = $records->orderBy('quiz_records.email', 'desc');
                }elseif($column == 'email_asc'){
                    $records = $records->orderBy('quiz_records.email', 'asc');
                }elseif($column == 'status'){
                    $records = $records->where('quiz_records.status', 'like', "%".request($column)."%");
                }elseif($column == 'status_desc'){
                    $records = $records->orderBy('quiz_records.status', 'desc');
                }elseif($column == 'status_asc'){
                    $records = $records->orderBy('quiz_records.status', 'asc');
                }else{
                    $records = $records->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }

        if(!empty(request('per_page'))){
            $records = $records->appends($queries);        
        }else{
            $records = $records->paginate($per_page)->appends($queries);
        }

        return view('backend.quizs.quiz_records_index', ['records'=>$records]);
    }

    public function quiz_records_view($id)
    {
        $quiz_record = QuizRecord::find($id);

        return view('backend.quizs.quiz_records_view', ['quiz_record'=>$quiz_record]);
    }
}
