<?php  namespace SICV\Sales;

use Eloquent;
use SICV\Articles\Article;

/**
 * Represents an article to sell.
 *
 * @property long $id
 * @property integer $buy_price
 * @property integer $sell_price
 * @property integer $article_id
 *
 * @package SICV\Sales
 */
class Product extends Eloquent {

    protected $table = 'products';
    protected $fillable = ['buy_price', 'sell_price', 'article_id'];

    public $timestamps = false;

    public function article(){
        return $this->belongsTo(Article::class);
    }

}