@php
    $insured = $policy->motorQuote->policyholders->firstWhere('use_as_address', true)
        ?? $policy->motorQuote->policyholders->first();
    $insuredName = $insured->name ?? $policy->producer->full_name;
    $insuredAddress = $insured->address ?? '';
@endphp
<div class="coc-doc">
    <table class="coc-grid">
        <tr>
            <td rowspan="4" style="width: 46%;">
                <label>Name and Address of Insured</label>
                <span>{{ $insuredName }}@if($insuredAddress)<br>{{ $insuredAddress }}@endif</span>
            </td>
            <td style="width: 27%;"><label>Business/Profession</label></td>
            <td style="width: 27%;"><label>Confirmation of Cover</label></td>
        </tr>
        <tr>
            <td><label>Date Issued</label><span>{{ $policy->issued_at->format('M d, Y') }}</span></td>
            <td><label>Official Receipt No.</label></td>
        </tr>
        <tr>
            <td colspan="2" class="header-cell">Period of Insurance</td>
        </tr>
        <tr>
            <td><label>From 12:00 Noon</label><span>{{ $policy->contract_from->format('M d, Y') }}</span></td>
            <td><label>To 12:00 Noon</label><span>{{ $policy->contract_to->format('M d, Y') }}</span></td>
        </tr>
    </table>
</div>
