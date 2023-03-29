<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function upload(UploadRequest $request)
    {
        $file = $request->file('file');

        $dbFile = new File();
        $dbFile->uuid = Str::uuid();
        $dbFile->name = $file->hashName();
        $dbFile->path = $file->store('', 'pet-shop');
        $dbFile->type = $file->getMimeType();
        $dbFile->size = $this->humanReadableSize($file->getSize());
        $dbFile->save();

        return response()->success(200, $dbFile->toArray());
    }

    public function download($uuid)
    {
        $file = File::where('uuid', $uuid)->first();

        if (!$file) {
            return response()->error(404, 'File not found');
        }

        return Storage::disk('pet-shop')->download($file->path, $file->name);
    }

    private function humanReadableSize($size)
    {
        $units = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $size >= 1024 && $i < 5; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . ' ' . $units[$i];
    }
}
