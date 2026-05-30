@php
    $fmt = fn($amount) => number_format((float) $amount, 2) . ' ' . ($invoice['currency'] ?? 'SAR');
    $feeLines = $invoice['fee_lines'] ?? [];
    $discountLines = collect($invoice['discount_lines'] ?? [])->filter(fn($l) => empty($l['is_summary']));
    $totalDiscount = (float) ($invoice['total_discount'] ?? 0);
    $showSummaryDiscount = $totalDiscount > 0 && $discountLines->isEmpty();
    $rowNum = count($feeLines);
@endphp
<table class="header-table">
        <tr>
            <td style="width:60%">
                <div class="logos">
                    <strong style="font-size:16px;color:#0f6cc8">{{ $invoice['brand_label'] ?? 'Pioneers Edu' }}</strong>
                    @if(!empty($invoice['school_logo_path']))
                        <span class="divider"></span>
                        <img src="{{ $invoice['school_logo_path'] }}" alt="School" />
                    @endif
                </div>
                <p class="title" style="margin-top:14px">{{ $invoice['document_title'] ?? 'View invoice' }}</p>
                <p class="sub">{{ $invoice['school_name'] ?? '' }}</p>
            </td>
            <td style="width:40%;text-align:right">
                <p class="title">{{ $invoice['copy_label'] ?? 'Student Copy' }}</p>
                <div class="badge">#{{ $invoice['reference_no'] ?? '' }}</div>
            </td>
        </tr>
    </table>

    <table class="summary-table">
        <tr>
            <td><p class="card-label">Total</p><p class="card-value">{{ $fmt($invoice['total'] ?? 0) }}</p></td>
            <td><p class="card-label">Start Date</p><p class="card-value">{{ $invoice['start_date'] ?? '—' }}</p></td>
            <td><p class="card-label">{{ $invoice['reference_label'] ?? 'Booking #' }}</p><p class="card-value">#{{ $invoice['reference_no'] ?? '' }}</p></td>
        </tr>
    </table>

    <table class="fees">
        <thead><tr><th>#</th><th>Booking Summary</th><th>Total</th></tr></thead>
        <tbody>
            @forelse($feeLines as $index => $line)
                <tr>
                    <td style="text-align:center">{{ $index + 1 }}</td>
                    <td>
                        {{ $line['label'] ?? 'Item' }}
                        @if(!empty($line['detail'])) ({{ $line['detail'] }}) @endif
                    </td>
                    <td style="text-align:right">{{ $fmt($line['amount'] ?? 0) }}</td>
                </tr>
            @empty
                <tr><td style="text-align:center">1</td><td>{{ $invoice['course_name'] ?? 'Course' }}</td><td style="text-align:right">—</td></tr>
            @endforelse
            @foreach($discountLines as $index => $line)
                <tr class="discount-row">
                    <td style="text-align:center">{{ count($feeLines) + $index + 1 }}</td>
                    <td>{{ $line['label'] ?? 'Discount' }}</td>
                    <td style="text-align:right">{{ $fmt($line['amount'] ?? 0) }}</td>
                </tr>
            @endforeach
            @if($showSummaryDiscount)
                <tr class="discount-row">
                    <td style="text-align:center">{{ $rowNum + 1 }}</td>
                    <td>Total Discount</td>
                    <td style="text-align:right">{{ $fmt(-1 * $totalDiscount) }}</td>
                </tr>
            @endif
            <tr class="invoice-total">
                <td></td>
                <td>Total (includes all fees)</td>
                <td style="text-align:right">{{ $fmt($invoice['total'] ?? 0) }}</td>
            </tr>
        </tbody>
    </table>

    @if($invoice['show_contact'] ?? false)
        <div class="contact-section">
            <h2 class="section-title">Contact Us</h2>
            <p class="muted">If you need help, you can contact us using the details below</p>
            <div class="contact-row"><strong>Email:</strong> booking@pioneersedu.com</div>
            <div class="contact-row"><strong>WhatsApp:</strong> +966 55 002 7268</div>
        </div>
    @endif

    @if($invoice['show_payment'] ?? false)
        <div class="payment-section">
            <h2 class="section-title">Payment Methods</h2>
            <p class="muted">You can pay via bank transfer to any of the following banks</p>
            <table class="banks-table"><tr>
                @foreach($invoice['banks'] ?? [] as $bank)
                    <td>
                        <div class="bank-card">
                            <div class="bank-logo">
                                @if(!empty($bank['logo_path']))
                                    <img src="{{ $bank['logo_path'] }}" style="max-height:44px;max-width:120px" alt="" />
                                @else
                                    {{ $bank['logo_text'] ?? $bank['name'] }}
                                @endif
                            </div>
                            <div class="bank-body">
                                Beneficiary: {{ $bank['beneficiary'] ?? '' }}<br>
                                Account: {{ $bank['account_number'] ?? '' }}<br>
                                IBAN: {{ $bank['iban'] ?? '' }}
                            </div>
                        </div>
                    </td>
                @endforeach
            </tr></table>
        </div>
    @endif

    @if(($invoice['copy_label'] ?? '') === 'School Copy')
        <p class="school-note">For partner school use only. Internal pricing copy — commissions, referrals, and Pioneers discounts are excluded.</p>
    @endif
