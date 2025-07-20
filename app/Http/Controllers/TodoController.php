<?php

namespace App\Http\Controllers;

use App\Services\TodoService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    use ApiResponse;

    protected TodoService $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    public function index()
    {
        $todos = $this->todoService->getAllTodos();

        return $this->successResponse(['todos' => $todos]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        $todo = $this->todoService->createTodo($validatedData);

        return $this->successResponse(['todo' => $todo], 'Todo created successfully');
    }

    public function show($id)
    {
        $todo = $this->todoService->findTodo($id);

        if (!$todo) {
            return $this->notFoundResponse('Todo not found');
        }

        return $this->successResponse(['todo' => $todo]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:255'
        ]);

        $todo = $this->todoService->updateTodo($id, $validatedData);

        if (!$todo) {
            return $this->notFoundResponse('Todo not found');
        }

        return $this->successResponse(['todo' => $todo], 'Todo updated successfully');
    }

    public function destroy($id)
    {
        $deleted = $this->todoService->deleteTodo($id);

        if (!$deleted) {
            return $this->notFoundResponse('Todo not found');
        }

        return $this->successResponse(null, 'Todo deleted successfully');
    }
}
