<?php

namespace App\Models\Contracts;

use App\Helpers\Dates\DateHelper;
use App\Models\Articles\Article;
use App\Models\Clients\Client;
use App\Models\Sales\Product;
use App\Models\Sellouts\Sellout;
use App\Models\Users\User;
use App\Models\Utils\Presenters\PresentableTrait;
use App\Presenters\ContractPresenter;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use PresentableTrait;

    protected $presenter = ContractPresenter::class;

    protected $table = 'contracts';

    protected $fillable = [
        'user_id',
        'client_id',
        'months',
        'created_at',
        'percentage',
        'amount',
        'state',
        'end_date',
        'end_amount',
    ];

    public function id()
    {
        return $this->id;
    }

    public function amount()
    {
        return $this->amount;
    }

    public function userId()
    {
        return $this->user_id;
    }

    public function clientId()
    {
        return $this->client_id;
    }

    public function months()
    {
        return $this->months;
    }

    public function percentage()
    {
        return $this->percentage;
    }

    public function createdAt()
    {
        return $this->created_at;
    }

    public function state()
    {
        return $this->state;
    }

    public function endAmount()
    {
        return $this->end_amount;
    }

    public function endDate()
    {
        return $this->end_date;
    }

    public function extension(): float
    {
        return ceil($this->amount() * ($this->percentage / 100));
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setEndDate($endDate): self
    {
        $this->end_date = $endDate;
        return $this;
    }

    /**
     * Difference between the created_at and end_date (If this last one still not happen, so NOW).
     * @return \App\Helpers\Dates\DateDifference
     */
    public function elapsedDifference()
    {
        if ($this->isActive()) {
            return DateHelper::getDifference($this->createdAt());
        } else {
            return DateHelper::getDifference($this->createdAt(), $this->endDate());
        }
    }

    /**
     * Returns the number of months elapsed, because it's upfront always it's plus one.
     * @return int
     */
    public function elapsedMonths()
    {
        return $this->elapsedDifference()->inMonths() + 1;
    }

    public function extendedMonths()
    {
        return floor($this->payedExtensions() / $this->extension());
    }

    /**
     * Return the number of months of the contract and contains the extended months too.
     * @return int|mixed
     */
    public function contractMonths()
    {
        return $this->elapsedMonths() - $this->extendedMonths();
    }

    /**
     * Total of extensions payed to the contract.
     * @return int
     */
    public function payedExtensions()
    {
        return $this->extensions->sum('amount');
    }

    /**
     * Returns the amount that the client ows in extensions.
     * @return int
     */
    public function duedExtensions()
    {
        if ($this->isActive()) {
            return ($this->elapsedMonths() * $this->extension()) - $this->payedExtensions();
        }
        return 0;
    }

    /**
     * Basically the duedExtensions + the amount of the contract.
     * @return int
     */
    public function amountToTerminate()
    {
        if ($this->isActive()) {
            return $this->amount() + $this->duedExtensions();
        }
        return 0;
    }

    public function profit()
    {
        return ($this->payedExtensions() + $this->endAmount()) - $this->amount();
    }

    public function profitPercent()
    {
        return ($this->profit() / $this->amount()) * 100;
    }

    public function articlesCount()
    {
        return $this->articles->count();
    }

    public function isActive(): bool
    {
        return $this->state() == ContractStates::ACTIVE;
    }

    public function toActive(): self
    {
        $this->state = ContractStates::ACTIVE;
        return $this;
    }

    public function toEnded()
    {
        $this->state = ContractStates::ENDED;
        return $this;
    }

    public function isTerminated()
    {
        return $this->state() == ContractStates::TERMINATED;
    }

    public function isEnded()
    {
        return $this->state() == ContractStates::ENDED;
    }

    public function isAnnulled()
    {
        return $this->state() == ContractStates::ANNULLED;
    }

    public function isPreSellout()
    {
        if (is_null($this->preSellout)) {
            return false;
        } else {
            return true;
        }
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class)
            ->withPivot(['id', 'article_amount']);
    }

    public function extensions()
    {
        return $this->hasMany(Extension::class);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Returns the relationship with the annul.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function annul()
    {
        return $this->hasOne(Annul::class);
    }

    public function preSellout()
    {
        return $this->hasOne(PreSellout::class);
    }

    public function sellout()
    {
        return $this->belongsToMany(Sellout::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
