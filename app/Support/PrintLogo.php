<?php

namespace App\Support;

/**
 * Resolves the Stronghold logo as a base64 data URI for use in print/PDF
 * views. DomPDF has `enable_remote` disabled by default, so it can't fetch
 * `/logo/...` as an HTTP or filesystem path — a data URI works identically
 * whether the view is rendered directly in the browser or through DomPDF.
 */
class PrintLogo
{
    private static ?string $dataUri = null;

    private static ?string $signatureDataUri = null;

    public static function dataUri(): string
    {
        return self::$dataUri ??= 'data:image/png;base64,'.base64_encode(
            file_get_contents(public_path('logo/stronghold_logo.png'))
        );
    }

    public static function signatureDataUri(): string
    {
        return self::$signatureDataUri ??= 'data:image/png;base64,'.base64_encode(
            file_get_contents(public_path('logo/vp_signature.png'))
        );
    }
}
