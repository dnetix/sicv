<?php

namespace App\Support;

/**
 * Builds the glyph string that renders as a Code 128 (set B) barcode with
 * the bundled `Code 128` webfont — a direct port of the legacy generator,
 * so printed barcodes stay identical to the ones in circulation.
 */
class Code128
{
    private const int DIFF = 32;

    private const int BIG_DIFF = 100;

    private const int START_B = 204;

    private const int STOP = 206;

    private const int CHECK_MOD = 103;

    public static function encode(string $value): string
    {
        $checksum = 104;
        $encoded = chr(self::START_B);

        foreach (str_split($value) as $index => $char) {
            $checksum += (ord($char) - self::DIFF) * ($index + 1);
            $encoded .= $char;
        }

        $checksum %= self::CHECK_MOD;
        $encoded .= chr($checksum + ($checksum > 94 ? self::BIG_DIFF : self::DIFF));
        $encoded .= chr(self::STOP);

        // The font maps the control glyphs in the Latin-1 range; Blade
        // outputs UTF-8, so re-encode the raw single-byte string.
        return mb_convert_encoding($encoded, 'UTF-8', 'ISO-8859-1');
    }
}
