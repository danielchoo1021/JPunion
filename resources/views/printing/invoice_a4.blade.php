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
    // One long comma-joined line instead of a separate <div> per field - a
    // stack of 3-4 short lines was the main thing making the header tall,
    // and the wider Bill To column (below) gives this room to actually
    // stay on one line for a typical address.
    $shipToAddressLine = collect([
        $transaction->address,
        $transaction->address_2 ?? null,
        trim(collect([$transaction->postcode, $transaction->city, $delivery_state->name ?? $transaction->state])->filter()->implode(', ')),
        $delivery_country->country_name ?? $transaction->country,
    ])->filter()->implode(', ');

    // A5 is roughly half the area of A4, so the same spacing/column widths
    // that look fine on A4 waste a lot of relative space on A5 - tighten
    // the header block and give the address column more width (instead of
    // squeezing it into a 50/50 split, which wraps a normal address onto
    // 3-4 short lines) so the header takes up less of the page overall.
    $isA5 = ($paperSize ?? 'a4') === 'a5';
    $pageMargin = $isA5 ? '8mm 10mm' : '12mm 14mm';
    $logoSize = $isA5 ? 38 : 55;
    $h1Size = $isA5 ? 11 : 14;
    $hrMargin = $isA5 ? '3pt 0' : '6pt 0';
    $addrTitleSize = $isA5 ? 7.5 : 8.5;
    $addrTitleMargin = $isA5 ? 1 : 2;
    $billToWidth = $isA5 ? '68%' : '50%';
    $purchasedByWidth = $isA5 ? '32%' : '50%';
    $signatureMarginTop = $isA5 ? '14pt' : '30pt';
    $footerMarginTop = $isA5 ? '5pt' : '10pt';
    $companyLineSize = $isA5 ? 8 : 9;
    $subHeaderMarginTop = $isA5 ? '4pt' : '6pt';
    $invoiceNoSize = $isA5 ? 11 : 13;
@endphp
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { box-sizing: border-box; }
    @page { margin: {{ $pageMargin }}; }
    /* "notosanssc"/"notosanstc" come first so Chinese product/customer names
       render correctly - without a font that has CJK glyphs, dompdf falls
       back to a Latin-only core font and prints "?" for every Chinese
       character. Both fonts also cover Latin, so English text is unaffected. */
    body { font-family: "notosanssc", "notosanstc", "Open Sans", Helvetica, Arial, sans-serif; font-size: 9.5pt; color: #000; margin: 0; padding: 0; }
    table { border-collapse: collapse; width: 100%; }
    .muted { color: #444; }
    .text-right { text-align: right; }
    .logo { max-width: {{ $logoSize }}pt; max-height: {{ $logoSize }}pt; }
    h1 { font-size: {{ $h1Size }}pt; margin: 0 0 2pt 0; }
    hr { border: none; border-top: 1px solid #000; margin: {{ $hrMargin }}; }

    /* Company name/address/phone as a prominent, single-line-per-field
       block at the very top - previously "muted" gray small text squeezed
       into a narrow middle column next to Invoice No/Date/Payment.
       No font-weight here: only the "normal" weight of the CJK fonts is
       registered (see ensureCjkFontsRegistered()), so bolding this would
       make dompdf fall back to a core serif font instead - fine for Latin
       text but "?" if the company name/address is ever in Chinese. */
    .company-line { color: #000; font-size: {{ $companyLineSize }}pt; }
    .company-info { text-align: center; }

    /* Boxed + larger so the invoice number stands out at a glance instead
       of reading the same as the other Payment/Date labels around it. */
    .invoice-no-box { display: inline-block; border: 1px solid #000; padding: 3pt 8pt; font-size: {{ $invoiceNoSize }}pt; font-weight: 700; }

    .header-table td { vertical-align: top; }

    .addr-title { font-weight: 700; text-transform: uppercase; font-size: {{ $addrTitleSize }}pt; margin-bottom: {{ $addrTitleMargin }}pt; }

    /* table-layout: fixed + word-break so a long/unbroken Chinese product
       name wraps within its column instead of overflowing the page -
       Chinese text has no spaces for the browser to find a break point at.
       Only the Description column is left unsized so it absorbs whatever
       width remains after the others' fixed widths. */
    .items { table-layout: fixed; }
    .items th, .items td { border-bottom: 1px solid #ddd; padding: 4pt 5pt; font-size: 9pt; text-align: left; vertical-align: top; word-break: break-word; overflow-wrap: break-word; }
    .items th { border-top: 1px solid #000; border-bottom: 1px solid #000; }
    .items td.num, .items th.num { text-align: right; width: 60pt; }
    .items td.qty, .items th.qty { text-align: center; width: 40pt; }
    .items td.code, .items th.code { width: 55pt; }

    .totals-table { width: 260pt; margin-left: auto; }
    .totals-table td { padding: 2pt 0; }
    .totals-table .grand-total td { font-weight: 700; border-top: 1px solid #000; padding-top: 4pt; }

    .footer { text-align: center; font-size: 8pt; margin-top: {{ $footerMarginTop }}; color: #555; }
    .page-break { page-break-after: always; }
    .signature-box { width: 220pt; margin: {{ $signatureMarginTop }} 0 0 0; }
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
                <td class="company-info">
                    <h1>{{ $company_name }}</h1>
                    @if(!empty($company_address))<div class="company-line">{{ $company_address }}</div>@endif
                    @if(!empty($company_phone))<div class="company-line">Tel: {{ $company_phone }}</div>@endif
                </td>
            </tr>
        </table>

        <table class="header-table" style="margin-top: {{ $subHeaderMarginTop }};">
            <tr>
                <td style="width: 50%;">
                    <div><b>Payment:</b> {{ $payment_method_label }}</div>
                    <div style="margin-top: 3pt;"><b>Date:</b> {{ optional($transaction->created_at)->format('d/m/Y') }}</div>
                </td>
                <td style="width: 50%;" class="text-right">
                    <div class="muted" style="font-size: 8pt;">Invoice No</div>
                    <div class="invoice-no-box">{{ $transaction->transaction_no }}</div>
                </td>
            </tr>
        </table>

        <hr style="margin-top: 2pt;">

        <table class="header-table">
            <tr>
                <td style="width: {{ $billToWidth }};">
                    <div class="addr-title">Bill To</div>
                    <div><b>{{ $shipToName }}</b></div>
                    @if(!empty($shipToAddressLine))
                        <div>{{ $shipToAddressLine }}</div>
                    @endif
                    @if(!empty($transaction->phone))
                        <div>Tel: {{ !empty($transaction->country_code) ? '+'.$transaction->country_code.' ' : '' }}{{ $transaction->phone }}</div>
                    @endif
                </td>
                <td style="width: {{ $purchasedByWidth }};">
                    <div class="addr-title">Purchased By</div>
                    <div>{{ $transaction->address_name }} ({{ $purchased_by_display_code ?? $transaction->user_id }})</div>
                    @if(!empty($transaction->email))<div>{{ $transaction->email }}</div>@endif
                </td>
            </tr>
        </table>

        <hr>

        <table class="items">
            <thead>
                <tr>
                    <th style="width: 25pt;">No</th>
                    <th class="code">Item Code</th>
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
