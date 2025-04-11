<?php
namespace App\Models;

class ApiResponse
{
    private $status;
    private $statusCode;
    private $data;

    /**
     * Constructor for ApiResponse
     *
     * @param string $status Status string (success/error)
     * @param int $statusCode HTTP status code
     * @param array $data Response data
     */
    public function __construct(string $status, int $statusCode, array $data)
    {
        $this->status = $status;
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    /**
     * Send the response
     */
    public function send(): void
    {
        header('Content-Type: application/json');
        http_response_code($this->statusCode);

        $responseData = array_merge(
            ['status' => $this->status],
            $this->data
        );

        echo json_encode($responseData);
        exit;
    }
}