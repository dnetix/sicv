<?php

namespace App\Models\Budgets;

use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    protected $table = 'expense_types';
    protected $fillable = ['name'];

    public $timestamps = false;

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /* ----------- Relationships --------------- */

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
