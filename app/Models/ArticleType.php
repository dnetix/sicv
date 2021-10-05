<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleType extends Model
{
    use HasFactory;

    protected $table = 'article_types';
    public $timestamps = false;

    public const GOLD_ID = 3;
    protected $fillable = [
        'article_type',
        'article_type_id',
    ];

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->article_type;
    }

    public function parentId(): int
    {
        return $this->article_type_id;
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'article_type_id');
    }
}
