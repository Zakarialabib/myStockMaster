<?php

use App\Livewire\MediaUpload;
use Livewire\Livewire;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('renders the media upload component successfully', function () {
    Livewire::test(MediaUpload::class)
        ->assertStatus(200)
        ->assertSee('Upload File');
});

it('can upload a single file', function () {
    Storage::fake('local_files');

    $file = UploadedFile::fake()->image('avatar.png');

    Livewire::test(MediaUpload::class, ['single' => true])
        ->set('file', $file)
        ->assertSet('file', $file)
        ->assertHasNoErrors('file');
});

it('can remove a single file', function () {
    Storage::fake('local_files');

    $file = UploadedFile::fake()->image('avatar.png');

    Livewire::test(MediaUpload::class, ['single' => true])
        ->set('file', $file)
        ->call('removeSingle')
        ->assertSet('file', null);
});

it('can upload multiple files', function () {
    Storage::fake('local_files');

    $file1 = UploadedFile::fake()->image('photo1.png');
    $file2 = UploadedFile::fake()->image('photo2.png');

    Livewire::test(MediaUpload::class, ['single' => false])
        ->set('file', [$file1, $file2])
        ->assertSet('file', [$file1, $file2])
        ->assertHasNoErrors('file');
});

it('can remove a specific file from multiple uploads', function () {
    Storage::fake('local_files');

    $file1 = UploadedFile::fake()->image('photo1.png');
    $file2 = UploadedFile::fake()->image('photo2.png');

    Livewire::test(MediaUpload::class, ['single' => false])
        ->set('file', [$file1, $file2])
        ->call('removeMultiple', 0) // Remove first file
        ->assertSet('file', [$file2]); // Only second file should remain
});

it('validates file uploads correctly if rules are applied in parent component', function () {
    // MediaUpload itself delegates validation to the parent via #[Modelable]
    // but we ensure it can accept the state.
    $file = UploadedFile::fake()->create('document.pdf', 1024);

    Livewire::test(MediaUpload::class)
        ->set('file', $file)
        ->assertSet('file', $file);
});
