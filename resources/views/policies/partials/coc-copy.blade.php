<div class="coc-doc compact">
    <table class="coc-header-table">
        <tr>
            <td class="coc-company-cell">
                <table class="coc-company-inner">
                    <tr>
                        <td class="coc-logo-cell"><img src="{{ $logo }}" alt="Stronghold"></td>
                        <td class="coc-company">
                            <h1>STRONGHOLD</h1>
                            <p class="tagline">INSURANCE COMPANY, INCORPORATED</p>
                            <p>17th Floor, Security Bank Centre, 6776 Ayala Avenue, Makati City, Philippines</p>
                            <p>Tel. Nos.: 8891-1329 to 37 &bull; Fax Nos. 8891-1640; 8891-1326; 8891-1383</p>
                            <p>TIN: 000-602-270-00000 VAT</p>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="coc-doctitle-cell">
                <div class="coc-doctitle">
                    <p class="original">&ldquo;{{ strtoupper($copyLabel) }}&rdquo;</p>
                    <h2>Confirmation of Cover</h2>
                    <h3>Non-Land Transportation Operators<br>Vehicle</h3>
                    <p class="coc-number">No. {{ $policy->coc_no }}</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="coc-policyno"><label>Policy No.</label><span>{{ $policy->online_policy_no }}</span></div>
    <div class="coc-policyno coc-authno"><label>Authentication No.</label><span>{{ $policy->authentication_no }}</span></div>

    @include('policies.partials.insured-details-grid', ['policy' => $policy])

    @include('policies.partials.scheduled-vehicle', ['policy' => $policy])

    @include('policies.partials.liability-box', ['policy' => $policy])

    <table class="coc-footer-table">
        <tr>
            <td class="coc-footer-legal-cell coc-footer">
                <p class="legal">
                    This Confirmation of Cover is evidence of the policy of insurance required under Chapter VI &ndash;
                    Compulsory Motor Vehicle Liability Insurance, of the Insurance Code, as amended by Presidential Decree No. 1814.
                </p>
            </td>
            <td class="coc-footer-sign-cell">
                <div class="signature-line">Authorized Signature</div>
            </td>
        </tr>
    </table>
</div>
