@extends('layouts.admin_app')

@section('content')
<div class="container-box">
    <h2 class="section-header">Print History - {{ $transaction->transaction_no }}</h2>
    <p class="text-muted">
        Every auto-print attempt recorded for this order. "Skipped" means it was deliberately excluded (e.g. the
        printer was assigned to that template after this order was already placed) - it was never actually printed.
    </p>

    <div class="row">
        <div class="col-12" style="overflow: auto;">
            <table class="table table-bordered" style="text-align: center;">
                <thead>
                    <tr class="info">
                        <th>When</th>
                        <th>Template</th>
                        <th>Printer</th>
                        <th>Attempt</th>
                        <th>Result</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        @php
                            $templateLabels = [
                                'invoice_a4' => 'Invoice (A4)',
                                'invoice_a5' => 'Invoice (A5)',
                                'packing_label' => 'Packing Label',
                                'packing_label_roll' => 'Packing Label (Paper Roll)',
                            ];

                            if ($log->status === 'success') {
                                $bg = '#28a745'; $label = 'Printed';
                            } elseif ($log->status === 'skipped') {
                                $bg = '#6c757d'; $label = 'Skipped';
                            } else {
                                $bg = '#dc3545'; $label = 'Failed';
                            }
                        @endphp
                        <tr>
                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $templateLabels[$log->document_type] ?? $log->document_type }}</td>
                            <td>{{ $log->printer_name }}</td>
                            <td>{{ $log->attempt }}</td>
                            <td>
                                <span style="display: inline-block; padding: 3px 10px; border-radius: 10px; background-color: {{ $bg }}; color: #fff; font-size: 12px; font-weight: 600;">
                                    {{ $label }}
                                </span>
                            </td>
                            <td style="text-align: left;">{{ $log->message ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">No print activity recorded for this order yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
