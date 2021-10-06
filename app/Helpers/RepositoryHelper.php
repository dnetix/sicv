<?php

namespace App\Helpers;

use App\Repositories\ContractRepository;

class RepositoryHelper
{
    private static ?self $instance = null;
    protected array $repositories = [];

    private function __construct()
    {
    }

    public function checkAndGet(string $repository)
    {
        if (!($this->repositories[$repository] ?? false)) {
            $this->repositories[$repository] = app($repository);
        }
        return $this->repositories[$repository];
    }

    public static function instance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function forContracts(): ContractRepository
    {
        return self::instance()->checkAndGet(ContractRepository::class);
    }
}
