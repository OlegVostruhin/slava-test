<?php

namespace App\Repository;

use App\Models\FileProcessing;

class FileProcessingRepository
{
    public function create(string $name, string $path): FileProcessing
    {
        return FileProcessing::create([
            'name' => $name,
            'file_path' => $path,
        ]);
    }

    public function confirmSuccess(string $name): bool
    {
        return FileProcessing::where(['name' => $name])->update([
            'state' => 1,
            'message' => 'Файл успешно обработан'
        ]);
    }
}
