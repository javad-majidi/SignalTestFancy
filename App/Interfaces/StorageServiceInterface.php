<?php

namespace App\Interfaces;

use App\Models\RequestData;
use App\Models\StorageResult;


interface StorageServiceInterface
{
    /**
     * Store request data
     *
     * @param RequestData $data The request data to store
     * @return StorageResult The result of the storage operation
     */
    public function storeRequestData(RequestData $data): StorageResult;
}