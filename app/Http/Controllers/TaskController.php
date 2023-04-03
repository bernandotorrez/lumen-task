<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    public function insert(Request $request)
    {
        $validation = Validator::make($request->post(), [
            'task_name' => 'required|min:3|max:50|alpha_num',
        ]);

        if($validation->fails()) {
            return response()->json([
                'code' => 400,
                'success' => false,
                'message' => 'ERROR PARAMETER',
                'data' => $validation->errors()->all()
            ], 400);
        }

        $insert = $this->task->create([
            'task_name' => $request->post('task_name'),
            'id_user' => auth()->user()['id_user']
        ]);

        if($insert) {
            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Successfully Insert Data',
                'data' => $insert
            ], 200);
        } else {
            return response()->json([
                'code' => 200,
                'success' => false,
                'message' => 'Failed Insert Data',
                'data' => null
            ], 200);
        }
    }
}
