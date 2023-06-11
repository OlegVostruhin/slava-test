<?php

namespace App\Dto;

class ParsingStatus
{
    public function __construct(public readonly string $fileName, public readonly int $processedRowCount){}
}
