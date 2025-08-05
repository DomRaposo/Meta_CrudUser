<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Enums\TaskStatusEnum;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function validateTask(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:' . implode(',', TaskStatusEnum::all()),
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function createTask(array $data)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Usuário não autenticado.');
        }
        $this->validateTask($data);
        $data['user_id'] = $user->id;
        $data['status'] = $data['status'] ?? TaskStatusEnum::PENDING;
        Log::info('Criando tarefa', $data);
        return $this->taskRepository->create($data);
    }

    public function markAsCompleted($taskId)
    {
        $task = $this->taskRepository->find($taskId);
        if (!$task) abort(404, 'Tarefa não encontrada.');
        $task->status = TaskStatusEnum::COMPLETED;
        $task->save();
        return $task;
    }

    public function getTasksForUser()
    {
        $user = Auth::user();
        if ($user->role === 'isAdmin') {
            return $this->taskRepository->getAll();
        } else {
            return $this->taskRepository->getAllByUser($user->id);
        }
    }

    public function updateTask($id, array $data)
    {
        $user = Auth::user();
        $task = $this->taskRepository->find($id);
        if (!$task) abort(404, 'Tarefa não encontrada.');
        if ($user->role !== 'admin' && $task->user_id !== $user->id) {
            abort(403, 'Você não tem permissão para editar esta tarefa.');
        }
        return $this->taskRepository->update($id, $data);
    }

    public function deleteTask($id)
    {
        $user = Auth::user();
        $task = $this->taskRepository->find($id);
        if (!$task) abort(404, 'Tarefa não encontrada.');
        if ($user->role !== 'admin' && $task->user_id !== $user->id) {
            abort(403, 'Você não tem permissão para excluir esta tarefa.');
        }
        return $this->taskRepository->delete($id);
    }

    public function getPendingTasks($userId)
    {
        return $this->taskRepository->getPendingByUser($userId);
    }

    public function getCompletedTasks($userId)
    {
        return $this->taskRepository->getCompletedByUser($userId);
    }
}
