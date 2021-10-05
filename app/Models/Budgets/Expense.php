<?php

namespace App\Models\Budgets;

use App\Models\Presenters\ExpensePresenter;
use App\Models\Users\User;
use App\Models\Utils\Presenters\PresentableTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Expense.
 *
 * @property int id
 * @property int $amount
 * @property string $created_at
 * @property string $description
 * @property int $expense_type_id
 * @property int $user_id
 */
class Expense extends Model
{
    use PresentableTrait;

    protected $presenter = ExpensePresenter::class;

    protected $table = 'expenses';
    protected $fillable = [
        'amount',
        'created_at',
        'description',
        'expense_type_id',
        'user_id',
    ];

    public function id()
    {
        return $this->id;
    }

    public function amount()
    {
        return $this->amount;
    }

    public function createdAt()
    {
        return $this->created_at;
    }

    public function description()
    {
        return $this->description;
    }

    public function expenseTypeId()
    {
        return $this->expense_type_id;
    }

    public function userId()
    {
        return $this->user_id;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    public function setExpenseTypeId($expense_type_id)
    {
        $this->expense_type_id = $expense_type_id;
        return $this;
    }
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /* ----------- Relationships --------------- */

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Do nothing
    public function setUpdatedAtAttribute($value)
    {
    }
}
