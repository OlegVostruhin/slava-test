<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use App\Service\RowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class RowController extends Controller
{
    public function __construct(private readonly RowService $rowService)
    {}

    public function upload(UploadRequest $request): RedirectResponse
    {
        $file = $request->file('file');
        $this->rowService->processUploaded($file);

        return redirect()->back();
    }

    public function show(): Response
    {
        return response($this->rowService->show());
    }
}
