<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\IndexRequest;
use App\Http\Requests\Project\MarkAsCompletedRequest;
use App\Http\Requests\Project\ShowRequest;
use App\Http\Requests\Project\StoreRequest;
use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexRequest $request
     * @return mixed
     */
    public function index(IndexRequest $request)
    {
        $projects = Project::where('is_completed', false)
            ->orderBy('created_at', 'desc')
            ->withCount([
                'tasks' => function ($query) {
                    $query->where('is_completed', false);
                }
            ])->get();

        return $projects->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();

        $project = Project::create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        return response()->json('Project created!');
    }

    /**
     * Display the specified resource.
     *
     * @param ShowRequest $request
     * @param $id
     * @return string
     */
    public function show(ShowRequest $request, $id)
    {
        $project = Project::with([
            'tasks' => function ($query) {
                $query->where('is_completed', false);
            }
        ])->find($id);

        return $project->toJson();
    }

    /**
     * Mark a project as completed.
     *
     * @param MarkAsCompletedRequest $request
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsCompleted(MarkAsCompletedRequest $request, Project $project)
    {
        $project->is_completed = true;
        $project->update();

        return response()->json('Project updated!');
    }
}
