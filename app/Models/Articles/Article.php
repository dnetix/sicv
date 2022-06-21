<?php

namespace App\Models\Articles;

use App\Models\Contracts\Contract;
use App\Models\Utils\Presenters\PresentableTrait;
use App\Presenters\ArticlePresenter;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use PresentableTrait;

    protected $presenter = ArticlePresenter::class;

    protected $table = 'articles';
    public $timestamps = false;

    protected $fillable = [
        'description',
        'location',
        'weight',
        'article_type_id',
    ];

    public function id()
    {
        return $this->id;
    }

    public function description()
    {
        return $this->description;
    }

    public function location()
    {
        return $this->location;
    }

    public function weight()
    {
        return $this->weight;
    }

    public function articleTypeId()
    {
        return $this->article_type_id;
    }

    public function isGold()
    {
        return $this->articleTypeId() == ArticleType::GOLD_ID;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * Should only be call if has a relationship with a contract.
     * @return mixed
     */
    public function articleAmount()
    {
        return $this->pivot->article_amount;
    }

    public function articleType()
    {
        return $this->belongsTo(ArticleType::class);
    }

    public function contracts()
    {
        return $this->belongsToMany(Contract::class);
    }
}
