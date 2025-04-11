<?php

namespace App\Interfaces;

use App\Models\StorageResult;


interface ResponseFactoryInterface
{
    /**
     * Create a success response
     *
     * @param StorageResult $result The storage result
     * @return mixed The success response
     */
    public function createSuccessResponse(StorageResult $result);

    /**
     * Create an error response
     *
     * @param StorageResult $result The storage result
     * @return mixed The error response
     */
    public function createErrorResponse(StorageResult $result);
}