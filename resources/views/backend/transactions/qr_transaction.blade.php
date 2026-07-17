@extends('layouts.admin_app')
@section('content')
    <h3>{{ isset($data['backendlang']['backendlang']['QR_Pay_Transaction_List']) ? $data['backendlang']['backendlang']['QR_Pay_Transaction_List'] :'' }}</h3><hr>
    <table class="table table-bordered">
        <thead>
            <th>{{ isset($data['backendlang']['backendlang']['QR_Transaction_No']) ? $data['backendlang']['backendlang']['QR_Transaction_No'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['From']) ? $data['backendlang']['backendlang']['From'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['DateTime']) ? $data['backendlang']['backendlang']['DateTime'] :'' }}</th>
        </thead>
        <tbody>
            @if (!$qr_list->isEmpty())
                @foreach ($qr_list as $qr)
                    <tr>
                        <td>{{ $qr->payment_no }}</td>
                        <td>{{ $qr->customer_name }}</td>
                        <td>RM {{ number_format($qr->amount,2)}}</td>
                        <td>{{$qr->created_at }}</td>
                    </tr>
                @endforeach
               
            @endif
            
        </tbody>
    </table>
    {{ $qr_list->links() }}
@endsection