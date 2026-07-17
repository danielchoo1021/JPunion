@extends('layouts.admin_app')
@section('css')
<style type="text/css">
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
@endsection
@section('content')
<form method="POST" action="{{ route('save_setting_auto_withdrawal') }}" id='setting-merchant-form'>
    @csrf
    <div class="form-group container-box">

        <div class="form-group">
            <div class="row">
                <div class="col-3">
                    <h4>{{ isset($data['backendlang']['backendlang']['Auto_Withdrawal_Enable']) ? $data['backendlang']['backendlang']['Auto_Withdrawal_Enable'] :'' }}</h4>
                </div>
                <div class="col-1" align="left">
                    <label class="switch">
                        <input type="checkbox" name="auto_withdrawal_enable" {{ (!empty($setting->id) && $setting->auto_withdrawal_enable == 1) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <label style="font-size:16px">{{ isset($data['backendlang']['backendlang']['Withdrawal_Date']) ? $data['backendlang']['backendlang']['Withdrawal_Date'] :'' }} 1</label>
                    <select name="day_only[]" class="form-control" style="background-color: transparent;">
                        <option value="">{{ isset($data['backendlang']['backendlang']['Select_Day_For_Withdrawal']) ? $data['backendlang']['backendlang']['Select_Day_For_Withdrawal'] :'' }}</option>
                        @for ($i = 1; $i <= 31; $i++)
                            @php $dayFormatted=str_pad($i, 2, '0' , STR_PAD_LEFT); @endphp
                            <option value="{{ $dayFormatted }}" {{ (!empty($setting->auto_withdrawal_day) && $setting->auto_withdrawal_day == $i) ? 'selected' : '' }}>
                            {{ $i }}
                            </option>
                            @endfor
                    </select>
                </div>
                <div class="col-12 mt-4">
                    <label style="font-size:16px">{{ isset($data['backendlang']['backendlang']['Withdrawal_Date']) ? $data['backendlang']['backendlang']['Withdrawal_Date'] :'' }} 2</label>
                    <select name="day_only[]" class="form-control" style="background-color: transparent;">
                        <option value="">{{ isset($data['backendlang']['backendlang']['Select_Day_For_Withdrawal']) ? $data['backendlang']['backendlang']['Select_Day_For_Withdrawal'] :'' }}</option>
                        @for ($i = 1; $i <= 31; $i++)
                            @php $dayFormatted=str_pad($i, 2, '0' , STR_PAD_LEFT); @endphp
                            <option value="{{ $dayFormatted }}" {{ (!empty($setting->auto_withdrawal_day_2) && $setting->auto_withdrawal_day_2 == $i) ? 'selected' : '' }}>
                            {{ $i }}
                            </option>
                            @endfor
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group container-box mt-4">
        <div class="form-group">
            <div class="row">
                <div class="col-12">
                    <h4>{{ isset($data['backendlang']['backendlang']['Minimum_Withdrawal_Amount']) ? $data['backendlang']['backendlang']['Minimum_Withdrawal_Amount'] :'' }}</h4>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <label for="min_withdrawal_amount" class="form-label">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }} (MYR)</label>
                    <input type="number" min="0" id="min_withdrawal_amount" name="min_withdrawal_amount" class="form-control" value="{{ !empty($setting->min_withdrawal_amount) ? $setting->min_withdrawal_amount : old('min_withdrawal_amount') }}" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] : '' }} 100">
                </div>
            </div>
        </div>
    </div>
</form>
<div class="submit-form-btn">
    <div class="form-group wizard-actions" align="right">
        <button class="btn btn-outline-primary">
            <i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
        </button>

    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $('.submit-form-btn .btn-outline-primary').click(function(e) {
        e.preventDefault();

        $('#setting-merchant-form').submit();
    });
</script>
@endsection