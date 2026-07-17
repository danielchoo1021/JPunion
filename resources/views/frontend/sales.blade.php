@extends('layouts.app')

<!-- Simple daterangepicker includes (CDN) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@section('content')

@include('partial.frontend.profile_header')

<div class="profile-content">
    <div class="container">
        <div class="form-group container-box">
            <h4>{{ isset($data['lang']['lang']['Transactions']) ? $data['lang']['lang']['Transactions'] :'交易记录'}}</h4>
            <hr>
            <br>
            <form method="GET" action="{{ route('sales') }}">
                @csrf
                <div class="row" style="margin-bottom:15px;">
                    <div class="col-sm-12 col-lg-2">
                        <input type="text" class="form-control" name="user" value="{{ request('user') }}" placeholder="{{ isset($data['lang']['lang']['Search_Buyer_Code']) ? $data['lang']['lang']['Search_Buyer_Code'] :''}}">
                    </div>
                    <div class="col-sm-12 col-lg-2">
                        <input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : (($startDate ?? '') . ' - ' . ($endDate ?? '')) }}" placeholder="DD/MM/YYYY - DD/MM/YYYY" readonly>
                    </div>
                    <div class="col-sm-12 col-lg-2">
                        <select name="status" class="form-control">
                            <option value="">{{ isset($data['lang']['lang']['Select_Status']) ? $data['lang']['lang']['Select_Status'] :''}}</option>
                            <option value="99" {{ request('status') == '99' ? 'selected' : '' }}>{{ isset($data['lang']['lang']['unpaid']) ? $data['lang']['lang']['unpaid'] :''}}</option>
                            <option value="97,98" {{ request('status') == '97,98' ? 'selected' : '' }}>{{ isset($data['lang']['lang']['awaiting_verify']) ? $data['lang']['lang']['awaiting_verify'] :''}}</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ isset($data['lang']['lang']['paid']) ? $data['lang']['lang']['paid'] :''}}</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-lg-2">
                        <input type="text" class="form-control" name="transaction_no" value="{{ request('transaction_no') }}" placeholder="{{ isset($data['lang']['lang']['Search_Transaction_No']) ? $data['lang']['lang']['Search_Transaction_No'] :''}}">
                    </div>
                    <div class="col-sm-12 col-lg-4 d-flex align-items-center gap-2 flex-lg-nowrap flex-wrap">
                        <button type="submit" class="btn btn-primary" style="white-space: nowrap;"><i class="fa fa-search"></i> {{ isset($data['lang']['lang']['search']) ? $data['lang']['lang']['search'] :''}}</button>
                        <a href="{{ route('sales') }}" class="btn btn-warning" style="white-space: nowrap;"><i class="fa fa-refresh"></i> {{ isset($data['lang']['lang']['clear_search']) ? $data['lang']['lang']['clear_search'] :''}}</a>
                    </div>
                </div>
                <div class="form-group">
									<div class="row">
										<div class="col-sm-2">
											<label>{{ isset($data['lang']['lang']['Item_Per_Page']) ? $data['lang']['lang']['Item_Per_Page'] :'' }}:</label>
											<select class="input-small" name="per_page">
												<option {{ (!empty(request('per_page')) && request('per_page') == '10') ? 'selected' : '' }} value="10">10</option>
												<option {{ (!empty(request('per_page')) && request('per_page') == '20') ? 'selected' : '' }} value="20">20</option>
												<option {{ (!empty(request('per_page')) && request('per_page') == '50') ? 'selected' : '' }} value="50">50</option>
											</select>
										</div>
									</div>
								</div>
            </form>
            @if(isset($transactions) && !$transactions->isEmpty())
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="info">
                            <th>#</th>
                            <th>{{ isset($data['lang']['lang']['transaction_no']) ? $data['lang']['lang']['transaction_no'] :'' }}</th>
                            <th>{{ isset($data['lang']['lang']['date']) ? $data['lang']['lang']['date'] :'' }}</th>
                            <th>{{ isset($data['lang']['lang']['Buyer_Code']) ? $data['lang']['lang']['Buyer_Code'] :'' }}</th>
                            <th>{{ isset($data['lang']['lang']['items']) ? $data['lang']['lang']['items'] :'' }}</th>
                            <th>{{ isset($data['lang']['lang']['total']) ? $data['lang']['lang']['total'] :'' }} (RM)</th>
                            <th>{{ isset($data['lang']['lang']['status']) ? $data['lang']['lang']['status'] :'' }}</th>
                            <!-- <th style="width: 160px;">Actions</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $index => $transaction)
                        <tr>
                            <td>{{ ($transactions->currentPage()-1)*$transactions->perPage() + $index + 1 }}</td>
                            <td>{{ $transaction->transaction_no }}</td>
                            <td>{{ $transaction->created_at }}</td>
                            <td>{{ $transaction->user_id }}</td>
                            <td>{{ isset($details[$transaction->id]) ? count($details[$transaction->id]) : 0 }}</td>
                            <td>{{ number_format($transaction->grand_total, 2) }}</td>
                            <td>
                                @if($transaction->status == 99)
                                    <span class="badge badge-pill bg-warning">{{ isset($data['lang']['lang']['unpaid']) ? $data['lang']['lang']['unpaid'] :'' }}</span>
                                @elseif($transaction->status == 98 || $transaction->status == 97)
                                    <span class="badge badge-pill badge-info">{{ isset($data['lang']['lang']['awaiting_verify']) ? $data['lang']['lang']['awaiting_verify'] :'' }}</span>
                                @elseif($transaction->status == 1)
                                    <span class="badge badge-pill bg-success">{{ isset($data['lang']['lang']['paid']) ? $data['lang']['lang']['paid'] :'' }}</span>
                                @else
                                    <span class="badge badge-pill bg-danger">{{ isset($data['lang']['lang']['cancelled']) ? $data['lang']['lang']['cancelled'] :'' }}</span>
                                @endif
                            </td>
                            <!-- <td>
                                <a href="{{ route('customer_invoice', $transaction->transaction_no) }}" class="btn btn-sm btn-primary">View</a>
                                <a href="{{ route('download_invoice', $transaction->transaction_no) }}" class="btn btn-sm btn-outline-primary">PDF</a>
                            </td> -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="form-group d-flex justify-content-between align-items-center">
                <div>
                    {{ $transactions->appends(request()->all())->links() }}
                </div>
            </div>
            @else
            <div class="form-group" align="center">
                {{ isset($data['lang']['lang']['No_Transaction_Found']) ? $data['lang']['lang']['No_Transaction_Found'] :'' }}
            </div>
            @endif
        </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    (function(){
        var s1 = document.createElement('script');
        s1.src = 'https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js';
        s1.onload = function(){
            var s2 = document.createElement('script');
            s2.src = 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js';
            s2.onload = function(){
                $(function(){
                    var $dates = $('input[name=dates]');
                    $dates.daterangepicker({
                        autoUpdateInput: true,
                        locale: {
                            format: 'DD/MM/YYYY',
                            applyLabel: "{{ isset($data['lang']['lang']['Apply']) ? $data['lang']['lang']['Apply'] :'' }}",
                            cancelLabel: "{{ isset($data['lang']['lang']['cancel']) ? $data['lang']['lang']['cancel'] :'' }}",
                        }
                    });
                });
            };
            document.body.appendChild(s2);
        };
        document.body.appendChild(s1);
    })();
</script>
@endsection
