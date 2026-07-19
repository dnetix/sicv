<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
class ItemType extends Model
{
    use HasFactory;

    /**
     * Legacy id of the "Oro" type: gold contracts require a weight and can
     * be scrapped instead of moved to the store on foreclosure.
     */
    public const int GOLD = 2;

    public function isGold(): bool
    {
        return $this->id === self::GOLD;
    }

    /**
     * Fetch-or-create with an EXPLICIT id. firstOrCreate would silently drop
     * the guarded id and let auto-increment pick another one; catalog ids
     * are fixed (gold must stay id 2), so they are forced.
     */
    public static function ensure(int $id, string $name): self
    {
        return self::query()->findOr($id, fn () => self::query()->forceCreate([
            'id' => $id,
            'name' => $name,
        ]));
    }
}
