<?php

namespace App\Service;

use App\Dto\ParsingStatus;
use Illuminate\Support\Facades\Redis;

class CacheService
{
    private const PARSING_STATUS_PREFIX = 'parsing.';

    public function setParsingStatus(ParsingStatus $parsingStatus)
    {
        return Redis::set(self::PARSING_STATUS_PREFIX . $parsingStatus->fileName, json_encode($parsingStatus, JSON_THROW_ON_ERROR));
    }
}
