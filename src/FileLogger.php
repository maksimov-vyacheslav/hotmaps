<?php
declare(strict_types=1);

namespace Library;


class FileLogger implements Logger
{
    private string $logFilePath;

    /**
     * FileLogger constructor.
     *
     * @param string $logFilePath
     */
    private function __construct(string $logFilePath)
    {
        $this->logFilePath = $logFilePath;
    }

    /**
     * @param string $info
     *
     * @return bool
     *
     * @throws LoggerException
     */
    public function info(string $info): bool
    {
        if (!file_exists($this->logFilePath)) {
            throw new LoggerException('unable to write to file');
        }

        file_put_contents($this->logFilePath, $info, FILE_APPEND);

        return true;
    }
}