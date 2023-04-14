<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Collect extends Model
{
    use HasFactory, Uuids;

    protected $table = 'collects';

    protected $fillable = [
        'collection_id',
        'product_id',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
