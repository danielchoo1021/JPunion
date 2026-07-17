@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
    <div class="row">
        <div class="col-12">
            <h5 class="mb-0" style="display: inline-block;">{{ isset($data['backendlang']['backendlang']['Edit_Variation']) ? $data['backendlang']['backendlang']['Edit_Variation'] :'' }}</h5>
        </div>
    </div>
    <hr>
    <br>
    <form method="POST" action="{{ route('save_edit_variation',$product->id) }}" id="edit-variation-form">
    @csrf
        <div class="row">
            <div class="col-3">
                <div class="form-group" align="right">
                    <b>{{ isset($data['backendlang']['backendlang']['Variation']) ? $data['backendlang']['backendlang']['Variation'] :'' }} 1</b>
                </div>
            </div>
            @php
            $v1_num = 0;
            $v2_num = 0;
            @endphp
            <div class="col-6">
                <div class="form-group">
                    <input type="text" class="form-control" name="variation_title" placeholder="{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}"
                        value="{{ (isset($product)) ? $product->variation_title : old('variation_title') }}">
                </div>
                <div class="variation-parent-row">
                    @if(isset($variations) && !$variations->isEmpty())
                    @foreach($variations as $variation)
                    <div class="form-group variation-child-row">
                        <div class="row">
                            <div class="col-10">
                                <input type="text" class="form-control variation_option" name="variation_option[]" placeholder="{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}" data-id="{{ $v1_num }}" value="{{ $variation->variation_name }}">
                            </div>
                            <div class="col-2" align="center">
                                <a href="#" class="del-v1-btn" data-id="{{ $variation->id }}">
                                    <i class="bi bi-trash" style="font-size: 25px;"></i>
                                </a>
                            </div>
                        </div>
                        <input type="hidden" name="fvid[]" value="{{ $variation->id }}">
                    </div>
                    @php
                    $v1_num++;
                    @endphp
                    @endforeach
                    @else
                    <div class="form-group variation-child-row">
                        <div class="row">
                            <div class="col-10">
                                <input type="text" class="form-control variation_option" name="variation_option[]" placeholder="{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}" data-id="{{ $v1_num }}">
                            </div>
                            <div class="col-2" align="center">
                                <a href="#" class="del-v1-btn">
                                    <i class="bi bi-trash" style="font-size: 25px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <input type="hidden" name="total_v1_variation" class="total_v1_variation" value="{{ $v1_num }}">
                <div class="form-group">
                    <a href="#" class="btn btn-outline-primary btn-block add-v1-option">
                        <i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add_Option']) ? $data['backendlang']['backendlang']['Add_Option'] :'' }}
                    </a>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-3">
                <div class="form-group" align="right">
                    <b>{{ isset($data['backendlang']['backendlang']['Variation']) ? $data['backendlang']['backendlang']['Variation'] :'' }} 2</b>
                </div>
            </div>
            <div class="col-6">
                <div class="hide_variation_two_area">
                    <a href="#" class="btn btn-outline-primary btn-block">
                        <i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add']) ? $data['backendlang']['backendlang']['Add'] :'' }}
                    </a>
                </div>
                <input type="hidden" name="variation_two_enable" value='0'>
                <div class="variation_two_area" style="display: none;">
                    <div class="form-group" align="right">
                        <a href="#" class="close-variation-two">
                            <i class="bi bi-x"></i>
                        </a>
                    </div>
                    <div class="form-group">

                        <input type="text" class="form-control" name="variation_two_title" placeholder="{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}"
                            value="{{ (isset($product)) ? $product->second_variation_title : old('variation_two_title') }}">
                    </div>
                    <div class="variation-parent-row">
                        @if(isset($svs) && !$svs->isEmpty())
                        @foreach($svs as $sv)
                        <div class="form-group variation-child-row">
                            <div class="row">
                                <div class="col-10">
                                    <input type="text" class="form-control second_variation_option second_variation_option_{{ $v2_num }}" name="variation_two_option[]" placeholder="{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}" data-id="{{ $v2_num }}" value="{{ $sv->variation_name }}">
                                </div>
                                <div class="col-2" align="center">
                                    <a href="#" class="del-v2-btn" data-id="{{ $sv->id }}" data-variation="{{ $sv->variation_id }}" data-name="{{ $sv->variation_name }}">
                                        <i class="bi bi-trash" style="font-size: 25px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @php
                        $v2_num++;
                        @endphp
                        @endforeach
                        @else
                        <div class="form-group variation-child-row">
                            <div class="row">
                                <div class="col-10">
                                    <input type="text" class="form-control second_variation_option second_variation_option_{{ $v2_num }}" name="variation_two_option[]" placeholder="{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}" data-id="{{ $v2_num }}">
                                </div>
                                <div class="col-2" align="center">
                                    <a href="#" class="del-v2-btn">
                                        <i class="bi bi-trash" style="font-size: 25px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <input type="hidden" name="total_v2_variation" class="total_v2_variation" value="{{ $v2_num }}">
                    <div class="form-group">
                        <a href="#" class="btn btn-outline-primary btn-block add-v2-option">
                            <i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add_Option']) ? $data['backendlang']['backendlang']['Add_Option'] :'' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="submit-form-btn">
        <div class="form-group wizard-actions" align="right">
            <a href="{{ route('product.products.edit', $product->id) }}" class="btn btn-outline-danger">
                <i class="fa fa-ban">{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
            </a>

            <button class="btn btn-outline-primary">
                <i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
            </button>

        </div>
    </div>
</div>
@endsection


@section('js')
<script type="text/javascript">

    $('.submit-form-btn .btn-outline-primary').click( function(e){
      e.preventDefault(); 

      $('#edit-variation-form').submit();
    });

    $('.variation-parent-row').on("click", ".del-v1-btn", function(e) {
        e.preventDefault();

        var ele = $(this);
        var data_id = ele.data('id');
        var product_id = '{{ $product->id }}';
        var row_id = ele.closest('.variation-child-row').find('.variation_option').data('id');

        if (data_id) {
            if (confirm("{{ isset($data['backendlang']['backendlang']['Confirm_Delete_This_Variation']) ? $data['backendlang']['backendlang']['Confirm_Delete_This_Variation'] :'' }}") == true) {
                $('.loading-gif').show();
                var fd = new FormData();
                fd.append('data_id', data_id);
                fd.append('product_id', product_id);

                $.ajax({
                    url: '{{ route("deleteVariation") }}',
                    type: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('.loading-gif').hide();
                        location.reload();
                    },
                });
            }
        } else {
            ele.closest('.variation-child-row').remove();

            $('.variation-list-child-row').find('.variation_option_display_' + row_id).parent().remove();
            $('.variation-list-child-row').find('.added').filter(function() {
                return $(this).data('id') == row_id
            }).remove();
        }
    });

    $('.variation-parent-row').on("click", ".del-v2-btn", function(e) {
        e.preventDefault();

        var ele = $(this);
        var data_id = ele.data('id');
        var variation_id = ele.data('variation');
        var variation_name = ele.data('name');
        var product_id = '{{ $product->id }}';

        var row_id = ele.closest('.variation-child-row').find('.second_variation_option').data('id');

        var fd = new FormData();
        fd.append('data_id', data_id);
        fd.append('variation_id', variation_id);
        fd.append('product_id', product_id);
        fd.append('variation_name', variation_name);

        if (data_id) {
            if (confirm("{{ isset($data['backendlang']['backendlang']['Confirm_Delete_This_Variation']) ? $data['backendlang']['backendlang']['Confirm_Delete_This_Variation'] :'' }}") == true) {
                $('.loading-gif').show();
                $.ajax({
                    url: '{{ route("deleteSecondVariation") }}',
                    type: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('.loading-gif').hide();
                        location.reload();
                    },
                });
            }
        } else {
            ele.closest('.variation-child-row').remove();

            $('.variation-list-child-row').find('.added-v2-option_' + row_id).remove();
            $('.variation-list-child-row').find('.first_variation').attr('rowspan', function(i, rs) {
                return rs - 1;
            });
        }
    });

    $('.add-v1-option').click(function(e) {
        e.preventDefault();
        var ele = $(this);
        var total = $('.variation_option').length;

        var v2_enable = $('input[name="variation_two_enable"]').val();

        ele.closest('.row').find('.variation-parent-row').append('<div class="form-group variation-child-row">\
                                                                    <div class="row">\
                                                                        <div class="col-10"> \
                                                                            <input type="text" class="form-control variation_option" name="variation_option[]" placeholder="{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}" data-id="' + total + '">\
                                                                        </div>\
                                                                        <div class="col-2" align="center">\
                                                                            <a href="#" class="del-v1-btn">\
                                                                                <i class="bi bi-trash" style="font-size: 25px;"></i>\
                                                                            </a>\
                                                                        </div>\
                                                                    </div>\
                                                                </div>');
        if (v2_enable == 1) {
            var display = "";
        } else {
            var display = "display: none;";
        }
    });

    $('.variation-parent-row').on('keyup', '.variation_option', function(e) {
        e.preventDefault();

        var ele = $(this);
        var num = ele.data('id');

        $('.variation_option_display_' + num + '').html(ele.val());
    });

    $('.variation-parent-row').on('keyup', '.second_variation_option', function(e) {
        e.preventDefault();

        var ele = $(this);
        var num = ele.data('id');

        $('.variation_option_two_display_' + num + '').find('span').html(ele.val());
        $('.variation_option_two_value_' + num + '').val(ele.val());
    });

    $('input[name="variation_title"]').keyup(function() {
        var ele = $(this);

        $('.variation_title').html(ele.val());
    });

    $('input[name="variation_two_title"]').keyup(function() {
        var ele = $(this);

        $('.variation_two_title').html(ele.val());
    });

    $('.hide_variation_two_area .btn').click(function(e) {
        e.preventDefault();

        var ele = $(this);

        $('.variation_two_area').show();
        $('.hide_variation_two_area').hide();

        $('.variation_two').show();

        $('input[name="variation_two_enable"]').val(1);

        // $('.first_variation').closest('tr').find('input[type="text"]').each(function(){
        //     var name = $(this).attr('name');
        //     $(this).attr('name', name+'[]');
        // });
    });

    $('.close-variation-two').click(function(e) {
        e.preventDefault();

        var ele = $(this);

        $('.variation_two_area').hide();
        $('.hide_variation_two_area').show();
        $('.variation_two').hide();

        $('input[name="variation_two_enable"]').val(0);

        // $('.first_variation').closest('tr').find('input[type="text"]').each(function(){
        //     var name = $(this).attr('name');
        //     $(this).attr('name', name+'[]');
        // });
    });

    $('.add-v2-option').click(function(e) {
        e.preventDefault();

        var checkSecondVariation = '{{ $product->second_variation_enable }}';
        var ele = $(this);
        var total = $('.second_variation_option').length;
        if (checkSecondVariation == 1) {
            var rowspantotal = parseFloat(total) + 1;
        } else {
            var rowspantotal = total;
        }
        // alert(total);
        

        ele.closest('.row').find('.variation-parent-row').append('<div class="form-group variation-child-row">\
                                                                    <div class="row">\
                                                                        <div class="col-10"> \
                                                                           <input type="text" class="form-control second_variation_option second_variation_option_' + total + '" name="variation_two_option[]" placeholder="{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}" data-id="' + total + '">\
                                                                        </div>\
                                                                        <div class="col-2" align="center">\
                                                                            <a href="#" class="del-v2-btn">\
                                                                                <i class="bi bi-trash" style="font-size: 25px;"></i>\
                                                                            </a>\
                                                                        </div>\
                                                                    </div>\
                                                                 </div>');
    });

    var second_enabled = '{{ $product->second_variation_enable }}';
    if (second_enabled == 1) {
        $('.hide_variation_two_area .btn').click();
    }

    var second_enabled = '{{ $product->second_variation_enable }}';
    if (second_enabled == 1) {
        $('.hide_variation_two_area .btn').click();
    }
</script>
@endsection