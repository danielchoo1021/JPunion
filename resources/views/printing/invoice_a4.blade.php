@php
    // A4 has plenty of room; this budget is generous on purpose so a normal
    // order fits on one page. Only very large orders should ever hit page 2.
    $rowsPerPage = 22;
    $pages = $details->chunk($rowsPerPage);
    $totalPages = max($pages->count(), 1);

    $subTotal = $details->sum(function ($line) {
        return $line->unit_price * $line->t_qty;
    });
    $grandTotal = $subTotal + $transaction->shipping_fee - $transaction->discount - $transaction->ad_discount + $transaction->processing_fee;

    $shipToName = $transaction->address_name;
    $shipToLines = collect([
        $transaction->address,
        $transaction->address_2 ?? null,
        trim(collect([$transaction->postcode, $transaction->city, $delivery_state->name ?? $transaction->state])->filter()->implode(', ')),
        $delivery_country->country_name ?? $transaction->country,
    ])->filter();
@endphp
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { box-sizing: border-box; }
    @page { margin: 12mm 14mm; }
    body { font-family: "Open Sans", Helvetica, Arial, sans-serif; font-size: 9.5pt; color: #000; margin: 0; padding: 0; }
    table { border-collapse: collapse; width: 100%; }
    .muted { color: #444; }
    .text-right { text-align: right; }
    .logo { max-width: 55pt; max-height: 55pt; }
    h1 { font-size: 14pt; margin: 0 0 2pt 0; }
    hr { border: none; border-top: 1px solid #000; margin: 6pt 0; }

    .header-table td { vertical-align: top; }
    .info-table td { padding: 1pt 0; }
    .info-table .label { width: 85pt; }

    .addr-title { font-weight: 700; text-transform: uppercase; font-size: 8.5pt; margin-bottom: 2pt; }

    .items th, .items td { border-bottom: 1px solid #ddd; padding: 4pt 5pt; font-size: 9pt; text-align: left; vertical-align: top; }
    .items th { border-top: 1px solid #000; border-bottom: 1px solid #000; }
    .items td.num, .items th.num { text-align: right; }
    .items td.qty, .items th.qty { text-align: center; width: 40pt; }

    .totals-table { width: 260pt; margin-left: auto; }
    .totals-table td { padding: 2pt 0; }
    .totals-table .grand-total td { font-weight: 700; border-top: 1px solid #000; padding-top: 4pt; }

    .footer { text-align: center; font-size: 8pt; margin-top: 10pt; color: #555; }
    .page-break { page-break-after: always; }
    .signature-box { width: 220pt; margin: 30pt 0 0 0; }
    .signature-line { border-top: 1px solid #000; text-align: center; padding-top: 3pt; font-size: 8.5pt; }
</style>
</head>
<body>

@foreach($pages as $pageIndex => $pageItems)
    <div class="{{ !$loop->last ? 'page-break' : '' }}">

        <table class="header-table">
            <tr>
                <td style="width: 65pt;">
                    @if(!empty($website_logo))
                        <img class="logo" src="{{ $website_logo }}">
                    @endif
                </td>
                <td>
                    <h1>{{ $company_name }}</h1>
                    @if(!empty($company_address))<div class="muted">{{ $company_address }}</div>@endif
                    @if(!empty($company_phone))<div class="muted">Tel: {{ $company_phone }}</div>@endif
                </td>
                <td style="width: 170pt;" class="text-right">
                    <table class="info-table">
                        <tr><td class="label muted">Invoice No</td><td class="text-right">{{ $transaction->transaction_no }}</td></tr>
                        <tr><td class="label muted">Date</td><td class="text-right">{{ optional($transaction->created_at)->format('d/m/Y') }}</td></tr>
                        <tr><td class="label muted">Payment</td><td class="text-right">{{ $payment_method_label }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <hr>

        <table class="header-table">
            <tr>
                <td style="width: 50%;">
                    <div class="addr-title">Bill To</div>
                    <div><b>{{ $shipToName }}</b></div>
                    @foreach($shipToLines as $line)
                        <div>{{ $line }}</div>
                    @endforeach
                    @if(!empty($transaction->phone))
                        <div>Tel: {{ !empty($transaction->country_code) ? '+'.$transaction->country_code.' ' : '' }}{{ $transaction->phone }}</div>
                    @endif
                </td>
                <td style="width: 50%;">
                    <div class="addr-title">Purchased By</div>
                    <div>{{ $transaction->address_name }} ({{ $transaction->user_id }})</div>
                    @if(!empty($transaction->email))<div>{{ $transaction->email }}</div>@endif
                </td>
            </tr>
        </table>

        <hr>

        <table class="items">
            <thead>
                <tr>
                    <th style="width: 25pt;">No</th>
                    <th>Item Code</th>
                    <th>Description</th>
                    <th class="qty">Qty</th>
                    <th class="num">Unit Price</th>
                    <th class="num">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pageItems as $key => $line)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $line->item_code }}</td>
                        <td>
                            {{ $line->product_name }}
                            @if(!empty($line->sub_category))<br><span class="muted">{{ $line->sub_category }}</span>@endif
                            @if(!empty($line->second_sub_category))<br><span class="muted">{{ $line->second_sub_category }}</span>@endif
                        </td>
                        <td class="qty">{{ number_format($line->t_qty, 2) }}</td>
                        <td class="num">{{ number_format($line->unit_price, 2) }}</td>
                        <td class="num">{{ number_format($line->unit_price * $line->t_qty, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($loop->last)
            <table class="totals-table">
                <tr><td>Sub Total</td><td class="text-right">{{ number_format($subTotal, 2) }}</td></tr>
                <tr><td>Shipping Fee</td><td class="text-right">{{ number_format($transaction->shipping_fee, 2) }}</td></tr>
                @if(!empty($transaction->discount))
                    <tr><td>Discount</td><td class="text-right">-{{ number_format($transaction->discount, 2) }}</td></tr>
                @endif
                @if(!empty($transaction->ad_discount))
                    <tr><td>Agent Discount</td><td class="text-right">-{{ number_format($transaction->ad_discount, 2) }}</td></tr>
                @endif
                @if(!empty($transaction->processing_fee))
                    <tr><td>Processing Fee</td><td class="text-right">{{ number_format($transaction->processing_fee, 2) }}</td></tr>
                @endif
                <tr class="grand-total"><td>Grand Total (MYR)</td><td class="text-right">{{ number_format($grandTotal, 2) }}</td></tr>
            </table>

            @if(!empty($transaction->remark))
                <div style="margin-top: 8pt;"><b>Remarks:</b> {{ $transaction->remark }}</div>
            @endif

            <div class="signature-box">
                <div class="signature-line">Authorised Signature</div>
            </div>
        @endif

        <div class="footer">Page {{ $pageIndex + 1 }} / {{ $totalPages }}</div>

    </div>
@endforeach

</body>
</html>
