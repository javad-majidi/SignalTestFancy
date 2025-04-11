<?php
namespace App\Models;

class RequestData
{
    private $method;
    private $headers;
    private $content;
    private $timestamp;

    /**
     * Constructor for RequestData
     *
     * @param string $method The HTTP method used
     * @param array $headers The HTTP headers from the request
     * @param mixed $content The content of the request
     * @param string|null $timestamp Timestamp of when the request was processed
     */
    public function __construct(string $method, array $headers, $content, ?string $timestamp = null)
    {
        $this->method = $method;
        $this->headers = $headers;
        $this->content = $content;
        $this->timestamp = $timestamp ?? date('Y-m-d H:i:s');
    }

    /**
     * Convert RequestData to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'method' => $this->method,
            'headers' => $this->headers,
            'content' => $this->content,
            'timestamp' => $this->timestamp
        ];
    }

    /**
     * Convert RequestData to JSON
     *
     * @param int $options JSON encoding options
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Create a RequestData object from the current HTTP request
     *
     * @return RequestData
     */
    public static function fromCurrentRequest(): RequestData
    {
        $requestContent = file_get_contents('php://input');
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestHeaders = function_exists('getallheaders') ? getallheaders() : self::getHeadersFromServer();

        // Try to decode JSON content
        $content = json_decode($requestContent, true);

        // If content is not valid JSON, use the raw content
        if (json_last_error() !== JSON_ERROR_NONE) {
            $content = $requestContent;
        }

        return new self($requestMethod, $requestHeaders, $content);
    }

    /**
     * Get headers from $_SERVER array when getallheaders() is not available
     *
     * @return array
     */
    private static function getHeadersFromServer(): array
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) === 'HTTP_') {
                $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }
}