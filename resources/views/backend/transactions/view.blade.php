@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif

<!-- <div class="form-group">
    <a href="#" class="btn btn-warning">
        <i class="fa fa-print"></i> 打印
    </a>
</div> -->

<div class="form-group container-box">
    <div class="row">
        <div class="col-6">
           {{ isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'' }}: <b>#{{ $transaction->transaction_no }}</b>
            @if(isset($transaction->te_einvoice) && !empty($transaction->te_einvoice))
              <br>
             {{ isset($data['backendlang']['backendlang']['EInvoice_No']) ? $data['backendlang']['backendlang']['EInvoice_No'] :'' }}: <b>{{ $transaction->te_einvoice }}</b>
            @endif
            <br>
            {{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: <b>{{ $transaction->created_at }}</b>
            <br>
             {{ isset($data['backendlang']['backendlang']['Payment_Method']) ? $data['backendlang']['backendlang']['Payment_Method'] :'' }}: 
            @if(!empty($transaction->get_payment_gateway_setting->id))
                <b>{{ $transaction->get_payment_gateway_setting->name }}</b>
            @elseif($transaction->mall == 1)
                <b> {{ isset($data['backendlang']['backendlang']['Cash_Wallet']) ? $data['backendlang']['backendlang']['Cash_Wallet'] :'' }}</b>
            @elseif($transaction->mall == 2)
                <b> {{ isset($data['backendlang']['backendlang']['Topup_Wallet']) ? $data['backendlang']['backendlang']['Topup_Wallet'] :'' }}</b>
            @elseif(!empty($transaction->bank_id))
                <b> {{ isset($data['backendlang']['backendlang']['Online_Banking']) ? $data['backendlang']['backendlang']['Online_Banking'] :'' }}</b>
            @elseif(!empty($transaction->bank_slip))
                @if(!empty($transaction->created_backend == 1))
                <b>{{ isset($data['backendlang']['backendlang']['Bank_Transfer_Create_From_Backend']) ? $data['backendlang']['backendlang']['Bank_Transfer_Create_From_Backend'] :'' }}</b>
                @else
                <b>{{ isset($data['backendlang']['backendlang']['Bank_Transfer']) ? $data['backendlang']['backendlang']['Bank_Transfer'] :'' }}</b>
                @endif
            @elseif(!empty($transaction->pv_purchase))
                <b>{{ isset($data['backendlang']['backendlang']['Point_Wallet']) ? $data['backendlang']['backendlang']['Point_Wallet'] :'' }}</b>
            @elseif(!empty($transaction->payment_method == 1))
                <b>{{ isset($data['backendlang']['backendlang']['POS_Cash']) ? $data['backendlang']['backendlang']['POS_Cash'] :'' }}</b>
            @elseif(!empty($transaction->payment_method == 2))
                <b>{{ isset($data['backendlang']['backendlang']['POS_QR_Code']) ? $data['backendlang']['backendlang']['POS_QR_Code'] :'' }}</b>
            @elseif(!empty($transaction->payment_method == 3))
                <b>{{ isset($data['backendlang']['backendlang']['POS_Credit_Card_Debit_Card']) ? $data['backendlang']['backendlang']['POS_Credit_Card_Debit_Card'] :'' }}</b>
            @elseif(!empty($transaction->created_backend == 1))
                <b>{{ isset($data['backendlang']['backendlang']['Create_From_Backend_No_Bank_Slip']) ? $data['backendlang']['backendlang']['Create_From_Backend_No_Bank_Slip'] :'' }}</b>
            @endif
            @if(!empty($transaction->payment_method) && !empty($transaction->reference_number))
                @if(!empty($transaction->get_bank))
                <br>
                {{ isset($data['backendlang']['backendlang']['Bank']) ? $data['backendlang']['backendlang']['Bank'] :'' }}: <b>{{ $transaction->get_bank->bank_name }}</b>
                @endif
                @if(!empty($transaction->qr_paylist))
                <br>
                {{ isset($data['backendlang']['backendlang']['QR_Type']) ? $data['backendlang']['backendlang']['QR_Type'] :'' }}: <b>{{ $transaction->qr_paylist->title }}</b>
                @endif
                <br>
                {{ isset($data['backendlang']['backendlang']['Reference_Number']) ? $data['backendlang']['backendlang']['Reference_Number'] :'' }}: <b>{{ $transaction->reference_number }}</b>
            @endif
            <br>
            {{ isset($data['backendlang']['backendlang']['pickup_method']) ? $data['backendlang']['backendlang']['pickup_method'] :'取货方式'}}: 
            @if(!empty($transaction->self_pick) && $transaction->self_pick == 1)
                <b>{{ isset($data['backendlang']['backendlang']['self_pickup']) ? $data['backendlang']['backendlang']['self_pickup'] :'自提'}}</b>
            @elseif(!empty($transaction->cod_address))
                <b>{{ isset($data['backendlang']['backendlang']['self_pickup']) ? $data['backendlang']['backendlang']['self_pickup'] :'自提'}}</b>
            @else
                @if(!empty($transaction->payment_method))
                <b>{{ isset($data['backendlang']['backendlang']['self_pickup']) ? $data['backendlang']['backendlang']['self_pickup'] :'自提'}}</b>
                @else
                <b>{{ isset($data['backendlang']['backendlang']['courier_service']) ? $data['backendlang']['backendlang']['courier_service'] :'快递服务'}}</b>
                @endif
            @endif
            @if(!empty($transaction->ship_type))
                <br>
                {{ isset($data['backendlang']['backendlang']['ship_method']) ? $data['backendlang']['backendlang']['ship_method'] :'运输方式'}}:
                @if($transaction->ship_type == 1)
                    <b>{{ isset($data['backendlang']['backendlang']['air_transport']) ? $data['backendlang']['backendlang']['air_transport'] :'空运'}}</b>
                @elseif($transaction->ship_type == 2)
                    <b>{{ isset($data['backendlang']['backendlang']['sea_transport']) ? $data['backendlang']['backendlang']['sea_transport'] :'海运'}}</b>
                @else
                    -
                @endif
            @endif
         
        </div>
        <div class="col-6" align="right">
            <h3 class="total_amount">
                <b>{{ isset($data['backendlang']['backendlang']['Total']) ? $data['backendlang']['backendlang']['Total'] :'' }}</b> : 
                <b style="color: green;">
                    @if(!empty($transaction->pv_purchase))
                        {{ number_format($transaction->grand_total, 2) }} {{ isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] :'' }}
                    @else
                        RM{{ number_format($transaction->grand_total, 2) }}
                    @endif
                </b>
            </h3>
        </div>
    </div>
    <div class="form-group mt-3">
        <div class="row">
            <div class="col-12">
                <form method="POST" action="{{ route('update_remark', md5($transaction->id)) }}">
                    @csrf
                    <h3>
                        <b>{{ isset($data['backendlang']['backendlang']['Remark']) ? $data['backendlang']['backendlang']['Remark'] :'' }}</b>
                    </h3>
                    <textarea class="form-control" name="remark" rows="3" placeholder="{{ isset($data['backendlang']['backendlang']['Remark']) ? $data['backendlang']['backendlang']['Remark'] :'' }}">{!! $transaction->remark !!}</textarea>
                    <br>
                    <button class="btn btn-primary">
                       {{ isset($data['backendlang']['backendlang']['Update']) ? $data['backendlang']['backendlang']['Update'] :'' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@if(empty($transaction->payment_method))
<div class="form-group container-box">
    <div class="row">
        <div class="col-md-12">

            @if(!empty($transaction->cod_address))
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <h4>
                                <b>{{ isset($data['backendlang']['backendlang']['Pickup_Address']) ? $data['backendlang']['backendlang']['Pickup_Address'] :'' }}</b>
                            </h4>
                            <hr>
                        </div>
                        <div class="form-group">
                            {{ $transaction->ca_address }} <br><br>
                            {{ $transaction->ca_address_desc }}
                        </div>
                        <div class="form-group">
                            <h4>
                                <b>{{ isset($data['backendlang']['backendlang']['Receiver_Details']) ? $data['backendlang']['backendlang']['Receiver_Name'] :'' }}</b>
                            </h4>
                            <hr>
                        </div>
                        <div class="form-group">
                            @if(!empty($transaction->pickup_name))
                                <b>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}:</b> {{ $transaction->pickup_name }} <br>
                            @endif
                            @if(!empty($transaction->pickup_phone))
                                <b>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}:</b> {{ $transaction->pickup_phone }} <br>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if($transaction->customer_address == 1)
                            <div class="form-group">
                                <h3>
                                    <b>{{ isset($data['backendlang']['backendlang']['Recipient_Address_Customer']) ? $data['backendlang']['backendlang']['Recipient_Address_Customer'] :'' }}</b>
                                </h3>
                                <hr>
                                <div class="form-group">
                                    <b>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}</b>: <br>
                                    {{ $transaction->c_address_name }} <br><br>

                                    <b>{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}</b>: <br>
                                    {{ $transaction->c_address }} <br>
                                    {{ $transaction->c_city }} {{ $transaction->c_postcode }} <br>
                                    {{ $transactionState->NameOfState }} {{ $transaction->country }}<br><br>
                                    <b>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</b>: <br>
                                    @if(!empty($transaction->country_code))
                                    +{{ $transaction->country_code }}
                                    @endif
                                    @if($transaction->country_code && $transaction->country_code == '60')
									    {{ ($transaction->c_phone[0] == 0) ? $transaction->c_phone : '0'.$transaction->c_phone }}
                                    @else
                                        {{ ($transaction->c_phone[0] == 0) ? substr($transaction->c_phone, 1) : $transaction->c_phone }}
                                    @endif
                                    {{-- {{ $transaction->c_phone }} --}}
                                </div>
                            </div>
                        @else
                            <div class="form-group">
                                <h3>
                                    <b>{{ isset($data['backendlang']['backendlang']['Recipient_Address']) ? $data['backendlang']['backendlang']['Recipient_Address'] :'' }}</b>
                                </h3>
                                <hr>
                                <div class="form-group">
                                    <b>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}</b>: <br>
                                    {{ $transaction->address_name }} <br><br>

                                    <b>{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}</b>: <br>
                                    {{ $transaction->address }} <br>
                                    {{ $transaction->city }} {{ $transaction->postcode }} <br>
                                    {{ !empty($transactionState->NameOfState) ? $transactionState->NameOfState : $transactionState->state }}, {{ $transaction->country_name }}<br><br>
                                    <b>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</b>: <br>
                                    @if(!empty($transaction->country_code))
                                    +{{ $transaction->country_code }}
                                    @endif
                                    @if($transaction->country_code && $transaction->country_code == '60')
									    {{ ($transaction->phone[0] == 0) ? $transaction->phone : '0'.$transaction->phone }}
                                    @else
                                        {{ ($transaction->phone[0] == 0) ? substr($transaction->phone, 1) : $transaction->phone }}
                                    @endif
                                    {{-- {{ $transaction->phone }} --}}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                @if($transaction->customer_address == 1)
                    <div class="form-group">
                        <h3>
                            <b>{{ isset($data['backendlang']['backendlang']['Recipient_Address_Customer']) ? $data['backendlang']['backendlang']['Recipient_Address_Customer'] :'' }}</b>
                        </h3>
                        <hr>
                        <div class="form-group">
                            <b>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}</b>: <br>
                            {{ $transaction->c_address_name }} <br><br>

                            <b>{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}</b>: <br>
                            {{ $transaction->c_address }} <br>
                            {{ $transaction->c_city }} {{ $transaction->c_postcode }} <br>
                            {{ $transactionState->NameOfState }} {{ $transaction->country }}<br><br>
                            <b>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</b>: <br>
                            @if(!empty($transaction->country_code))
                            +{{ $transaction->country_code }}
                            @endif
                            @if($transaction->country_code && $transaction->country_code == '60')
                                {{ ($transaction->c_phone[0] == 0) ? $transaction->c_phone : '0'.$transaction->c_phone }}
                            @else
                                {{ ($transaction->c_phone[0] == 0) ? substr($transaction->c_phone, 1) : $transaction->c_phone }}
                            @endif
                            {{-- {{ $transaction->c_phone }} --}}
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <h3>
                            <b>{{ isset($data['backendlang']['backendlang']['Recipient_Address']) ? $data['backendlang']['backendlang']['Recipient_Address'] :'' }}</b>
                        </h3>
                        <hr>
                        <div class="form-group">
                            <b>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}</b>: <br>
                            {{ $transaction->address_name }} <br><br>

                            <b>{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}</b>: <br>
                            {{ $transaction->address }} <br>
                            {{ $transaction->city }} {{ $transaction->postcode }} <br>
                            {{ !empty($transactionState->NameOfState) ? $transactionState->NameOfState : $transactionState->state }}, {{ $transaction->country_name }}<br><br>
                            <b>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</b>: <br>
                            @if(!empty($transaction->country_code))
                            +{{ $transaction->country_code }}
                            @endif
                            @if($transaction->country_code && $transaction->country_code == '60')
                                {{ ($transaction->phone[0] == 0) ? $transaction->phone : '0'.$transaction->phone }}
                            @else
                                {{ ($transaction->phone[0] == 0) ? substr($transaction->phone, 1) : $transaction->phone }}
                            @endif
                            {{-- {{ $transaction->phone }} --}}
                        </div>
                    </div>
                @endif
            @endif
            @if($transaction->different_billing_address == 1 && !empty($bill_address->id))
                @if(!empty($bill_address))
                    <div class="form-group">
                        <h3>
                            <b>{{ isset($data['backendlang']['backendlang']['Billing_Address']) ? $data['backendlang']['backendlang']['Billing_Address'] :'' }}</b>
                        </h3>
                        <hr>
                        <div class="form-group">
                            <b>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}</b>: <br>
                            {{ $bill_address->address_name }} <br><br>

                            <b>{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}</b>: <br>
                            {{ $bill_address->address }} <br>
                            {{ $bill_address->city }} {{ $bill_address->postcode }} <br>
                            {{ !empty($bill_address->NameOfState) ? $bill_address->NameOfState : $bill_address->state }}, {{ $transaction->country_name }}<br><br>
                            <b>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</b>: <br>
                            @if(!empty($bill_address->country_code))
                            +{{ $bill_address->country_code }}
                            @endif
                            @if($bill_address->country_code && $bill_address->country_code == '60')
                                {{ ($bill_address->phone[0] == 0) ? $bill_address->phone : '0'.$bill_address->phone }}
                            @else
                                {{ ($bill_address->phone[0] == 0) ? substr($bill_address->phone, 1) : $bill_address->phone }}
                            @endif
                            {{-- {{ $bill_address->phone }} --}}
                        </div>
                    </div>
                @endif
            @else
                <div class="form-group">
                    <h3>
                        <b>{{ isset($data['backendlang']['backendlang']['Billing_Address']) ? $data['backendlang']['backendlang']['Billing_Address'] :'' }}</b>
                    </h3>
                    <hr>
                    <div class="form-group">
                        <b>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}</b>: <br>
                        {{ $transaction->address_name }} <br><br>

                        <b>{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}</b>: <br>
                        {{ $transaction->address }} <br>
                        {{ $transaction->city }} {{ $transaction->postcode }} <br>
                        {{ !empty($transactionState->NameOfState) ? $transactionState->NameOfState : $transactionState->state }}, {{ $transaction->country_name }}<br><br>
                        <b>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</b>: <br>
                        @if(!empty($transaction->country_code))
                        +{{ $transaction->country_code }}
                        @endif
                        @if($transaction->country_code && $transaction->country_code == '60')
                            {{ ($transaction->phone[0] == 0) ? $transaction->phone : '0'.$transaction->phone }}
                        @else
                            {{ ($transaction->phone[0] == 0) ? substr($transaction->phone, 1) : $transaction->phone }}
                        @endif
                        {{-- {{ $transaction->phone }} --}}
                    </div>
                </div>
            @endif
        </div>
        
    </div>
</div>
@endif

<div class="form-group container-box">
    @foreach($details as $detail)
    @php
    $image = (!empty($detail->product_image)) ? $detail->product_image : 'images/no-image-available-icon-6.jpg';
    @endphp
    <div class="form-group">
        <div class="row">
            <div class="col-sm-1" align="center">
                <div class="from-group">
                    <img src="{{ asset($image) }}" style="width: 70px;">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group product-details">
                    <b>{{ $detail->product_name }}</b> &nbsp;&nbsp;&nbsp;
                    @if($transaction->status == 99)
                        <span class="badge badge-pill bg-warning">{{ isset($data['backendlang']['backendlang']['Unpaid']) ? $data['backendlang']['backendlang']['Unpaid'] :'' }}</span>
                    @elseif($transaction->status == 97)
                        <span class="badge badge-pill label-info">{{ isset($data['backendlang']['backendlang']['Waiting_Verification']) ? $data['backendlang']['backendlang']['Waiting_Verification'] :'' }}</span>
                    @elseif($transaction->status == 98)
                        <span class="badge badge-pill badge-info">{{ isset($data['backendlang']['backendlang']['Waiting_Verification']) ? $data['backendlang']['backendlang']['Waiting_Verification'] :'' }}</span>
                    @elseif($transaction->status == 1)
                        @if(!empty($transaction->bank_id))
                            <span class="badge badge-pill bg-success">{{ isset($data['backendlang']['backendlang']['Paid']) ? $data['backendlang']['backendlang']['Paid'] :'' }}</span>
                        @else
                            <span class="badge badge-pill bg-success">{{ isset($data['backendlang']['backendlang']['Paid']) ? $data['backendlang']['backendlang']['Paid'] :'' }}</span>
                        @endif
                    @elseif($transaction->status == '96')
                        <span class="badge bg-danger">{{ isset($data['backendlang']['backendlang']['Rejected']) ? $data['backendlang']['backendlang']['Rejected'] :'' }}</span>
                    @else
                        <span class="badge badge-pill bg-danger">{{ isset($data['backendlang']['backendlang']['Cancelled']) ? $data['backendlang']['backendlang']['Cancelled'] :'' }}</span>
                    @endif
                    <br>
                    {!! ($detail->sub_category != '') ? "Option: ".$detail->sub_category."<br>" : '' !!}
                    {!! ($detail->second_sub_category != '') ? "Second Option: ".$detail->second_sub_category."<br>" : '' !!}
                    @if(!empty($transaction->pv_purchase))
                        {{ number_format($detail->unit_price, 2) }} Point<br>
                    @else
                        RM {{ number_format($detail->unit_price, 2) }}<br>
                    @endif
                    {{ isset($data['backendlang']['backendlang']['Qty']) ? $data['backendlang']['backendlang']['Qty'] :'' }}: {{ $detail->quantity }}<br>
                   {{ isset($data['backendlang']['backendlang']['total_weight']) ? $data['backendlang']['backendlang']['total_weight'] :'' }}: {{ $detail->quantity }}: {{ $detail->unit_weight * $detail->quantity }} 
                    @if(!empty($detail->get_promo_title))
                    <br>
                    <span class="badge bg-danger">
                        {{ $detail->get_promo_title->promo_title }}
                    </span>
                    @endif

                    @if(!empty($detail->get_pv))
                        <br>
                        {{ $detail->get_pv }} PV
                    @endif
                </div>
            </div>
        </div>
    </div>
    <hr>
    @endforeach
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            @php
                $ex = explode('.',$transaction->bank_slip);
                $end = end($ex);
            @endphp
            <div class="form-group container-box" 
                 style="{{ ($end == 'pdf') ? 'height: 500px' : 'height: 332px' }}; overflow: hidden;">
                <h3>
                    <b>
                        {{ isset($data['backendlang']['backendlang']['Bank_Slip']) ? $data['backendlang']['backendlang']['Bank_Slip'] :'' }}
                    </b>
                </h3>
                <hr>
                @if(!empty($transaction->bank_slip))
                    @if($end == 'pdf')
                        <iframe src="{{ asset($transaction->bank_slip) }}" width="100%" style="height:100%"></iframe>
                    @else
                        <a href="#" data-toggle="modal" data-target="#myModal">
                            <div style="background-image: url('{{ asset($transaction->bank_slip) }}');
                                        background-size: cover;
                                        background-repeat: no-repeat;
                                        background-position: center;
                                        width: 150px;
                                        height: 150px;">
                            </div>
                        </a>
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-body">
                                <img src="{{ asset(asset($transaction->bank_slip)) }}" width="100%">
                            </div>
                            </div>
                        </div>
                        </div>
                    @endif
                @else
                    <h3 align="center" style="color: #b5b2b2;margin-top: 88px;">
                        {{ isset($data['backendlang']['backendlang']['No_Bank_Slip']) ? $data['backendlang']['backendlang']['No_Bank_Slip'] :'' }}
                    </h3>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group container-box">
                <div class="form-group">
                    <h3>
                        <b>{{ isset($data['backendlang']['backendlang']['Summary']) ? $data['backendlang']['backendlang']['Summary'] :'' }}</b>
                    </h3>
                </div>
                <hr>
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            {{ isset($data['backendlang']['backendlang']['Sub_Total']) ? $data['backendlang']['backendlang']['Sub_Total'] :'' }}: 
                        </div>
                        <div class="col-6" align="right">
                            @if(!empty($transaction->sub_total))
                                @if(!empty($transaction->pv_purchase))
                                    {{ number_format($transaction->sub_total, 2) }} {{ isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] :'' }}
                                @else
                                    RM {{ number_format($transaction->sub_total, 2) }}
                                @endif
                            @else
                                 @if(!empty($transaction->pv_purchase))
                                    {{ number_format(($transaction->grand_total) - $transaction->shipping_fee - $transaction->processing_fee + $transaction->discount, 2) }} {{ isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] :'' }}
                                @else
                                    RM {{ number_format(($transaction->grand_total) - $transaction->shipping_fee - $transaction->processing_fee + $transaction->discount, 2) }}
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                @if(!empty($transaction->ad_discount))
                @php
                    if($transaction->ad_discount_type == 'Percentage'){
                        $display_ad = "(".$transaction->ad_discount_amount."%)";
                    }else{
                        $display_ad = "(RM ".number_format($transaction->ad_discount_amount, 2).")";
                    }
                @endphp
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                           {{ isset($data['backendlang']['backendlang']['Additional_Discount']) ? $data['backendlang']['backendlang']['Additional_Discount'] :'' }} {{ $display_ad }}: 
                        </div>
                        <div class="col-6" align="right">
                            (-) 
                            @if(!empty($transaction->pv_purchase))
                                {{ number_format($transaction->ad_discount, 2) }} {{ isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] :'' }}
                            @else
                                RM {{ number_format($transaction->ad_discount, 2) }}
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            @php
                                $afterDiscount = $transaction->sub_total - $transaction->discount;
                            @endphp
                            
                            @if($afterDiscount <= 0)
                                {{ isset($data['backendlang']['backendlang']['Discount']) ? $data['backendlang']['backendlang']['Discount'] :'' }}(
                                @if(!empty($transaction->pv_purchase))
                                    {{ $transaction->sub_total }} Point
                                @else
                                    RM {{ $transaction->sub_total }}
                                @endif
                                    or {{ $transaction->discount_amount }}%):
                            @else
                               {{ isset($data['backendlang']['backendlang']['Discount']) ? $data['backendlang']['backendlang']['Discount'] :'' }}
                                @if(!empty($transaction->discount_code))
                                    (
                                        @if(!empty($transaction->discount_code))
                                            {{ $transaction->discount_code }}
                                            ->
                                        @endif

                                        @if($transaction->discount_type == 'Percentage')
                                            {{ number_format($transaction->discount_amount, 2) }}%
                                        @else
                                            @if(!empty($transaction->pv_purchase))
                                                {{ number_format($transaction->discount_amount, 2) }} {{ isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] :'' }}
                                            @else
                                                RM {{ number_format($transaction->discount_amount, 2) }}
                                            @endif
                                        @endif
                                    )
                                @endif
                            @endif
                            :
                        </div>
                        <div class="col-6" align="right">
                            @if($afterDiscount <= 0)
                            (-) 
                                @if(!empty($transaction->pv_purchase))
                                    {{ $transaction->sub_total }} {{ isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] :'' }}
                                @else
                                    RM {{ $transaction->sub_total }}
                                @endif
                            @else
                            (-) 
                                @if(!empty($transaction->pv_purchase))
                                    {{ number_format($transaction->discount, 2) }} {{ isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] :'' }}
                                @elseif(!empty($transaction->discount))
                                    RM {{ number_format($transaction->discount, 2) }}
                                @else
                                    RM {{ number_format(0, 2) }}
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            {{ isset($data['backendlang']['backendlang']['Shipping_Fee']) ? $data['backendlang']['backendlang']['Shipping_Fee'] :'' }}: 
                        </div>
                        <div class="col-6" align="right">
                            @if(!empty($transaction->pv_purchase))
                                {{ number_format($transaction->shipping_fee, 2) }} {{ isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] :'' }}
                            @else
                                RM {{ number_format($transaction->shipping_fee, 2) }}
                            @endif
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <h3>
                                <b>{{ isset($data['backendlang']['backendlang']['Grand_Total']) ? $data['backendlang']['backendlang']['Grand_Total'] :'' }}</b>
                            </h3>
                        </div>
                        <div class="col-6" align="right">
                            <h3 style="color: green;">
                                <b>
                                    @if(!empty($transaction->pv_purchase))
                                        {{ number_format($transaction->grand_total, 2) }} {{ isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] :'' }}
                                    @else
                                        RM {{ number_format($transaction->grand_total, 2) }}
                                    @endif
                                </b>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>                  
        </div>
    </div>
</div>
<div class="submit-form-btn">
    <div class="form-group wizard-actions" align="right">
        <a href="{{ url()->previous() }}" class="btn btn-outline-danger">
            <i class="fa fa-angle-left"></i> {{ isset($data['backendlang']['backendlang']['Back_To_List']) ? $data['backendlang']['backendlang']['Back_To_List'] :'' }}
        </a>
        @if($transaction->status == '98')
        <a href="{{ route('transaction.transactions.index') }}" class="btn btn-success change_action" data-id="1">
            <i class="fa fa-check"></i> {{ isset($data['backendlang']['backendlang']['Approve']) ? $data['backendlang']['backendlang']['Approve'] :'' }}
        </a>

        <a href="{{ route('transaction.transactions.index') }}" class="btn btn-danger change_action" data-id="96">
            <i class="fa fa-ban"> </i> {{ isset($data['backendlang']['backendlang']['Reject']) ? $data['backendlang']['backendlang']['Reject'] :'' }}
        </a>
        @endif

        @if($transaction->status == '97')
        <a class="btn btn-success change_action" data-id="1">
            <i class="fa fa-check"></i> {{ isset($data['backendlang']['backendlang']['complete']) ? $data['backendlang']['backendlang']['complete'] :'' }}
        </a>

        <a class="btn btn-danger change_action" data-id="95">
            <i class="fa fa-ban"> </i> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}
        </a>
        @endif

        @if($transaction->status == '1')
        <a href="{{ route('transaction_invoice', $transaction->transaction_no) }}"  class="btn btn-success" target="_blank">
            <i class="fa fa-print"> </i> {{ isset($data['backendlang']['backendlang']['Print_Invoice']) ? $data['backendlang']['backendlang']['Print_Invoice'] :'' }}
        </a>
        @endif
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    $('.submit-form-btn .btn-outline-primary').click( function(e){
        e.preventDefault();
        
        $('#sub-categories-form').submit();
    });
</script>

<script type="text/javascript">
    $('.change_action').click( function(e){
        e.preventDefault();
        var ele = $(this);
        var action_id = $(this).data('id');
        var tid = '{{ $transaction->id }}';
        var fd = new FormData();
        fd.append('action_id', action_id);
        fd.append('tid', tid);

        if(action_id == '1'){
            var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Complete_This_Transaction']) ? $data['backendlang']['backendlang']['Complete_This_Transaction'] :'' }}');
        }else if(action_id == '95'){
            var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Cancel_This_Transaction']) ? $data['backendlang']['backendlang']['Cancel_This_Transaction'] :'' }} ');
        }else if(action_id == '96'){
            var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Reject_This_Transaction']) ? $data['backendlang']['backendlang']['Reject_This_Transaction'] :'' }}');
        }

        if(confirmMessage == true){
            $.ajax({
               url: '{{ route("change_transaction_action") }}',
               type: 'post',
               data: fd,
               contentType: false,
               processData: false,
               success: function(response){
                
                    toastr.success('{{ isset($data['backendlang']['backendlang']['Update_Successful']) ? $data['backendlang']['backendlang']['Update_Successful'] :'' }}');
                    window.location.href = "{{ route('transaction.transactions.index') }}";
                    // if(action_id == '1'){
                    //  ele.closest('tr').find('.status_id').html('<span class="badge bg-success">Approved</span>');
                    // }else if(action_id == '98'){
                    //  ele.closest('tr').find('.status_id').html('<span class="badge bg-danger">Rejected</span>');
                    // }else{
                    //  ele.closest('tr').find('.status_id').html('<span class="badge bg-danger">Cancelled</span>');
                    // }
               },
            });         
        }
    });
</script>
@endsection