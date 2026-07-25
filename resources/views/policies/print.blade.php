<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $policy->online_policy_no }} — {{ ucwords(str_replace('-', ' ', $mode)) }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif; color: #111; font-size: 13px; margin: 0; padding: 32px; }
        .letterhead { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #2a78d6; padding-bottom: 12px; margin-bottom: 20px; }
        .brand { display: flex; align-items: center; gap: 10px; }
        .brand img { width: 44px; height: 44px; object-fit: contain; }
        .letterhead h1 { font-size: 16px; margin: 0; color: #184f95; }
        .letterhead p { margin: 2px 0 0; font-size: 11px; color: #555; }
        .doc-title { text-align: right; }
        .doc-title h2 { margin: 0; font-size: 15px; text-transform: uppercase; letter-spacing: 0.05em; }
        .doc-title p { margin: 2px 0 0; font-size: 11px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 6px 8px; border: 1px solid #ddd; font-size: 12px; }
        th { background: #f2f6fc; }
        .section-title { font-size: 13px; font-weight: bold; margin: 22px 0 6px; color: #184f95; }
        .kv { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px 20px; margin-top: 6px; }
        .kv label { display: block; font-size: 10px; text-transform: uppercase; color: #888; }
        .kv span { font-size: 13px; font-weight: 600; }
        .premium-box { border: 1px solid #ddd; border-radius: 6px; padding: 12px 16px; margin-top: 10px; width: 300px; margin-left: auto; }
        .premium-box .row { display: flex; justify-content: space-between; padding: 3px 0; font-size: 12px; }
        .premium-box .total { border-top: 1px solid #ccc; margin-top: 6px; padding-top: 6px; font-weight: bold; font-size: 13px; }
        ul.wordings { padding-left: 20px; }
        ul.wordings li { margin-bottom: 6px; }
        .footer-note { margin-top: 28px; font-size: 10px; color: #888; }
    </style>
</head>
<body onload="window.print()">
    <div class="letterhead">
        <div class="brand">
            <img src="{{ $logo }}" alt="Stronghold">
            <div>
                <h1>Stronghold</h1>
                <p>InsurApp — SICI Producers' Portal</p>
                <p>Posted to Genweb: {{ $policy->genweb_code }}</p>
            </div>
        </div>
        <div class="doc-title">
            <h2>
                @switch($mode)
                    @case('schedule') Policy Schedule @break
                    @case('coc') Certificate of Cover (COC) @break
                    @case('cov') Certificate of Validation (COV) @break
                    @case('premium-statement') Premium Statement @break
                    @case('jacket') Policy Jacket @break
                @endswitch
            </h2>
            <p>Online Policy No. {{ $policy->online_policy_no }}</p>
        </div>
    </div>

    @if ($mode === 'schedule')
        <div class="kv">
            <div><label>Issued Date</label><span>{{ $policy->issued_at->format('M d, Y g:i A') }}</span></div>
            <div><label>Contract Term From</label><span>{{ $policy->contract_from->format('M d, Y') }}</span></div>
            <div><label>Contract Term To</label><span>{{ $policy->contract_to->format('M d, Y') }}</span></div>
            <div><label>Insured/s</label><span>{{ $policy->motorQuote->policyholders->pluck('name')->join(', ') ?: $policy->producer->full_name }}</span></div>
            <div><label>Producer</label><span>{{ $policy->producer->code }} — {{ $policy->producer->full_name }}</span></div>
        </div>

        <p class="section-title">Premium Due</p>
        <div class="premium-box">
            <div class="row"><span>Net Premium</span><span>₱{{ number_format($policy->motorQuote->net_premium, 2) }}</span></div>
            <div class="row"><span>Documentary Stamps Tax</span><span>₱{{ number_format($policy->motorQuote->doc_stamps_tax, 2) }}</span></div>
            <div class="row"><span>Value Added Tax</span><span>₱{{ number_format($policy->motorQuote->vat, 2) }}</span></div>
            <div class="row"><span>Local Government Tax</span><span>₱{{ number_format($policy->motorQuote->local_govt_tax, 2) }}</span></div>
            <div class="row"><span>LTO DBP-DCI Fee</span><span>₱{{ number_format($policy->motorQuote->lto_dbp_dci_fee, 2) }}</span></div>
            <div class="row"><span>COC Verification</span><span>₱{{ number_format($policy->motorQuote->coc_verification_fee, 2) }}</span></div>
            <div class="row"><span>Others</span><span>₱{{ number_format($policy->motorQuote->other_charges, 2) }}</span></div>
            <div class="row total"><span>Total Premium</span><span>₱{{ number_format($policy->motorQuote->total_premium, 2) }}</span></div>
        </div>
    @endif

    @if (in_array($mode, ['coc', 'cov']))
        <div class="kv">
            <div><label>Risk/s Insured</label><span>1</span></div>
            <div><label>Vehicle</label><span>{{ $policy->motorQuote->vehicle_title }}</span></div>
            <div><label>Plate No.</label><span>{{ $policy->motorQuote->plate_no }}</span></div>
            <div><label>COC No.</label><span>{{ $policy->coc_no }}</span></div>
            <div><label>Authentication No.</label><span>{{ $policy->authentication_no }}</span></div>
            <div><label>Registration Type</label><span>{{ $policy->motorQuote->lto_registration_type }}</span></div>
        </div>
    @endif

    @if ($mode === 'premium-statement')
        <table>
            <thead>
                <tr><th>Item</th><th>Amount</th></tr>
            </thead>
            <tbody>
                <tr><td>Net Premium</td><td>₱{{ number_format($policy->motorQuote->net_premium, 2) }}</td></tr>
                <tr><td>Documentary Stamps Tax</td><td>₱{{ number_format($policy->motorQuote->doc_stamps_tax, 2) }}</td></tr>
                <tr><td>Value Added Tax</td><td>₱{{ number_format($policy->motorQuote->vat, 2) }}</td></tr>
                <tr><td>Local Government Tax</td><td>₱{{ number_format($policy->motorQuote->local_govt_tax, 2) }}</td></tr>
                <tr><td>LTO DBP-DCI Fee</td><td>₱{{ number_format($policy->motorQuote->lto_dbp_dci_fee, 2) }}</td></tr>
                <tr><td>COC Verification</td><td>₱{{ number_format($policy->motorQuote->coc_verification_fee, 2) }}</td></tr>
                <tr><td>Others</td><td>₱{{ number_format($policy->motorQuote->other_charges, 2) }}</td></tr>
                <tr><th>Total Premium</th><th>₱{{ number_format($policy->motorQuote->total_premium, 2) }}</th></tr>
            </tbody>
        </table>
    @endif

    @if ($mode === 'jacket')
        <p class="section-title">Motor Vehicle Insurance — {{ $policy->motorQuote->vehicle_class }}</p>
        <ul class="wordings">
            <li>Section I - Liability</li>
            <li>Section II - No Fault Indemnity</li>
            <li>General Exceptions</li>
            <li>Definitions</li>
            <li>Conditions</li>
            <li>Nuclear Exclusions</li>
            <li>Waiver Clause</li>
            <li>Short Period Rate Scale</li>
        </ul>
    @endif

    <p class="footer-note">
        This document is system-generated by InsurApp for demonstration purposes and is not a legally binding insurance
        document. Copyright &copy; 2018-2026 Stronghold. All rights reserved.
    </p>
</body>
</html>
