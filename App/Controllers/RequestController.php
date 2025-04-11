<?php
namespace App\Controllers;

use App\Interfaces\StorageServiceInterface;
use App\Interfaces\ResponseFactoryInterface;
use App\Models\RequestData;

/**
 * RequestController handles incoming requests
 */
class RequestController
{
    private $storageService;
    private $responseFactory;

    /**
     * Constructor for RequestController
     *
     * @param StorageServiceInterface $storageService The storage service to use
     * @param ResponseFactoryInterface $responseFactory The response factory to use
     */
    public function __construct(
        StorageServiceInterface $storageService,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->storageService = $storageService;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Handle the current request
     *
     * @return void
     */
    public function handleRequest(): void
    {
        // Parse request data
        $requestData = RequestData::fromCurrentRequest();

        // Store request data
        $result = $this->storageService->storeRequestData($requestData);

        // Create and send response
        if ($result->isSuccess()) {
            $response = $this->responseFactory->createSuccessResponse($result);
        } else {
            $response = $this->responseFactory->createErrorResponse($result);
        }

        $response->send();
    }
}