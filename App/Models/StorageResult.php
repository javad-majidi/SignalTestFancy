<?php
namespace App\Models;

class StorageResult
{
    private $success;
    private $filename;
    private $message;

    /**
     * Constructor for StorageResult
     *
     * @param bool $success Whether the operation was successful
     * @param string|null $filename The filename that was used
     * @param string|null $message Additional message about the operation
     */
    public function __construct(bool $success, ?string $filename = null, ?string $message = null)
    {
        $this->success = $success;
        $this->filename = $filename;
        $this->message = $message;
    }

    /**
     * Check if the operation was successful
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Get the filename
     *
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * Get the message
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'filename' => $this->filename,
            'message' => $this->message
        ];
    }
}