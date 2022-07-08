<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'extension',
        'path',
        'user_id',
        'size'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
