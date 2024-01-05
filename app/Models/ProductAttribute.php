<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $table = 'product_attributes';
    protected $guarded = [];


    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
