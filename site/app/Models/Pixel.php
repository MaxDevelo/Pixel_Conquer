<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pixel extends Model
{
    use HasFactory;

    protected $fillable = ['coordinate_x', 'coordinate_y', 'color', 'user_id'];
    protected $table = 'Pixel';
    protected $primaryKey = 'pixel_id';
    public $timestamps = false;
}


