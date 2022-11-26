<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const IS_DEFAULT = 1;
    const IS_NOT_DEFAULT = 0;

    protected $fillable = [
        'name',
        'code',
        'status',
        'is_default',
    ];

    public $timestamps = false;

    public function blogs()
    {
        return $this->hasMany('App\Models\Blog', 'language_id');
    }

    public function blog_categories()
    {
        return $this->hasMany('App\Models\BlogCategory', 'language_id');
    }

    public function categories()
    {
        return $this->hasMany('App\Models\Category', 'language_id');
    }

    public function subcategories()
    {
        return $this->hasMany('App\Models\Subcategory', 'language_id');
    }

    public function childcategories()
    {
        return $this->hasMany('App\Models\Childcategory', 'language_id');
    }

    public function faqs()
    {
        return $this->hasMany('App\Models\Faq', 'language_id');
    }

    public function packages()
    {
        return $this->hasMany('App\Models\Package', 'language_id');
    }

    public function pages()
    {
        return $this->hasMany('App\Models\Page', 'language_id');
    }

    public function pickups()
    {
        return $this->hasMany('App\Models\Pickup', 'language_id');
    }

    public function shippings()
    {
        return $this->hasMany('App\Models\Shipping', 'language_id');
    }

    public function sliders()
    {
        return $this->hasMany('App\Models\Slider', 'language_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'language_id');
    }
}
