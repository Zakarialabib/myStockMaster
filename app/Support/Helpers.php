<?php

use Illuminate\Support\Facades\Request;
use Carbon\Carbon;


function flagImageUrl($language_code)
{
    return asset("images/flags/{$language_code}.png");
}

function getSlug($request, $key)
{
    $language_default = \App\Models\Language::query()
        ->where('is_default', \App\Models\Language::IS_DEFAULT)
        ->select('code')
        ->first();
    $language_code = $language_default->code;
    $value = $request[$language_code][$key];
    $slug = \Illuminate\Support\Str::slug($value);
    return $slug;
}


