<?php

namespace App\Repository;

use App\Http\Resources\RowResource;
use App\Models\Row;
use Illuminate\Support\Collection;

class RowRepository
{
    public function bulkUpsert(Collection $collection): int
    {
        return Row::upsert([
            ...$collection
        ], ['id'], ['name', 'date']);
    }

    public function getGroupedByDate(): Collection
    {
        return RowResource::collection(Row::all())->groupBy('date');
    }
}
