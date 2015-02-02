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
 * @property integer $contract_id
 * @property integer $quantity
 *
 * @package SICV\Sales
 */
class Product extends Eloquent {

    protected $table = 'products';
    protected $fillable = ['buy_price', 'sell_price', 'article_id', 'contract_id', 'quantity'];

    public $timestamps = false;

    public function id(){
        return $this->id;
    }

    public function setBuyPrice($buyPrice){
        $this->buy_price = $buyPrice;
        return $this;
    }

    public function setSellPrice($sellPrice){
        $this->sell_price = $sellPrice;
        return $this;
    }

    public function setArticleId($articleId){
        $this->article_id = $articleId;
        return $this;
    }

    public function setContractId($contractId){
        $this->contract_id = $contractId;
        return $this;
    }

    public function setQuantity($quantity){
        $this->quantity = $quantity;
        return $this;
    }

    /* ----------- Relationships --------------- */

    public function scopeAvailable($query){
        return $query->where('quantity', '>', 0);
    }

    public function article(){
        return $this->belongsTo(Article::class);
    }

    public function invoices(){
        return $this->belongsToMany(Invoice::class);
    }

}