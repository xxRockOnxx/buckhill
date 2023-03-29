<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function upload(UploadRequest $request): JsonResponse
    {
        /** @var UploadedFile $file */
        $file = $request->file('file');
        $storedFile = $file->store('', 'pet-shop');

        if (! $storedFile) {
            return Response::error(500, 'File could not be stored');
        }

        $dbFile = new File();
        $dbFile->uuid = Str::uuid();
        $dbFile->path = $storedFile;
        $dbFile->name = $file->hashName();

        // clientMimeType() is not really safe but it should do the job for now.
        // Ideally we should fail but for this demo it should be fine.
        $dbFile->type = $file->getMimeType() ?? $file->getClientMimeType();

        $dbFile->size = $this->humanReadableSize($file->getSize());
        $dbFile->save();

        return Response::success(200, $dbFile->toArray());
    }

    public function download(string $uuid): StreamedResponse
    {
        $file = File::where('uuid', $uuid)->first();

        if (! $file) {
            return Response::error(404, 'File not found');
        }

        return Storage::disk('pet-shop')->download($file->path, $file->name);
    }

    private function humanReadableSize(int $size): string
    {
        $units = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $size >= 1024 && $i < 5; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . ' ' . $units[$i];
    }
}
