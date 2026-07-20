<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Batch COC</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif; color: #111; font-size: 13px; margin: 0; }
        .page { padding: 32px; page-break-after: always; }
        .page:last-child { page-break-after: auto; }
        .letterhead { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #2a78d6; padding-bottom: 12px; margin-bottom: 20px; }
        .letterhead h1 { font-size: 16px; margin: 0; color: #184f95; }
        .letterhead p { margin: 2px 0 0; font-size: 11px; color: #555; }
        .doc-title { text-align: right; }
        .doc-title h2 { margin: 0; font-size: 15px; text-transform: uppercase; letter-spacing: 0.05em; }
        .kv { display: flex; flex-wrap: wrap; gap: 16px; margin-top: 6px; }
        .kv div { min-width: 200px; margin-bottom: 8px; }
        .kv label { display: block; font-size: 10px; text-transform: uppercase; color: #888; }
        .kv span { font-size: 13px; font-weight: 600; }
    </style>
</head>
<body>
    @foreach ($policies as $policy)
        <div class="page">
            <div class="letterhead">
                <div>
                    <h1>Fortune General Insurance Corp.</h1>
                    <p>InsurApp — FGIC Producers' Portal</p>
                    <p>Posted to Genweb: {{ $policy->genweb_code }}</p>
                </div>
                <div class="doc-title">
                    <h2>Certificate of Cover (COC)</h2>
                    <p>Online Policy No. {{ $policy->online_policy_no }}</p>
                </div>
            </div>

            <div class="kv">
                <div><label>Vehicle</label><span>{{ $policy->motorQuote->vehicle_title }}</span></div>
                <div><label>Plate No.</label><span>{{ $policy->motorQuote->plate_no }}</span></div>
                <div><label>COC No.</label><span>{{ $policy->coc_no }}</span></div>
                <div><label>Authentication No.</label><span>{{ $policy->authentication_no }}</span></div>
                <div><label>Registration Type</label><span>{{ $policy->motorQuote->lto_registration_type }}</span></div>
                <div><label>Issued Date</label><span>{{ $policy->issued_at->format('M d, Y g:i A') }}</span></div>
                <div><label>Contract Term</label><span>{{ $policy->contract_from->format('M d, Y') }} — {{ $policy->contract_to->format('M d, Y') }}</span></div>
                <div><label>Producer</label><span>{{ $policy->producer->code }} — {{ $policy->producer->full_name }}</span></div>
            </div>
        </div>
    @endforeach
</body>
</html>
