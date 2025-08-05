<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        return response()->json($this->taskService->getTasksForUser());
    }

    public function show($id)
    {
        $task = $this->taskService->taskRepository->find($id);
        if (!$task) {
            return response()->json(['message' => 'Tarefa não encontrada.'], 404);
        }
        return response()->json($task);
    }

    public function store(Request $request)
    {
        $task = $this->taskService->createTask($request->all());
        return response()->json(['message' => 'Tarefa criada com sucesso.', 'task' => $task], 201);
    }

    public function update(Request $request, $id)
    {
        $task = $this->taskService->updateTask($id, $request->all());
        if (!$task) {
            return response()->json(['message' => 'Tarefa não encontrada.'], 404);
        }
        return response()->json(['message' => 'Tarefa atualizada com sucesso.', 'task' => $task]);
    }

    public function destroy($id)
    {
        $task = $this->taskService->deleteTask($id);
        if (!$task) {
            return response()->json(['message' => 'Tarefa não encontrada.'], 404);
        }
        return response()->json(['message' => 'Tarefa excluída com sucesso.']);
    }
}
