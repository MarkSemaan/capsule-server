<?php

namespace App\Services;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;

class TodoService
{
    public function getAllTodos(): Collection
    {
        return Todo::all();
    }

    public function createTodo(array $data): Todo
    {
        return Todo::create($data);
    }

    public function findTodo(int $id): ?Todo
    {
        return Todo::find($id);
    }


    public function updateTodo(int $id, array $data): ?Todo
    {
        $todo = $this->findTodo($id);

        if ($todo) {
            $todo->update($data);
        }

        return $todo;
    }

    public function deleteTodo(int $id): bool
    {
        $todo = $this->findTodo($id);

        if ($todo) {
            return $todo->delete();
        }

        return false;
    }
}
