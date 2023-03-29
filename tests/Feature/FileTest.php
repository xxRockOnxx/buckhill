<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class FileTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_upload_file()
    {
        // Arrange
        $user = User::factory()->create();

        $this->actingAs($user);

        $file = UploadedFile::fake()->image('avatar.jpg');

        // Act
        $response = $this->post('/api/v1/file/upload', [
            'file' => $file,
        ]);

        // Assert
        $this->assertSuccessResponseMacro($response, [
            'uuid' => true,
            'name' => true,
            'path' => true,
            'size' => true,
            'type' => true,
            'created_at' => true,
            'updated_at' => true,
        ]);
    }

    public function test_cannot_upload_file_as_guest()
    {
        // Arrange
        $file = UploadedFile::fake()->image('avatar.jpg');

        // Act
        $response = $this->post('/api/v1/file/upload', [
            'file' => $file,
        ]);

        // Assert
        $this->assertErrorResponseMacro($response, 401, 'Unauthorized');
    }

    public function test_can_download_file()
    {
        // Arrange
        $file = UploadedFile::fake()->image('avatar.jpg');
        $storedFile = $file->store('', 'pet-shop');

        $model = new File();
        $model->uuid = Str::uuid();
        $model->name = $file->hashName();
        $model->path = $storedFile;
        $model->type = $file->getMimeType();
        $model->size = $file->getSize();
        $model->save();

        // Act
        $response = $this->get('/api/v1/file/' . $model->uuid);

        // Assert
        $response->assertDownload($model->name);
    }
}
