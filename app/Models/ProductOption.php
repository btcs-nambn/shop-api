<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;


class ProductOption extends Model
{
    use HasFactory, Uuids;

    protected $table = 'product_options';

    protected $fillable = [
        'product_id',
        'name',
        'values',
    ];
}
