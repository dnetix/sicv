<?php

namespace App\Queries;

/**
 * @property-read array{extensions: int, sales: int, redemptions: int} $in
 * @property-read array{loans: int, expenses: int, purchases: int} $out
 */
readonly class CashFlowSummary
{
    public function __construct(
        public array $in,
        public array $out,
        public int $redeemedCapital,
    ) {}

    public function totalIn(): int
    {
        return array_sum($this->in);
    }

    public function totalOut(): int
    {
        return array_sum($this->out);
    }

    public function balance(): int
    {
        return $this->totalIn() - $this->totalOut();
    }
}
