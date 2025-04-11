<?php

namespace App\Interfaces;

/**
 * Interface for storage repositories
 */
interface FileNamingStrategyInterface
{
    /**
     * Get the next filename in the sequence
     *
     * @param array $existingFiles Array of existing filenames
     * @return string The next filename
     */
    public function getNextFilename(array $existingFiles): string;
}