<?php  namespace SICV\Budgets;

use Eloquent;
use SICV\Presenters\ExpensePresenter;
use SICV\Users\User;
use SICV\Utils\Presenters\PresentableTrait;

/**
 * Class Expense
 *
 * @property integer id
 * @property integer $amount
 * @property String $created_at
 * @property String $description
 * @property integer $expense_type_id
 * @property integer $user_id
 * @package SICV\Budgets
 */
class Expense extends Eloquent {

    protected $presenter = ExpensePresenter::class;
    use PresentableTrait;

    protected $table = 'expenses';
    protected $fillable = [
        'amount',
        'created_at',
        'description',
        'expense_type_id',
        'user_id'
    ];

    public function id(){
        return $this->id;
    }

    public function amount(){
        return $this->amount;
    }

    public function createdAt(){
        return $this->created_at;
    }

    public function description(){
        return $this->description;
    }

    public function expenseTypeId(){
        return $this->expense_type_id;
    }

    public function userId(){
        return $this->user_id;
    }

    public function setAmount($amount){
        $this->amount = $amount;
        return $this;
    }
    public function setCreatedAt($created_at){
        $this->created_at = $created_at;
        return $this;
    }
    public function setDescription($description){
        $this->description = $description;
        return $this;
    }
    public function setExpenseTypeId($expense_type_id){
        $this->expense_type_id = $expense_type_id;
        return $this;
    }
    public function setUserId($user_id){
        $this->user_id = $user_id;
        return $this;
    }

    /* ----------- Relationships --------------- */

    public function expenseType(){
        return $this->belongsTo(ExpenseType::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    // Do nothing
    public function setUpdatedAtAttribute($value){}

}