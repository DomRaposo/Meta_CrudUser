<?php

namespace App\Repositories\Forum;

use App\Models\Forum\Thread;
use App\Repositories\BaseRepository;

class ThreadRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Thread::class);
    }

    // Adicione métodos específicos de consulta para Thread aqui, se necessário
} 