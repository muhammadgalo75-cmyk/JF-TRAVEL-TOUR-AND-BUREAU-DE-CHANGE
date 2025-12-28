<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'description',
        'image',
        'tour_count',
    ];

    protected $casts = [
        'tour_count' => 'integer',
    ];
}
