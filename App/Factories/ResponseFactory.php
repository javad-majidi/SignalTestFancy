<?php
namespace App\Factories;

use App\Interfaces\ResponseFactoryInterface;
use App\Models\StorageResult;
use App\Models\ApiResponse;

/**
 * ResponseFactory implements a factory for API responses
 */
class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * Create a success response
     *
     * @param StorageResult $result The storage result
     * @return ApiResponse The success response
     */
    public function createSuccessResponse(StorageResult $result): ApiResponse
    {
        return new ApiResponse('success', 200, [
            'message' => $result->getMessage(),
            'filename' => $result->getFilename()
        ]);
    }

    /**
     * Create an error response
     *
     * @param StorageResult $result The storage result
     * @return ApiResponse The error response
     */
    public function createErrorResponse(StorageResult $result): ApiResponse
    {
        return new ApiResponse('error', 500, [
            'message' => $result->getMessage()
        ]);
    }
}