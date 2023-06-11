<?php

namespace App\Service;

use App\Http\Resources\RowCollection;
use App\Jobs\ProcessRows;
use App\Repository\FileProcessingRepository;
use App\Repository\RowRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class RowService
{
    public function __construct(
        private readonly FileProcessingRepository $fileProcessingRepository,
        private readonly RowRepository $rowRepository
    )
    {}

    public function processUploaded(UploadedFile $file): void
    {
        $pathToFile = Storage::put('/rows', $file);
        $this->fileProcessingRepository->create($file->hashName(), $pathToFile);

        ProcessRows::dispatch($file->hashName());
    }

    public function show(): Collection
    {
        return $this->rowRepository->getGroupedByDate();
    }
}
