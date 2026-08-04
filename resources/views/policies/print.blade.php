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
        .footer-note { margin-top: 16px; font-size: 10px; color: #888; }

        /* Certificate of Cover — modeled on the physical Stronghold COC form */
        .coc-doc { font-size: 11px; }
        .coc-doc .coc-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; }
        .coc-doc .coc-company { display: flex; align-items: center; gap: 10px; border: 2px solid #111; padding: 8px 12px; width: 58%; }
        .coc-doc .coc-company img { width: 56px; height: 56px; object-fit: contain; flex-shrink: 0; }
        .coc-doc .coc-company h1 { margin: 0; font-size: 19px; letter-spacing: 0.03em; }
        .coc-doc .coc-company p { margin: 1px 0 0; font-size: 9px; color: #333; }
        .coc-doc .coc-company p.tagline { font-weight: bold; font-size: 11px; margin-top: 2px; }
        .coc-doc .coc-doctitle { width: 40%; text-align: center; }
        .coc-doc .coc-doctitle .original { font-size: 19px; font-weight: bold; margin: 0 0 4px; }
        .coc-doc .coc-doctitle h2 { margin: 0; font-size: 15px; text-transform: uppercase; }
        .coc-doc .coc-doctitle h3 { margin: 3px 0 0; font-size: 10px; font-weight: normal; text-transform: uppercase; line-height: 1.4; }
        .coc-doc .coc-number { margin-top: 10px; font-size: 20px; font-weight: bold; }

        .coc-doc .coc-policyno { text-align: right; font-size: 10px; margin: 6px 0 10px; }
        .coc-doc .coc-policyno label { display: inline; text-transform: uppercase; color: #555; margin-right: 6px; }
        .coc-doc .coc-policyno span { border-bottom: 1px solid #111; padding: 0 4px 1px; font-weight: 600; }

        .coc-doc table.coc-grid { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .coc-doc table.coc-grid td { border: 1px solid #111; vertical-align: top; padding: 5px 8px; height: 26px; }
        .coc-doc table.coc-grid td label { display: block; font-size: 8.5px; text-transform: uppercase; color: #555; margin-bottom: 2px; }
        .coc-doc table.coc-grid td span { font-size: 11px; font-weight: 600; }
        .coc-doc table.coc-grid td.header-cell { text-align: center; font-size: 9.5px; font-weight: bold; text-transform: uppercase; background: #f2f6fc; }

        .coc-doc .section-bar { background: #184f95; color: #fff; font-weight: bold; text-align: center; padding: 4px; font-size: 11px; letter-spacing: 0.05em; text-transform: uppercase; margin-top: -1px; }

        .coc-doc table.coc-liability { width: 100%; border-collapse: collapse; margin-top: -1px; }
        .coc-doc table.coc-liability td { border: 1px solid #111; padding: 8px 10px; vertical-align: middle; }
        .coc-doc .liability-label { font-weight: bold; font-size: 11px; margin: 0; }
        .coc-doc .liability-title { text-align: center; font-size: 14px; font-weight: bold; margin: 10px 0; }
        .coc-doc .liability-sub { font-size: 8.5px; font-style: italic; color: #333; margin: 0; }
        .coc-doc .amounts-col { text-align: center; font-weight: bold; font-size: 10px; line-height: 1.5; }
        .coc-doc .amount-row .amt-label { display: inline-block; width: 58%; vertical-align: middle; font-weight: bold; font-size: 11px; }
        .coc-doc .amount-row .amt-label .amt-sub { display: block; font-weight: normal; font-size: 8.5px; }
        .coc-doc .amount-row .amt-value { display: inline-block; width: 40%; vertical-align: middle; text-align: right; font-size: 15px; font-weight: bold; }

        .coc-doc .coc-footer { margin-top: 14px; display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; }
        .coc-doc .coc-footer .legal { width: 62%; font-size: 9px; line-height: 1.5; }
        .coc-doc .signature-line { width: 32%; text-align: center; border-top: 1px solid #111; padding-top: 4px; font-size: 9px; }

        /* Policy Schedule — modern recreation of the physical Stronghold policy schedule form */
        .schedule-doc { font-size: 11px; }
        .schedule-doc .sched-header { display: flex; justify-content: space-between; align-items: stretch; gap: 16px; border: 1px solid #d7e0ef; border-radius: 8px; overflow: hidden; }
        .schedule-doc .sched-company { display: flex; align-items: center; gap: 10px; padding: 12px 14px; width: 60%; background: #fff; }
        .schedule-doc .sched-company img { width: 48px; height: 48px; object-fit: contain; flex-shrink: 0; }
        .schedule-doc .sched-company h1 { margin: 0; font-size: 17px; letter-spacing: 0.03em; color: #184f95; }
        .schedule-doc .sched-company p { margin: 1px 0 0; font-size: 9px; color: #555; }
        .schedule-doc .sched-company p.tagline { font-weight: bold; font-size: 10px; color: #333; margin-top: 2px; }
        .schedule-doc .sched-doctitle { width: 40%; background: #184f95; color: #fff; padding: 12px 16px; text-align: right; }
        .schedule-doc .sched-doctitle h2 { margin: 0; font-size: 15px; text-transform: uppercase; letter-spacing: 0.04em; }
        .schedule-doc .sched-doctitle p.vehicle-tag { margin: 3px 0 0; font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.03em; color: #cfe0f7; }
        .schedule-doc .sched-doctitle .sched-policyno { margin-top: 10px; font-size: 9px; text-transform: uppercase; color: #cfe0f7; }
        .schedule-doc .sched-doctitle .sched-policyno span { display: block; font-size: 15px; font-weight: bold; color: #fff; letter-spacing: 0.03em; margin-top: 1px; }

        .schedule-doc .section-bar.small { font-size: 10px; padding: 3px; margin-top: 14px; border-radius: 3px; }
        .schedule-doc .not-covered { color: #9aa4b2; font-style: italic; font-weight: 500 !important; }
        .schedule-doc .not-applicable { text-align: center; font-style: italic; color: #9aa4b2; padding: 8px; border: 1px solid #111; border-top: none; margin: -1px 0 0; font-size: 11px; }

        .schedule-doc .sched-columns { display: flex; gap: 14px; margin-top: 14px; align-items: flex-start; }
        .schedule-doc .sched-col-left { width: 56%; }
        .schedule-doc .sched-col-right { width: 44%; }

        .schedule-doc .premium-card { border: 1px solid #d7e0ef; border-radius: 8px; overflow: hidden; }
        .schedule-doc .premium-card .premium-card-title { background: #eef3fb; color: #184f95; font-weight: bold; font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.03em; padding: 6px 12px; }
        .schedule-doc .premium-card .row { display: flex; justify-content: space-between; padding: 5px 12px; font-size: 11px; border-top: 1px solid #eef1f6; }
        .schedule-doc .premium-card .row.sub { padding-left: 20px; color: #555; font-size: 10.5px; }
        .schedule-doc .premium-card .row.group-label { padding-bottom: 0; font-weight: 600; color: #333; }
        .schedule-doc .premium-card .row.total { border-top: 2px solid #184f95; background: #eef3fb; font-weight: bold; font-size: 12.5px; color: #184f95; padding: 8px 12px; }

        .schedule-doc .legal-block { margin-top: 16px; font-size: 9.5px; line-height: 1.6; color: #333; }
        .schedule-doc .legal-block p { margin: 0 0 8px; }
        .schedule-doc .legal-block strong { color: #111; }
        .schedule-doc .legal-block ol { margin: 4px 0 8px 18px; padding: 0; }

        .schedule-doc .sched-signoff { margin-top: 16px; display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; }
        .schedule-doc .sched-signoff p { width: 62%; font-size: 9.5px; line-height: 1.5; margin: 0; }
        .schedule-doc .sched-signoff .signature-line { width: 32%; text-align: center; border-top: 1px solid #111; padding-top: 4px; font-size: 9px; }
    </style>
</head>
<body onload="window.print()">
    @if ($mode === 'coc')
        <div class="coc-doc">
            <div class="coc-header">
                <div class="coc-company">
                    <img src="{{ $logo }}" alt="Stronghold">
                    <div>
                        <h1>STRONGHOLD</h1>
                        <p class="tagline">INSURANCE COMPANY, INCORPORATED</p>
                        <p>17th Floor, Security Bank Centre, 6776 Ayala Avenue, Makati City, Philippines</p>
                        <p>Tel. Nos.: 8891-1329 to 37 &bull; Fax Nos. 8891-1640; 8891-1326; 8891-1383</p>
                        <p>TIN: 000-602-270-00000 VAT</p>
                    </div>
                </div>
                <div class="coc-doctitle">
                    <p class="original">&ldquo;ORIGINAL COPY&rdquo;</p>
                    <h2>Confirmation of Cover</h2>
                    <h3>Non-Land Transportation Operators<br>Vehicle</h3>
                    <p class="coc-number">No. {{ $policy->coc_no }}</p>
                </div>
            </div>

            <div class="coc-policyno"><label>Policy No.</label><span>{{ $policy->online_policy_no }}</span></div>

            @include('policies.partials.insured-details-grid', ['policy' => $policy])

            @include('policies.partials.scheduled-vehicle', ['policy' => $policy])

            @include('policies.partials.liability-box', ['policy' => $policy])

            <div class="coc-footer">
                <p class="legal">
                    This Confirmation of Cover is evidence of the policy of insurance required under Chapter VI &ndash;
                    Compulsory Motor Vehicle Liability Insurance, of the Insurance Code, as amended by Presidential Decree No. 1814.
                </p>
                <div class="signature-line">Authorized Signature</div>
            </div>
        </div>
    @elseif ($mode === 'schedule')
        <div class="coc-doc schedule-doc">
            <div class="sched-header">
                <div class="sched-company">
                    <img src="{{ $logo }}" alt="Stronghold">
                    <div>
                        <h1>STRONGHOLD</h1>
                        <p class="tagline">INSURANCE COMPANY, INCORPORATED</p>
                        <p>17th Floor, Security Bank Centre, 6776 Ayala Avenue, Makati City, Philippines</p>
                        <p>Tel. Nos.: 8891-1329 to 37 &bull; Fax Nos. 8891-1640; 8891-1326; 8891-1383</p>
                    </div>
                </div>
                <div class="sched-doctitle">
                    <h2>Policy Schedule</h2>
                    <p class="vehicle-tag">{{ $policy->motorQuote->vehicle_class }} &bull; CTPL</p>
                    <div class="sched-policyno">Policy No.<span>{{ $policy->online_policy_no }}</span></div>
                </div>
            </div>

            @include('policies.partials.insured-details-grid', ['policy' => $policy])

            @include('policies.partials.scheduled-vehicle', ['policy' => $policy])

            @include('policies.partials.liability-box', ['policy' => $policy])

            <div class="sched-columns">
                <div class="sched-col-left">
                    <div class="section-bar small">Section III</div>
                    <table class="coc-grid">
                        <tr>
                            <td colspan="2"><label>Insured's Estimate of Value of Scheduled Vehicle</label><span class="not-covered">Not Covered</span></td>
                        </tr>
                        <tr>
                            <td colspan="2"><label>Accessories</label><span class="not-covered">Not Covered</span></td>
                        </tr>
                        <tr>
                            <td><label>Deductible</label><span>Nil</span></td>
                            <td><label>Towing</label><span>Nil</span></td>
                        </tr>
                        <tr>
                            <td colspan="2"><label>Authorized Repair Limit</label><span>Nil</span></td>
                        </tr>
                    </table>

                    <div class="section-bar small">Section IV</div>
                    <table class="coc-grid">
                        <tr>
                            <td><label>Bodily Injury</label><span class="not-covered">Not Covered</span></td>
                            <td><label>Property Damage</label><span class="not-covered">Not Covered</span></td>
                        </tr>
                    </table>

                    <div class="section-bar small">Mortgagee</div>
                    <p class="not-applicable">Not Applicable</p>
                </div>

                <div class="sched-col-right">
                    <div class="premium-card">
                        <div class="premium-card-title">Premiums</div>
                        <div class="row"><span>Section I/II Premium</span><span>₱{{ number_format($policy->motorQuote->net_premium, 2) }}</span></div>
                        <div class="row group-label"><span>Others</span></div>
                        <div class="row sub"><span>LTO DBP-DCI Fee</span><span>₱{{ number_format($policy->motorQuote->lto_dbp_dci_fee, 2) }}</span></div>
                        <div class="row sub"><span>COC Verification Fee</span><span>₱{{ number_format($policy->motorQuote->coc_verification_fee, 2) }}</span></div>
                        <div class="row sub"><span>Other Charges</span><span>₱{{ number_format($policy->motorQuote->other_charges, 2) }}</span></div>
                        <div class="row"><span>Documentary Stamps Tax</span><span>₱{{ number_format($policy->motorQuote->doc_stamps_tax, 2) }}</span></div>
                        <div class="row"><span>Value Added Tax</span><span>₱{{ number_format($policy->motorQuote->vat, 2) }}</span></div>
                        <div class="row"><span>Local Government Tax</span><span>₱{{ number_format($policy->motorQuote->local_govt_tax, 2) }}</span></div>
                        <div class="row total"><span>Total Amount Due</span><span>₱{{ number_format($policy->motorQuote->total_premium, 2) }}</span></div>
                    </div>
                </div>
            </div>

            <div class="section-bar small">Forms and Endorsements Made Part of This Policy at Time of Issue</div>
            <p class="not-applicable">None</p>

            <div class="legal-block">
                <p>
                    <strong>Authorized Driver:</strong> Any of the following: (a) the Insured; (b) any person driving on the Insured's
                    order or with his permission, provided that the person driving is permitted, in accordance with the licensing law
                    or other regulations, to drive the Scheduled Vehicle, or has been permitted and is not disqualified by order of a
                    Court of Law or by reason of any enactment or regulation in that behalf, provided that for Sections I and II only
                    of this Policy an authorized driver shall include a duly licensed driver whose license at the time of the accident
                    had expired.
                </p>
                <p>
                    <strong>Limitations as to Use:</strong> Use only for social, domestic and pleasure purposes, and for the Insured's
                    business or profession. This policy does not cover use for (1) the hauling and/or carrying of logs, lumber, sand,
                    gravel, bottled beverages, gasoline products and/or other inflammable articles or materials; (2) racing,
                    pacemaking, reliability trial or speed testing; (3) the carriage of passengers for hire or reward; (4) any purpose
                    in connection with the Motor Trade.
                </p>
                <p>
                    Sections I and II of this Policy cover THIRD PARTY liability arising from bodily injury and/or death in amounts
                    set forth under the Schedule of Indemnities.
                </p>
            </div>

            <div class="sched-signoff">
                <p>
                    IN WITNESS WHEREOF, the Company has caused this Policy to be signed by its duly authorized officer/representative
                    at Makati City, Philippines this {{ $policy->issued_at->format('jS \d\a\y \o\f F, Y') }}.
                </p>
                <div class="signature-line">Authorized Signature</div>
            </div>
        </div>
    @else
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
                        @case('cov') Certificate of Validation (COV) @break
                        @case('premium-statement') Premium Statement @break
                        @case('jacket') Policy Jacket @break
                    @endswitch
                </h2>
                <p>Online Policy No. {{ $policy->online_policy_no }}</p>
            </div>
        </div>

        @if ($mode === 'cov')
            <div class="kv">
                <div><label>Risk/s Insured</label><span>1</span></div>
                <div><label>Vehicle</label><span>{{ $policy->motorQuote->vehicle_title }}</span></div>
                <div><label>Plate No.</label><span>{{ $policy->motorQuote->plate_no }}</span></div>
                <div><label>COC No.</label><span>{{ $policy->coc_no }}</span></div>
                <div><label>Authentication No.</label><span>{{ $policy->authentication_no }}</span></div>
                <div><label>Registration Type</label><span>{{ $policy->motorQuote->lto_registration_type }}</span></div>
            </div>

            @include('policies.partials.scheduled-vehicle', ['policy' => $policy])
        @endif
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
