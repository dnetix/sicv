<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['legal_name', 'tax_id', 'name', 'address', 'phone', 'city', 'logo_path'])]
class CompanySetting extends Model
{
    /**
     * The single row holding the company identity printed on every document.
     * Created with placeholder values if missing (fresh install) so the
     * layout, which shows the company name on every page, can always render.
     */
    public static function current(): self
    {
        return self::query()->firstOr(fn () => self::query()->create([
            'legal_name' => 'Compraventa S.A.S.',
            'tax_id' => '000000000-0',
            'name' => 'Compraventa',
            'address' => 'Calle 1 # 1-1',
            'phone' => '000 000 0000',
            'city' => 'Ciudad',
        ]));
    }
}
