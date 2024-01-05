<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $guarded = [];


    public function attributes()
    {
        return $this->belongsToMany(Attribute::class , 'attribute_categories');
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
