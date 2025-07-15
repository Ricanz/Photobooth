<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'type',
        'image',
        'border_top',
        'border_bottom',
        'border_right',
        'border_left'
    ];
}
