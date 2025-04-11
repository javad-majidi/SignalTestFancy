<?php
namespace App\Strategies;

use App\Interfaces\FileNamingStrategyInterface;

/**
 * CountdownNamingStrategy implements a countdown file naming strategy
 */
class CountdownNamingStrategy implements FileNamingStrategyInterface
{
    private $maxNumber;
    private $minNumber;
    private $extension;

    /**
     * Constructor for CountdownNamingStrategy
     *
     * @param int $maxNumber The maximum file number (starting point)
     * @param int $minNumber The minimum file number (endpoint)
     * @param string $extension The file extension to use
     */
    public function __construct(int $maxNumber, int $minNumber, string $extension)
    {
        $this->maxNumber = $maxNumber;
        $this->minNumber = $minNumber;
        $this->extension = $extension;
    }

    /**
     * Get the next filename in the sequence
     *
     * @param array $existingFiles Array of existing filenames
     * @return string The next filename
     */
    public function getNextFilename(array $existingFiles): string
    {
        // If no files exist or minimum value file exists, start from max
        if (empty($existingFiles) || $this->hasMinimumValueFile($existingFiles)) {
            return $this->maxNumber . $this->extension;
        }

        // Find the highest numbered file
        $highestNumber = $this->findLowestNumber($existingFiles);

        // Return one number less as the new filename
        return ($highestNumber - 1) . $this->extension;
    }

    /**
     * Check if the minimum value file exists
     *
     * @param array $existingFiles Array of existing filenames
     * @return bool True if the minimum value file exists
     */
    private function hasMinimumValueFile(array $existingFiles): bool
    {
        $minFile = $this->minNumber . $this->extension;

        foreach ($existingFiles as $file) {
            if (basename($file) === $minFile) {
                return true;
            }
        }

        return false;
    }

    /**
     * Find the highest number in existing files
     *
     * @param array $existingFiles Array of existing filenames
     * @return int The highest number found
     */
    private function findLowestNumber(array $existingFiles): int
    {
        $lowestNumber = (int)pathinfo(basename($existingFiles[0]), PATHINFO_FILENAME);

        foreach ($existingFiles as $file) {
            $filename = basename($file);
            $number = (int)pathinfo($filename, PATHINFO_FILENAME);

            if ($number < $lowestNumber) {
                $lowestNumber = $number;
            }
        }

        return $lowestNumber;
    }
}