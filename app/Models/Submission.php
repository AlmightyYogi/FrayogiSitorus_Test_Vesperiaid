<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'submission_id', 'payloads'];
    
    protected $casts = [
        'payloads' => 'array', // Ini akan secara otomatis mengonversi ke array
    ];
}
