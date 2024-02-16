<?php

/**
 * LogForge - A simple PHP logging library.
 * @author Dovletmammet Geldiyev
 * @version 1.0.0
 * @license MIT
 */


namespace Geldiyeff\LogForge;

use InvalidArgumentException;

class LogForge
{
    const LOG_LEVEL_DEBUG = 'debug';
    const LOG_LEVEL_INFO = 'info';
    const LOG_LEVEL_WARNING = 'warning';
    const LOG_LEVEL_ERROR = 'error';

    private $logDirectory;
    private $logLevels = [
        self::LOG_LEVEL_DEBUG,
        self::LOG_LEVEL_INFO,
        self::LOG_LEVEL_WARNING,
        self::LOG_LEVEL_ERROR
    ];
    private $maxFileSize = 1024000; // 1 MB
    private $dateFormat = "Y-m-d H:i:s";
    private $errorLogFilePath;

    /**
     * Constructor method for the LogCraftX class.
     *
     * @param string $logDirectory Directory where log files will be stored.
     */
    public function __construct($logDirectory)
    {
        $this->logDirectory = $logDirectory;

        // Check if the directory exists, create it if not.
        if (!file_exists($this->logDirectory)) {
            mkdir($this->logDirectory, 0777, true);
        }

        $this->errorLogFilePath = $this->logDirectory . '/error_log.txt';
    }

    /**
     * Logs a message at the specified level.
     *
     * @param string $level    Log level (debug, info, warning, error).
     * @param string $message  Log message to be saved.
     * @throws InvalidArgumentException Throws an exception for an invalid log level.
     */
    public function log($level, $message)
    {
        if (!in_array($level, $this->logLevels)) {
            throw new InvalidArgumentException("Invalid log level: $level");
        }

        $logFilePath = $this->logDirectory . '/' . $level . '_log.txt';
        $formattedMessage = $this->formatLogMessage($level, $message);

        // Check log file size and rotate if necessary.
        if (file_exists($logFilePath) && filesize($logFilePath) > $this->maxFileSize) {
            $this->rotateLogFile($logFilePath);
        }

        // Write the log message to the file.
        if (!file_put_contents($logFilePath, $formattedMessage, FILE_APPEND)) {
            // In case of an error, log to the error_log file.
            $errorLogMessage = "Error writing to log file: $logFilePath";
            $errorLogMessage .= "\nOriginal log message: $formattedMessage";
            file_put_contents($this->errorLogFilePath, $errorLogMessage, FILE_APPEND);
        }
    }

    /**
     * Formats a log message with the specified level and message.
     *
     * @param string $level    Log level (debug, info, warning, error).
     * @param string $message  Log message to be formatted.
     * @return string          Formatted log message.
     */
    private function formatLogMessage($level, $message)
    {
        return "[" . date($this->dateFormat) . "] [$level] $message" . PHP_EOL;
    }

    /**
     * Rotates the log file by renaming it with a timestamp.
     *
     * @param string $logFilePath Path of the log file to be rotated.
     */
    private function rotateLogFile($logFilePath)
    {
        $backupFilePath = $logFilePath . '.' . date('YmdHis');
        rename($logFilePath, $backupFilePath);
    }

    /**
     * Filters logs for a specific log level and logs before a certain date.
     *
     * @param string|null $level      Log level for filtering (debug, info, warning, error).
     * @param string|null $startDate  Start date for filtering (Y-m-d).
     * @return array                  Filtered log messages.
     */
    public function getLogs($level = null, $startDate = null)
    {
        $filteredLogs = [];

        foreach ($this->logLevels as $logLevel) {
            $logFilePath = $this->logDirectory . '/' . $logLevel . '_log.txt';

            if (file_exists($logFilePath)) {
                $logs = file($logFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

                foreach ($logs as $log) {
                    $logData = explode(']', $log);
                    $logDate = trim(str_replace('[', '', $logData[0]));

                    if (($level === null || $logLevel === $level) && ($startDate === null || strtotime($logDate) >= strtotime($startDate))) {
                        $filteredLogs[] = $log;
                    }
                }
            }
        }

        return $filteredLogs;
    }
}
