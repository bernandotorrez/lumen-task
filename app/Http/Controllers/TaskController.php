<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $data = $this->task->where('is_deleted', '0')->with('user:id_user,username,name')->get();

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Successfully Retrieve Data',
            'data' => $data
        ], 200);
    }

    public function getById($id = null)
    {
        $data = $this->task->where([
            'id_task' => $id,
            'is_deleted' => '0'
        ])->with('user:id_user,username,name')->first();

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => ($data) ? 'Successfully Retrieve Data' : 'Data not Found',
            'data' => $data ?? null
        ], 200);
    }

    public function getByUser()
    {
        $data = $this->task->where([
            'id_user' => auth()->user()['id_user'],
            'is_deleted' => '0'
        ])->with('user:id_user,username,name')->get();

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

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->post(), [
            'task_name' => 'required|min:3|max:50|alpha_num',
            'status' => 'required|in:0,1,2'
        ]);

        if($validation->fails()) {
            return response()->json([
                'code' => 400,
                'success' => false,
                'message' => 'ERROR PARAMETER',
                'data' => $validation->errors()->all()
            ], 400);
        }

        $data = $this->task->where([
            'id_task' => $id,
            'is_deleted' => '0'
        ]);

        if(!$data->first()) {
            return response()->json([
                'code' => 200,
                'success' => false,
                'message' => 'Data not Found',
                'data' => null
            ], 200);
        } else {
            $update = $data->update([
                'task_name' => $request->post('task_name'),
                'status' => $request->post('status')
            ]);

            if($update) {
                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Successfully Update Data',
                    'data' => $update
                ], 200);
            } else {
                return response()->json([
                    'code' => 200,
                    'success' => false,
                    'message' => 'Failed Update Data',
                    'data' => null
                ], 200);
            }
        }
    }

    public function delete($id = null)
    {
        $data = $this->task->where([
            'id_task' => $id,
            'is_deleted' => '0'
        ]);

        if(!$data->first()) {
            return response()->json([
                'code' => 200,
                'success' => false,
                'message' => 'Data not Found',
                'data' => null
            ], 200);
        } else {
            $update = $data->update([
                'is_deleted' => '1'
            ]);

            if($update) {
                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Successfully Delete Data',
                    'data' => $update
                ], 200);
            } else {
                return response()->json([
                    'code' => 200,
                    'success' => false,
                    'message' => 'Failed Delete Data',
                    'data' => null
                ], 200);
            }
        }
    }
}
