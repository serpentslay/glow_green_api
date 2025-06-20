<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'location_id',
    ];

    protected $dates = ['deleted_at'];

    public function boilers()
    {
        return $this->hasMany(Boiler::class, 'boiler_manufacturer_id');
    }
}
