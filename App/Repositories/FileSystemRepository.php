<?php
namespace App\Repositories;

use App\Interfaces\StorageRepositoryInterface;
use App\Interfaces\FileNamingStrategyInterface;
use App\Models\RequestData;
use App\Models\StorageResult;

/**
 * FileSystemRepository implements file system storage
 */
class FileSystemRepository implements StorageRepositoryInterface
{
    private $baseDir;
    private $namingStrategy;

    /**
     * Constructor for FileSystemRepository
     *
     * @param string $baseDir The base directory for storage
     * @param FileNamingStrategyInterface $namingStrategy The naming strategy to use
     */
    public function __construct(string $baseDir, FileNamingStrategyInterface $namingStrategy)
    {
        $this->baseDir = rtrim($baseDir, '/') . '/';
        $this->namingStrategy = $namingStrategy;

        // Ensure the directory exists
        $this->ensureDirectoryExists();
    }

    /**
     * Set the naming strategy
     *
     * @param FileNamingStrategyInterface $strategy The naming strategy to use
     * @return void
     */
    public function setNamingStrategy(FileNamingStrategyInterface $strategy): void
    {
        $this->namingStrategy = $strategy;
    }

    /**
     * Store data in a file
     *
     * @param RequestData $data The data to store
     * @return StorageResult The result of the storage operation
     */
    public function store(RequestData $data): StorageResult
    {
        try {
            // Get existing files
            $existingFiles = $this->getExistingFiles();

            // Get next filename using the naming strategy
            $filename = $this->namingStrategy->getNextFilename($existingFiles);
            $fullPath = $this->baseDir . $filename;

            // Store the data
            $result = file_put_contents($fullPath, $data->toJson(JSON_PRETTY_PRINT));

            if ($result !== false) {
                return new StorageResult(true, $filename, 'Request data saved successfully');
            } else {
                return new StorageResult(false, null, 'Failed to write to file');
            }
        } catch (\Exception $e) {
            return new StorageResult(false, null, 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Get existing files in the storage directory
     *
     * @return array Array of existing files
     */
    private function getExistingFiles(): array
    {
        $files = glob($this->baseDir . '*');
        return is_array($files) ? $files : [];
    }

    /**
     * Ensure the storage directory exists
     *
     * @return void
     */
    private function ensureDirectoryExists(): void
    {
        if (!is_dir($this->baseDir)) {
            if (!mkdir($this->baseDir, 0755, true) && !is_dir($this->baseDir)) {
                throw new \RuntimeException("Failed to create directory: {$this->baseDir}");
            }
        }
    }
}