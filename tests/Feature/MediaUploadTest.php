<?php

use App\Livewire\Brands\Create;
use App\Livewire\Brands\Edit;
use App\Models\Brand;
use Livewire\Livewire;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('can upload a single file in brands create component', function () {
    Storage::fake('local_files');

    $file = UploadedFile::fake()->image('brand_logo.png');

    Livewire::test(Create::class)
        ->set('form.name', 'Test Brand')
        ->set('form.image', $file)
        ->call('store')
        ->assertHasNoErrors();

    // Verify the file was stored physically
    $brand = Brand::where('name', 'Test Brand')->first();
    expect($brand)->not->toBeNull();
    expect($brand->image)->not->toBeNull();
    
    Storage::disk('local_files')->assertExists('brands/' . $brand->image);
});

it('can remove an uploaded file before saving', function () {
    Storage::fake('local_files');

    $file = UploadedFile::fake()->image('brand_logo.png');

    Livewire::test(Create::class)
        ->set('form.name', 'Test Brand')
        ->set('form.image', $file)
        ->set('form.image', null) // Removing it
        ->call('store')
        ->assertHasNoErrors();

    $brand = Brand::where('name', 'Test Brand')->first();
    expect($brand->image)->toBeNull();
});
