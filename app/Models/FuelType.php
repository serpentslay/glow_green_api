<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuelType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location_id',
    ];

    protected $dates = ['deleted_at'];

    public function boilers()
    {
        return $this->hasMany(Boiler::class, 'fuel_type_id');
    }
}
