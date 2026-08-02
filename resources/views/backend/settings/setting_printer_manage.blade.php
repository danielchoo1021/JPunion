@extends('layouts.admin_app')

@section('content')
<div class="container-box">
    <h2 class="section-header">Printer Manage</h2>
    <p class="text-muted">
        Status reported by the local print agent (<code>print-agent.ps1</code>) running on the machine the
        printers are physically connected to. This is a snapshot from its last check-in, not a live connection -
        if the agent isn't running, printers show as "Agent Offline" here even if the printer itself is fine.
    </p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-group">
        <button type="button" class="btn btn-secondary" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise"></i> Refresh Status
        </button>
        <div class="text-muted" style="font-size: 12px; margin-top: 4px;">
            Re-reads whatever the agent last reported - it can't reach into the local machine to force a fresh
            check, so this is only useful if the agent has reported something new since you loaded the page.
        </div>
    </div>

    <div class="row">
        <div class="col-12" style="overflow: auto;">
            <table class="table table-bordered" style="text-align: center;">
                <thead>
                    <tr class="info">
                        <th>Printer</th>
                        <th>Printing Template</th>
                        <th>Port</th>
                        <th>Status</th>
                        <th>Last Reported</th>
                        <th>Enabled</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($printers as $printer)
                        @php
                            $templateLabel = $templateLabels[$printer->document_type] ?? $printer->document_type;
                            $stale = $printer->isStale();

                            if ($stale) {
                                $statusLabel = 'Agent Offline / Unknown';
                                $statusBg = '#6c757d';
                            } elseif ($printer->is_ready) {
                                $statusLabel = 'Ready';
                                $statusBg = '#28a745';
                            } else {
                                $statusLabel = 'Not Ready';
                                $statusBg = '#dc3545';
                            }
                        @endphp
                        <tr>
                            <td>{{ $printer->printer_name ?? '-' }}</td>
                            <td>
                                <form method="POST" action="{{ route('setting_printer_manage_update', $printer->id) }}" style="display: inline-block;">
                                    @csrf
                                    <select name="document_type" class="form-control" style="display: inline-block; width: auto;" onchange="this.form.querySelector('[name=is_enabled]').value = {{ $printer->is_enabled ? 1 : 0 }}; this.form.submit()">
                                        @foreach($templateLabels as $type => $label)
                                            <option value="{{ $type }}" {{ $printer->document_type === $type ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="is_enabled" value="{{ $printer->is_enabled ? 1 : 0 }}">
                                </form>
                                <br>
                                <a href="{{ route('setting_printer_manage_preview', $printer->document_type) }}" target="_blank" style="font-size: 12px;">
                                    <i class="bi bi-eye"></i> Preview Sample
                                </a>
                            </td>
                            <td>{{ $printer->port_name ?? '-' }}</td>
                            <td>
                                <span style="display: inline-block; padding: 3px 10px; border-radius: 10px; background-color: {{ $statusBg }}; color: #fff; font-size: 12px; font-weight: 600;">
                                    {{ $statusLabel }}
                                </span>
                                @if(!empty($printer->windows_status))
                                    <div class="text-muted" style="font-size: 12px; margin-top: 3px;">{{ $printer->windows_status }}</div>
                                @endif
                            </td>
                            <td>
                                @if(!empty($printer->reported_at))
                                    {{ $printer->reported_at->diffForHumans() }}
                                @else
                                    Never
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('setting_printer_manage_update', $printer->id) }}">
                                    @csrf
                                    <input type="hidden" name="document_type" value="{{ $printer->document_type }}">
                                    <input type="hidden" name="is_enabled" value="{{ $printer->is_enabled ? 0 : 1 }}">
                                    <button type="submit" class="btn btn-sm {{ $printer->is_enabled ? 'btn-success' : 'btn-outline-secondary' }}">
                                        {{ $printer->is_enabled ? 'Enabled' : 'Disabled' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('setting_printer_manage_destroy', $printer->id) }}" onsubmit="return confirm('Remove this printer? It will stop receiving print jobs.');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted">No printers configured yet - add one below.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="container-box">
    <h2 class="section-header">Add Printer</h2>

    @if($availablePrinters->isEmpty())
        <p class="text-muted">
            No unassigned printers reported yet. Plug in the new printer, make sure the local agent
            (<code>print-agent.ps1</code>) is running, and it will show up here on its next heartbeat.
        </p>
    @else
        <form method="POST" action="{{ route('setting_printer_manage_store') }}">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>Printer</b></label>
                        <select name="printer_name" class="form-control" required>
                            <option value="" disabled selected>Select a printer...</option>
                            @foreach($availablePrinters as $discovered)
                                <option value="{{ $discovered->printer_name }}">{{ $discovered->printer_name }} ({{ $discovered->port_name }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><b>Printing Template</b></label>
                        <select name="document_type" class="form-control" required>
                            @foreach($templateLabels as $type => $label)
                                <option value="{{ $type }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label>&nbsp;</label>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary form-control">Add Printer</button>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
