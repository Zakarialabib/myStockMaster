<?php

declare(strict_types=1);

arch('controllers')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller');

arch('models')
    ->expect('App\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model');

arch('no debugging')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();

arch('enums')
    ->expect('App\Enums')
    ->toBeEnums();
