<?php
/**
 * File Rotation API
 *
 * Entry point for the application
 * This file should be placed in the root directory for the Docker setup
 */

// Autoloader
spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Import classes
use App\Controllers\RequestController;
use App\Services\FileStorageService;
use App\Repositories\FileSystemRepository;
use App\Strategies\CountdownNamingStrategy;
use App\Factories\ResponseFactory;

// Configuration
$config = [
    'storage_dir' => __DIR__ . '/data/',
    'max_file_number' => 100,
    'min_file_number' => 1,
    'file_extension' => '.txt'
];

// Set up dependencies
$namingStrategy = new CountdownNamingStrategy(
    $config['max_file_number'],
    $config['min_file_number'],
    $config['file_extension']
);

$repository = new FileSystemRepository(
    $config['storage_dir'],
    $namingStrategy
);

$fileStorageService = new FileStorageService($repository);
$responseFactory = new ResponseFactory();

// Process the request
$controller = new RequestController($fileStorageService, $responseFactory);
$controller->handleRequest();