<?php

namespace App\Tests;

require_once dirname(__DIR__, 2) . '/index.php';

use App\Models\RequestData;
use App\Repositories\FileSystemRepository;
use App\Strategies\CountdownNamingStrategy;
use App\Services\FileStorageService;

/**
 * Simple test class that doesn't require PHPUnit
 */
class FileRotationTest
{
    private $tempDir;
    private $namingStrategy;
    private $repository;
    private $service;

    /**
     * Set up the test environment
     */
    public function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/file_rotation_test_' . uniqid() . '/';
        mkdir($this->tempDir, 0755, true);

        $this->namingStrategy = new CountdownNamingStrategy(100, 1, '.txt');
        $this->repository = new FileSystemRepository($this->tempDir, $this->namingStrategy);
        $this->service = new FileStorageService($this->repository);
    }

    /**
     * Clean up the test environment
     */
    public function tearDown(): void
    {
        $this->deleteDirectory($this->tempDir);
    }

    /**
     * Test that the first request creates 100.txt
     */
    public function testFirstRequestCreates100(): void
    {
        $requestData = new RequestData('GET', [], ['test' => 'first request']);
        $result = $this->service->storeRequestData($requestData);

        if ($result->isSuccess() && $result->getFilename() === '100.txt' && file_exists($this->tempDir . '100.txt')) {
            echo "✓ PASS: First request created 100.txt\n";
        } else {
            echo "✗ FAIL: First request did not create 100.txt\n";
        }
    }

    /**
     * Test that the second request creates 99.txt
     */
    public function testSecondRequestCreates99(): void
    {
        // First request
        $requestData1 = new RequestData('GET', [], ['test' => 'first request']);
        $this->service->storeRequestData($requestData1);

        // Second request
        $requestData2 = new RequestData('GET', [], ['test' => 'second request']);
        $result = $this->service->storeRequestData($requestData2);

        if ($result->isSuccess() && $result->getFilename() === '99.txt' && file_exists($this->tempDir . '99.txt')) {
            echo "✓ PASS: Second request created 99.txt\n";
        } else {
            echo "✗ FAIL: Second request did not create 99.txt\n";
        }
    }

    /**
     * Test rotation back to 100.txt after reaching 1.txt
     */
    public function testRotationBackTo100(): void
    {
        // Create file 1.txt manually
        file_put_contents($this->tempDir . '1.txt', '{"test":"data"}');

        // Next request should create 100.txt
        $requestData = new RequestData('GET', [], ['test' => 'rotation test']);
        $result = $this->service->storeRequestData($requestData);

        if ($result->isSuccess() && $result->getFilename() === '100.txt' && file_exists($this->tempDir . '100.txt')) {
            echo "✓ PASS: Successfully rotated back to 100.txt after 1.txt\n";
        } else {
            echo "✗ FAIL: Did not rotate back to 100.txt after 1.txt\n";
        }
    }

    /**
     * Test that content is stored correctly as JSON
     */
    public function testContentIsStoredCorrectly(): void
    {
        $testData = ['key' => 'value', 'nested' => ['data' => true]];
        $requestData = new RequestData('POST', ['Content-Type' => 'application/json'], $testData);

        $result = $this->service->storeRequestData($requestData);

        $storedContent = file_get_contents($this->tempDir . $result->getFilename());
        $storedData = json_decode($storedContent, true);

        if (is_array($storedData) &&
            isset($storedData['content']) &&
            isset($storedData['content']['key']) &&
            $storedData['content']['key'] === 'value') {
            echo "✓ PASS: Content stored correctly as JSON\n";
        } else {
            echo "✗ FAIL: Content not stored correctly\n";
        }
    }

    /**
     * Delete a directory and its contents recursively
     *
     * @param string $dir Directory to delete
     * @return bool True on success
     */
    private function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object === '.' || $object === '..') {
                continue;
            }

            $path = $dir . '/' . $object;

            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }

        return rmdir($dir);
    }

    /**
     * Run all tests
     */
    public function runAllTests(): void
    {
        echo "Running file rotation tests...\n";
        echo "-------------------------\n";

        $this->setUp();
        $this->testFirstRequestCreates100();
        $this->testSecondRequestCreates99();
        $this->testRotationBackTo100();
        $this->testContentIsStoredCorrectly();
        $this->tearDown();

        echo "-------------------------\n";
        echo "Tests completed.\n";
    }
}

// Run tests when executed directly
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    $test = new FileRotationTest();
    $test->runAllTests();
}