<?php
namespace App\Services;

use App\Interfaces\StorageServiceInterface;
use App\Interfaces\StorageRepositoryInterface;
use App\Models\RequestData;
use App\Models\StorageResult;

/**
 * FileStorageService implements storage service functionality
 */
class FileStorageService implements StorageServiceInterface
{
    private $repository;

    /**
     * Constructor for FileStorageService
     *
     * @param StorageRepositoryInterface $repository The storage repository to use
     */
    public function __construct(StorageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store request data
     *
     * @param RequestData $data The request data to store
     * @return StorageResult The result of the storage operation
     */
    public function storeRequestData(RequestData $data): StorageResult
    {
        // Additional service-level logic could be added here
        // For example, validation, transformation, logging, etc.

        return $this->repository->store($data);
    }
}