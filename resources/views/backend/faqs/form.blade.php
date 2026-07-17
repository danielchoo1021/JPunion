<div class="container-box form-group mb-5 pb-5">
	<div class="row">
		<div class="col-12">
			<div class="form-group">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>{{ isset($data['backendlang']['backendlang']['Type']) ? $data['backendlang']['backendlang']['Type'] :'' }} <span class="important-text">*</span></label>
							<select class="form-control" name="type">
								@foreach($faqs_desctiption as $key => $description)
								<option {{ (isset($faq) && $faq->type == $key) ? 'selected' : '' }} value="{{ $key }}">
									{{ $description }}
								</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-12 pt-3">
						<div class="form-group">
							<label>{{ isset($data['backendlang']['backendlang']['Question(EN)']) ? $data['backendlang']['backendlang']['Question(EN)'] :'' }}  <span class="important-text">*</span></label>
							<input type="text" class="form-control" name="question" value="{{ isset($faq) ? $faq->question : old('question') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Question(EN)']) ? $data['backendlang']['backendlang']['Question(EN)'] :'' }}">
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>{{ isset($data['backendlang']['backendlang']['Question(CN)']) ? $data['backendlang']['backendlang']['Question(CN)'] :'' }} <span class="important-text">*</span></label>
							<input type="text" class="form-control" name="question_cn" value="{{ isset($faq) ? $faq->question_cn : old('question_cn') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Question(CN)']) ? $data['backendlang']['backendlang']['Question(CN)'] :'' }}">
						</div>
					</div>
					<div class="col-md-12 pt-3">
						<div class="form-group">
							<label>{{ isset($data['backendlang']['backendlang']['Answer(EN)']) ? $data['backendlang']['backendlang']['Answer(EN)'] :'' }} <span class="important-text">*</span></label>
							<textarea class="form-control" name="answer" placeholder="{{ isset($data['backendlang']['backendlang']['Answer(EN)']) ? $data['backendlang']['backendlang']['Answer(EN)'] :'' }}">{!! isset($faq) ? $faq->answer : old('answer') !!}</textarea>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>{{ isset($data['backendlang']['backendlang']['Answer(CN)']) ? $data['backendlang']['backendlang']['Answer(CN)'] :'' }} <span class="important-text">*</span></label>
							<textarea class="form-control" name="answer_cn" placeholder="{{ isset($data['backendlang']['backendlang']['Answer(CN)']) ? $data['backendlang']['backendlang']['Answer(CN)'] :'' }}">{!! isset($faq) ? $faq->answer_cn : old('answer_cn') !!}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

