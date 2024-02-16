# LogForge

LogForge is a PHP library that provides a flexible and easy-to-use logging mechanism with features like log rotation, log level filtering, and error handling.

## Installation

You can install LogForge via [Composer](https://getcomposer.org/):

```bash
composer require geldiyeff/logforge
```

# Usage
```php
<?php
// Load the Composer autoloader to automatically load required PHP classes
require "vendor/autoload.php";

// Import the LogForge class
use Geldiyeff\LogForge\LogForge;

// Specify the directory where log files will be stored
$logDirectory = "logs";

// Create an instance of LogForge
$logForge = new LogForge($logDirectory);

// Log messages at different levels
$logForge->log(LogForge::LOG_LEVEL_DEBUG, "This is a debug log message.");
$logForge->log(LogForge::LOG_LEVEL_INFO, "This is an info log message.");
$logForge->log(LogForge::LOG_LEVEL_WARNING, "This is a warning log message.");
$logForge->log(LogForge::LOG_LEVEL_ERROR, "This is an error log message.");

// Example of filtering log files
$filteredLogs = $logForge->getLogs(LogForge::LOG_LEVEL_DEBUG, '2024-02-16');
print_r($filteredLogs);

```

# Features

* Log Levels: Log messages can be categorized into different levels (debug, info, warning, error).
* Log Rotation: Log files are rotated automatically based on file size.
* Error Handling: Handles errors during log writing and logs them separately.
* Log Filtering: Filter logs based on log levels and start date.
* Namespace: The library is encapsulated within the geldiyeff\Forge namespace for better organization.

# License

LogForge is open-source software licensed under the [MIT license](LICENSE).
