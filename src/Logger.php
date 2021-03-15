<?php
declare(strict_types=1);

namespace Library;

interface Logger
{
    /**
     * @param string $info
     *
     * @return bool
     *
     * @throws LoggerException
     */
    public function info(string $info): bool;
}