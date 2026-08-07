@php
    // Real physical label measured by hand: 8cm (w) x 6cm (h), landscape.
    // Page 1 gets the full header (logo/company/order/customer/address) so
    // it can only fit a few item lines. Continuation pages only repeat the
    // order no + page footer, so they have much more room for items.
    // A product wraps onto extra lines if its name is long, or if it has a
    // sub_category/second_sub_category, so pack pages by estimated
    // text-line count rather than raw item count - otherwise a page of
    // several multi-line items overflows even though it "should" fit by
    // item count alone. The 18-char-per-line cutoff was measured directly
    // against the items column at this font size (table-layout: fixed
    // narrows it more than an unconstrained column would) - when in doubt
    // this rounds up, since wasting a little space is better than an item
    // silently spilling onto an extra label. A Chinese character renders
    // about twice as wide as a Latin one, so it counts double towards that
    // cutoff. These line budgets are estimates - adjust if the packer says
    // a page is wasting space or still cutting items off.
    // A remark takes up its own line on page 1, so leave less room for
    // items when one is present (otherwise the footer alone gets pushed
    // onto a wasted extra blank label).
    $firstPageLineBudget = !empty($transaction->remark) ? 2 : 3;
    $continuationLineBudget = 6;

    $textWidthUnits = function ($text) {
        $text = $text ?? '';
        $cjkCount = preg_match_all('/[\x{3000}-\x{9FFF}\x{F900}-\x{FAFF}\x{FF00}-\x{FFEF}]/u', $text);
        return (mb_strlen($text) - $cjkCount) + ($cjkCount * 2);
    };

    $lineCost = function ($detail) use ($textWidthUnits) {
        $lines = max(1, (int) ceil($textWidthUnits($detail->product_name) / 18));
        return $lines + (!empty($detail->sub_category) ? 1 : 0) + (!empty($detail->second_sub_category) ? 1 : 0);
    };

    // Continuous paper roll variant (see OrderPrintService::buildPackingSlipPdf):
    // the physical page height already grows to fit every item, so there's
    // no fixed-height cutoff to paginate around - everything goes on the one
    // continuous page instead of being split/chunked like the fixed label.
    $continuousRoll = $continuousRoll ?? false;

    $totalQuantity = $details->sum('quantity');

    $pages = collect();
    if ($continuousRoll) {
        $pages->push($details);
    } else {
        $remaining = $details->values();
        $isFirstPage = true;
        while ($remaining->isNotEmpty()) {
            $budget = $isFirstPage ? $firstPageLineBudget : $continuationLineBudget;
            $chunk = collect();
            $used = 0;
            foreach ($remaining as $detail) {
                $cost = $lineCost($detail);
                if ($used > 0 && $used + $cost > $budget) {
                    break;
                }
                $chunk->push($detail);
                $used += $cost;
            }
            $pages->push($chunk->values());
            $remaining = $remaining->slice($chunk->count())->values();
            $isFirstPage = false;
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
    /* "notosanssc"/"notosanstc" come first so Chinese product/customer names
       render correctly - without a font that has CJK glyphs, dompdf falls
       back to a Latin-only core font and prints "?" for every Chinese
       character. Both fonts also cover Latin, so English text is unaffected. */
    /* Explicit line-height everywhere, not just on .items: the Noto Sans
       SC/TC CJK fonts have much taller built-in line metrics than the old
       Open Sans, so any element left to the font's "normal" line-height
       (the header block, footer, etc.) renders noticeably taller than
       before - enough that the header alone can leave no room even for
       the "Page X/Y" footer on the same physical label. */
    body { font-family: "notosanssc", "notosanstc", "Open Sans", Helvetica, Arial, sans-serif; font-size: 7.5pt; color: #000; margin: 0; padding: 0; line-height: 1; }
    /* The fixed die-cut label (8cm x 6cm) needs the heavy 14mm left margin
       to clear its physical mounting edge, but the continuous paper roll
       has no such edge - reusing that same asymmetric margin on the roll
       just pushes everything off-center to the right, cutting off the
       right-hand text (e.g. "Tel:") near the paper's edge. */
    .page-content { margin: {{ $continuousRoll ? '5mm 4mm 5mm 4mm' : '5mm 2mm 5mm 14mm' }}; }
    table { border-collapse: collapse; width: 100%; }
    .header-table td { vertical-align: middle; }
    /* table-layout: fixed + no nowrap so a long customer name/phone number
       wraps onto a second line within its own column instead of the row
       silently growing past the page width - white-space: nowrap doesn't
       get "clipped" at the paper's edge the way you'd expect, it just
       keeps drawing past it, and the printer has no ink to put there. */
    .info-table { font-size: 7.5pt; table-layout: fixed; }
    .info-table td { padding: 0; line-height: 1.3; width: 50%; overflow-wrap: break-word; }
    .order-line { font-size: 11pt; font-weight: 700; margin-bottom: 1pt; }
    .text-right { text-align: right; }
    .logo { max-width: 17pt; max-height: 17pt; }
    h1 { font-size: 9pt; margin: 0; line-height: 1.1; }
    .muted { color: #333; }
    hr { border: none; border-top: 1px dashed #000; margin: 2pt 0; }
    /* Chinese product names have no spaces to wrap at. table-layout: fixed
       forces the column widths below to actually be respected (an auto
       layout table just grows past the page to fit unbreakable content),
       and word-break lets a long name wrap mid-character instead of
       overflowing the label. */
    .items { table-layout: fixed; }
    .items th, .items td { border-bottom: 0.5px solid #999; padding: 1.5pt 2pt; font-size: 9.5pt; text-align: left; vertical-align: top; line-height: 0.85; word-break: break-word; overflow-wrap: break-word; }
    .items th { border-top: 1px solid #000; border-bottom: 1px solid #000; font-size: 9.5pt; }
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

            <div class="order-line">Order: {{ $transaction->transaction_no }}</div>

            <table class="info-table">
                <tr>
                    <td><b>Date:</b> {{ optional($transaction->created_at)->format('d/m/Y') }} {{ optional($transaction->created_at)->format('H:i') }}</td>
                </tr>
                @if($continuousRoll)
                    {{-- Right-aligning Tel here was landing right at (or past) this
                         printer's real printable edge on the paper roll - kept on
                         its own line instead of guessing at a safer x-position. --}}
                    <tr>
                        <td><b>Customer:</b> {{ $transaction->address_name }}</td>
                    </tr>
                    <tr>
                        <td><b>Tel:</b> {{ !empty($transaction->country_code) ? '+'.$transaction->country_code.' ' : '' }}{{ $transaction->phone }}</td>
                    </tr>
                @else
                    <tr>
                        <td><b>Customer:</b> {{ $transaction->address_name }}</td>
                        <td class="text-right"><b>Tel:</b> {{ !empty($transaction->country_code) ? '+'.$transaction->country_code.' ' : '' }}{{ $transaction->phone }}</td>
                    </tr>
                @endif
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

        @if(!$continuousRoll)
            <div class="footer">Page {{ $pageIndex + 1 }} / {{ $totalPages }}</div>
        @endif

      </div>
    </div>
@endforeach

</body>
</html>
