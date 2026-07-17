@php
    // Real physical label measured by hand: 8cm (w) x 6cm (h), landscape.
    // Page 1 gets the full header (logo/company/order/customer/address) so
    // it can only fit a few item rows. Continuation pages only repeat the
    // order no + page footer, so they have much more room for items.
    // These row counts are estimates - adjust if the packer says a page is
    // wasting space or still cutting items off.
    $rowsFirstPage = 4;
    $rowsContinuationPage = 10;

    $totalQuantity = $details->sum('quantity');

    $pages = collect();
    if ($details->count() <= $rowsFirstPage) {
        $pages->push($details);
    } else {
        $pages->push($details->slice(0, $rowsFirstPage)->values());
        $rest = $details->slice($rowsFirstPage)->values();
        foreach ($rest->chunk($rowsContinuationPage) as $chunk) {
            $pages->push($chunk);
        }
    }
    $totalPages = max($pages->count(), 1);
@endphp
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { box-sizing: border-box; }
    /* @page margin + width:100% tables render unreliably together in dompdf
       (table width ends up computed against the un-margined page, pushing
       right-hand content off the physical edge). Zero the page margin and
       apply the margin to a normal block div instead - percentage widths
       inside that div size correctly against its content box. */
    @page { margin: 0; }
    body { font-family: "Open Sans", Helvetica, Arial, sans-serif; font-size: 7.5pt; color: #000; margin: 0; padding: 0; }
    .page-content { margin: 5mm 2mm 5mm 14mm; }
    table { border-collapse: collapse; width: 100%; }
    .header-table td { vertical-align: middle; }
    .info-table { font-size: 6.5pt; }
    .info-table td { padding: 0; line-height: 1.3; white-space: nowrap; }
    .text-right { text-align: right; }
    .logo { max-width: 17pt; max-height: 17pt; }
    h1 { font-size: 9pt; margin: 0; line-height: 1.1; }
    .muted { color: #333; }
    hr { border: none; border-top: 1px dashed #000; margin: 2pt 0; }
    .items th, .items td { border-bottom: 0.5px solid #999; padding: 1.5pt 2pt; font-size: 7.5pt; text-align: left; vertical-align: top; line-height: 1.2; }
    .items th { border-top: 1px solid #000; border-bottom: 1px solid #000; font-size: 7.5pt; }
    .items td.qty, .items th.qty { text-align: center; width: 22pt; }
    .total-row td { font-weight: 700; border-top: 1px solid #000; border-bottom: none; }
    .footer { text-align: center; font-size: 6.5pt; margin-top: 2pt; }
    .page-break { page-break-after: always; }
    .cont-header { font-size: 8pt; font-weight: 700; margin-bottom: 2pt; }
</style>
</head>
<body>

@foreach($pages as $pageIndex => $pageItems)
    <div class="{{ !$loop->last ? 'page-break' : '' }}">
      <div class="page-content">

        @if($pageIndex === 0)
            <table class="header-table">
                <tr>
                    <td style="width: 18pt;">
                        @if(!empty($website_logo))
                            <img class="logo" src="{{ $website_logo }}">
                        @endif
                    </td>
                    <td>
                        <h1>{{ $company_name }}</h1>
                    </td>
                </tr>
            </table>

            <hr>

            <table class="info-table">
                <tr>
                    <td><b>Order:</b> {{ $transaction->transaction_no }}</td>
                    <td class="text-right"><b>Date:</b> {{ optional($transaction->created_at)->format('d/m/Y') }} {{ optional($transaction->created_at)->format('H:i') }}</td>
                </tr>
                <tr>
                    <td><b>Customer:</b> {{ $transaction->address_name }}</td>
                    <td class="text-right"><b>Tel:</b> {{ !empty($transaction->country_code) ? '+'.$transaction->country_code.' ' : '' }}{{ $transaction->phone }}</td>
                </tr>
            </table>

            <div><b>Address:</b> {{ $transaction->address }}, {{ trim(collect([$transaction->postcode, $transaction->city, $delivery_state_name ?? $transaction->state])->filter()->implode(', ')) }}, {{ $delivery_country_name ?? $transaction->country }}</div>

            <hr>
        @else
            <div class="cont-header">{{ $company_name }} &nbsp; | &nbsp; Order No: {{ $transaction->transaction_no }}</div>
            <hr>
        @endif

        <table class="items">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="qty">Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pageItems as $line)
                    <tr>
                        <td>
                            {{ $line->product_name }}
                            @if(!empty($line->sub_category))<br><span class="muted">{{ $line->sub_category }}</span>@endif
                            @if(!empty($line->second_sub_category))<br><span class="muted">{{ $line->second_sub_category }}</span>@endif
                        </td>
                        <td class="qty">{{ number_format($line->quantity, 0) }}</td>
                    </tr>
                    @if($loop->last && $loop->parent->last)
                        <tr class="total-row">
                            <td>Total Quantity</td>
                            <td class="qty">{{ number_format($totalQuantity, 0) }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        @if($pageIndex === 0 && !empty($transaction->remark))
            <div><b>Remarks:</b> {{ $transaction->remark }}</div>
        @endif

        <div class="footer">Page {{ $pageIndex + 1 }} / {{ $totalPages }}</div>

      </div>
    </div>
@endforeach

</body>
</html>
