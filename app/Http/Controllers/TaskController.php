<?php

namespace App\Http\Controllers;
use App\Models\Task;

class TaskController extends Controller
{
    protected Task $task;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function all()
    {
        $data = $this->task->where('is_deleted', '0')->get();

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Successfully Retrieve Data',
            'data' => $data
        ], 200);
    }
}
