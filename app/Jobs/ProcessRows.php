<?php

namespace App\Jobs;

use App\Dto\ParsingStatus;
use App\Models\FileProcessing;
use App\Repository\FileProcessingRepository;
use App\Repository\RowRepository;
use App\Service\CacheService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rap2hpoutre\FastExcel\FastExcel;

class ProcessRows implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const CHUNK_SIZE = 1000;

    private RowRepository $rowRepository;
    private CacheService $cacheService;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly string $name)
    {
        $this->queue = 'process_rows';
    }

    /**
     * Execute the job.
     */
    public function handle(RowRepository $rowRepository, CacheService $cacheService, FileProcessingRepository $fileProcessingRepository): void
    {
        $rows = (new FastExcel)->import($this->getFilePath());
        $processedRows = 0;

        foreach ($rows->chunk(self::CHUNK_SIZE) as $chunk) {
            $rowRepository->bulkUpsert($chunk);

            $processedRows += count($chunk);
            $cacheService->setParsingStatus(new ParsingStatus($this->name, $processedRows));
        }

        $fileProcessingRepository->confirmSuccess($this->name);
    }

    private function getFilePath(): string
    {
        return storage_path() . '/app/rows/' . $this->name;
    }
}
