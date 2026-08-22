{{--
    Reusable "authorized signature" block for every printable certificate.
    Styled with inline styles (not the host document's <style> block) on
    purpose: this partial is shared between policies/print.blade.php and the
    entirely separate policies/batch-coc.blade.php document, which have their
    own independent stylesheets, so it needs to render correctly regardless
    of which one includes it.

    Pass `compact` => true for tight layouts (the COC's two-copies-per-page
    layout); omit it for normal-sized certificates.
--}}
@php
    $compact = $compact ?? false;
    $imgHeight = $compact ? 16 : 42;
    $ruleWidth = $compact ? '55%' : '46%';
    $companySize = $compact ? 4.6 : 8.5;
    $nameSize = $compact ? 6.4 : 11.5;
    $titleSize = $compact ? 5.4 : 9.5;
    $captionSize = $compact ? 5 : 8.5;
    $nameMargin = $compact ? 1 : 3;
@endphp
<div style="text-align: center;">
    <p style="margin: 0 0 1px; font-weight: bold; font-size: {{ $companySize }}px; text-transform: uppercase; letter-spacing: 0.02em; color: #111;">
        Stronghold Insurance Company Inc.
    </p>
    <img
        src="{{ \App\Support\PrintLogo::signatureDataUri() }}"
        alt="Authorized Signature"
        style="height: {{ $imgHeight }}px; width: auto; display: block; margin: 0 auto; object-fit: contain;"
    >
    <div style="width: {{ $ruleWidth }}; border-top: 1px solid #111; margin: 0 auto;"></div>
    <p style="margin: {{ $nameMargin }}px 0 0; font-weight: bold; font-size: {{ $nameSize }}px; color: #111;">
        Erchell De Guzman Agdon
    </p>
    <p style="margin: 1px 0 0; font-style: italic; font-size: {{ $titleSize }}px; color: #111;">
        Vice President
    </p>
    <p style="margin: 1px 0 0; font-size: {{ $captionSize }}px; color: #555;">
        Authorized Signature
    </p>
</div>
