@extends('layouts.admin_app')
@section('content')
<form method="POST" action="{{ route('save_setting_colour') }}" id="setting-merchant-form">
	@csrf

	 <div class="container-box">
     <h2 class="section-header">Header Design</h2>
       <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>{{ isset($data['backendlang']['backendlang']['Header_Announcement_Background_Colour']) ? $data['backendlang']['backendlang']['Header_Announcement_Background_Colour'] :'' }}</b></label>
                        <input type="text" name="header_announcement_background_colour" class="form-control" value="{{ isset($select) ? $select->header_announcement_background_colour : old('header_announcement_background_colour') }}">
                    </div>
                    <div class="color-preview" data-target="header_announcement_background_colour"></div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>{{ isset($data['backendlang']['backendlang']['Header_Announcement_Text_Colour']) ? $data['backendlang']['backendlang']['Header_Announcement_Text_Colour'] :'' }}</b></label>
                        <input type="text" name="header_announcement_text_colour" class="form-control" value="{{ isset($select) ? $select->header_announcement_text_colour : old('header_announcement_text_colour') }}">
                    </div>
                    <div class="color-preview" data-target="header_announcement_text_colour"></div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>{{ isset($data['backendlang']['backendlang']['Header_Background_Colour']) ? $data['backendlang']['backendlang']['Header_Background_Colour'] :'' }}</b></label>
                        <input type="text" name="header_background_colour" class="form-control" value="{{ isset($select) ? $select->header_background_colour : old('header_background_colour') }}">
                    </div>
                    <div class="color-preview" data-target="header_background_colour"></div>
                </div>
                <div class="col-md-4 mt-4">
                    <div class="form-group">
                        <label><b>{{ isset($data['backendlang']['backendlang']['Header_Text_Colour']) ? $data['backendlang']['backendlang']['Header_Text_Colour'] :'' }}</b></label>
                        <input type="text" name="header_text_colour" class="form-control" value="{{ isset($select) ? $select->header_text_colour : old('header_text_colour') }}">
                    </div>
                    <div class="color-preview" data-target="header_text_colour"></div>
                </div>
                <div class="col-md-4 mt-4">
                    <div class="form-group">
                        <label><b>{{ isset($data['backendlang']['backendlang']['Header_Text_Hover_Colour']) ? $data['backendlang']['backendlang']['Header_Text_Hover_Colour'] :'' }}</b></label>
                        <input type="text" name="header_text_hover_colour" class="form-control" value="{{ isset($select) ? $select->header_text_hover_colour : old('header_text_hover_colour') }}">
                    </div>
                    <div class="color-preview" data-target="header_text_hover_colour"></div>
                </div>      
            </div>
        </div>
    </div>

    <br>

    <div class="container-box">
    <h2 class="section-header">Body Design</h2>

	    <div class="form-group">
		    <div class="row">
                <div class="col-md-4">
					<div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['Button_Background_Colour']) ? $data['backendlang']['backendlang']['Button_Background_Colour'] :'' }}</b></label>
						<input type="text" name="button_colour" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Button_Colour']) ? $data['backendlang']['backendlang']['Button_Colour'] :'' }}" value="{{ isset($select) ? $select->button_colour : old('button_colour') }}">
					</div>
                    <div class="color-preview" data-target="button_colour"></div>
                </div>	
				 <div class="col-md-4">
					<div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['Button_Text_Colour']) ? $data['backendlang']['backendlang']['Button_Text_Colour'] :'' }}</b></label>
						<input type="text" name="text_colour" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Text_Colour']) ? $data['backendlang']['backendlang']['Text_Colour'] :'' }}" value="{{ isset($select) ? $select->text_colour : old('text_colour') }}">
                    </div>
                    <div class="color-preview" data-target="text_colour"></div>
				</div>
                <div class="col-md-4">
					<div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['Button_Text_Hover_Colour']) ? $data['backendlang']['backendlang']['Button_Text_Hover_Colour'] :'' }}</b></label>
						<input type="text" name="hover_colour" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Hover_Colour']) ? $data['backendlang']['backendlang']['Hover_Colour'] :'' }}" value="{{ isset($select) ? $select->hover_colour : old('hover_colour') }}">
                    </div>
                    <div class="color-preview" data-target="hover_colour"></div>
				</div>
			</div>
		</div>
	</div>

    <br>

    <div class="container-box">
    <h2 class="section-header">Footer Design</h2>
       <div class="form-group">
            <div class="row"><div class="col-md-4">
                    <div class="form-group">
                        <label><b>{{ isset($data['backendlang']['backendlang']['Footer_Trademark_Background_Colour']) ? $data['backendlang']['backendlang']['Footer_Trademark_Background_Colour'] :'' }}</b></label>
                        <input type="text" name="footer_trademark_background_colour" class="form-control" value="{{ isset($select) ? $select->footer_trademark_background_colour : old('footer_trademark_background_colour') }}">
                    </div>
                    <div class="color-preview" data-target="footer_trademark_background_colour"></div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>{{ isset($data['backendlang']['backendlang']['Footer_Trademark_Text_Colour']) ? $data['backendlang']['backendlang']['Footer_Trademark_Text_Colour'] :'' }}</b></label>
                        <input type="text" name="footer_trademark_text_colour" class="form-control" value="{{ isset($select) ? $select->footer_trademark_text_colour : old('footer_trademark_text_colour') }}">
                    </div>
                    <div class="color-preview" data-target="footer_trademark_text_colour"></div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>{{ isset($data['backendlang']['backendlang']['Footer_Background_Colour']) ? $data['backendlang']['backendlang']['Footer_Background_Colour'] :'' }}</b></label>
                        <input type="text" name="footer_background_colour" class="form-control" value="{{ isset($select) ? $select->footer_background_colour : old('footer_background_colour') }}">
                    </div>
                    <div class="color-preview" data-target="footer_background_colour"></div>
                </div>
                <div class="col-md-4 mt-4">
                    <div class="form-group">
                        <label><b>{{ isset($data['backendlang']['backendlang']['Footer_Text_Colour']) ? $data['backendlang']['backendlang']['Footer_Text_Colour'] :'' }}</b></label>
                        <input type="text" name="footer_text_colour" class="form-control" value="{{ isset($select) ? $select->footer_text_colour : old('footer_text_colour') }}">
                    </div>
                    <div class="color-preview" data-target="footer_text_colour"></div>
                </div>
                <div class="col-md-4 mt-4">
                    <div class="form-group">
                        <label><b>{{ isset($data['backendlang']['backendlang']['Footer_Text_Hover_Colour']) ? $data['backendlang']['backendlang']['Footer_Text_Hover_Colour'] :'' }}</b></label>
                        <input type="text" name="footer_text_hover_colour" class="form-control" value="{{ isset($select) ? $select->footer_text_hover_colour : old('footer_text_hover_colour') }}">
                    </div>
                    <div class="color-preview" data-target="footer_text_hover_colour"></div>
                </div>
            </div>
        </div>
    </div>

</form>

 <div class="submit-form-btn">

     <div class="form-group wizard-actions" align="right">
	    <button class="btn btn-outline-primary">
		<i class="bi bi-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
	    </button>
    </div>
</div>

@endsection

{{-- CSS --}}
@section('css')
<style>
.color-preview {
    width: 36px;
    height: 36px;
    margin-top: 6px;
    border-radius: 6px;
    border: 1px solid #ddd;
    background-color: transparent;
}
</style>
@endsection

{{-- JS --}}
@section('js')
<script>
function updateColorPreview(input) {
    let color = input.value.trim();
    let target = input.name;

    if (!color) return;

    if (!color.startsWith('#')) {
        color = '#' + color;
    }

    const box = document.querySelector('[data-target="'+target+'"]');
    if (box) {
        box.style.backgroundColor = color;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[name$="_colour"]').forEach(input => {

        // show saved color on load
        updateColorPreview(input);

        // LIVE update while typing
        input.addEventListener('input', function () {
            updateColorPreview(this);
        });
    });
});

// Submit form
$('.submit-form-btn .btn-outline-primary').click(function(e){
    e.preventDefault();
    $('#setting-merchant-form').submit();
});
</script>
@endsection
