<?php

namespace App\Interfaces;

use App\Models\RequestData;
use App\Models\StorageResult;


interface StorageRepositoryInterface
{
    /**
     * Store data in a file
     *
     * @param RequestData $data The data to store
     * @return StorageResult The result of the storage operation
     */
    public function store(RequestData $data): StorageResult;

    /**
     * Set the naming strategy
     *
     * @param FileNamingStrategyInterface $strategy The naming strategy to use
     * @return void
     */
    public function setNamingStrategy(FileNamingStrategyInterface $strategy): void;
}