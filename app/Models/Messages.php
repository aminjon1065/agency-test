<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subject',
        'description',
        'files_link',
        'user_id',
        'opened',
        'user_id',
        'from',
        'to',
    ];

    protected $casts = [
        'files_link' => 'array',
    ];
}
