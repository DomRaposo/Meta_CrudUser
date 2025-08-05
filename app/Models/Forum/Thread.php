<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $table = 'threads';
    protected $fillable = [
        'title',
        'description',
        'person_id',
        // Adicione outros campos necessários aqui
    ];
} 