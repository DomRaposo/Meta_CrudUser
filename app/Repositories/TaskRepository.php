<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function getAllByUser($userId)
    {
        return Task::with('user')->where('user_id', $userId)->get();
    }

    public function getPendingByUser($userId)
    {
        return Task::where('user_id', $userId)->where('status', '!=', 'completed')->get();
    }

    public function getCompletedByUser($userId)
    {
        return Task::where('user_id', $userId)->where('status', 'completed')->get();
    }

    public function getAll()
    {
        return Task::with('user')->get();
    }

    public function find($id)
    {
        return Task::with('user')->find($id);
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update($id, array $data)
    {
        $task = Task::find($id);
        if ($task) {
            $task->update($data);
        }
        return $task;
    }

    public function delete($id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->delete();
        }
        return $task;
    }
} 