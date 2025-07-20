<?php

namespace App\Http\Controllers;

use \App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Requests;


class TodoController extends Controller
{

    public function index()
    {
        $todos = Todo::all();
        return response()->json(
            [
                'status' => 'success',
                'todos' => $todos,
            ]
        );
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        $todo = Todo::create($validatedData);
        return response()->json([
            'status' => 'success',
            'message' => 'created todo',
            'todos' => $todo,
        ]);
    }

    public function show($id)
    {
        $todos = Todo::find($id);
        return response()->json([
            'status' => 'success',
            'todo' => $todos
        ]);
    }
}
