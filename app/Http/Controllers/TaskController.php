<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\MarkAsCompletedRequest;
use App\Http\Requests\Task\StoreRequest;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();

        $task = Task::create([
            'title' => $data['title'],
            'project_id' => $request->project_id,
        ]);

        return $task->toJson();
    }

    /**
     * Mark a task as completed.
     *
     * @param MarkAsCompletedRequest $request
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsCompleted(MarkAsCompletedRequest $request, Task $task)
    {
        $task->is_completed = true;
        $task->update();

        return response()->json('Task updated!');
    }
}
