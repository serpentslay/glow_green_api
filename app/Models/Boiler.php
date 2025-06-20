<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Boiler extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sku',
        'boiler_manufacturer_id',
        'name',
        'description',
        'manufacturer_part_number',
        'fuel_type_id',
        'url',
    ];


    protected $dates = ['deleted_at'];


    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'boiler_manufacturer_id', 'id');
    }

    public function fuelType()
    {
        return $this->belongsTo(FuelType::class, 'fuel_type_id', 'id');
    }
}
