<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Resources\Task as TaskResource;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::orderBy('created_at')->get();

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->filled('title')) {
            return response()->json(['message' => "Title cannot be empty"]);
        }

        $task = new Task();
        $task->title = $request->title;
        $task->save();

        $tasks = Task::orderBy('created_at')->get();

        return TaskResource::collection($tasks);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if (!$request->filled('title')) {
            return response()->json(['message' => "Title cannot be empty"]);
        }

        $task->title = $request->title;
        $task->completed = $request->filled('completed') ? $request->completed : false;
        $task->save();

        $tasks = Task::orderBy('created_at')->get();

        return TaskResource::collection($tasks);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        $tasks = Task::orderBy('created_at')->get();

        return TaskResource::collection($tasks);
    }
}
