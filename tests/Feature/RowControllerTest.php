<?php

namespace Tests\Feature;

use App\Jobs\ProcessRows;
use App\Models\Row;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RowControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testUploadEndpoint(): void
    {
        Queue::fake([
            ProcessRows::class,
        ]);
        Storage::fake();

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('rows.xlsx');

        $response = $this->actingAs($user)
            ->post('/api/row/upload', [
                'file' => $file
            ]);

        Storage::assertExists('rows/' . $file->hashName());
        Queue::assertPushedOn('process_rows', ProcessRows::class);

        $response->assertRedirect();
    }

    public function testShowEndpoint(): void
    {
        $user = User::factory()->create();
        $rows = Row::factory()->count(10)->create()
            ->makeHidden('date')
            ->groupBy('date')
            ->toArray();

        $response = $this->actingAs($user)
            ->get('/api/row');

        $response->assertJson($rows);
        $response->assertSuccessful();
    }
}
