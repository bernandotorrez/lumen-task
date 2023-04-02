<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Task extends Model {

    protected $table = 'task';
    protected $primaryKey = 'id_task';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_task', 'id_user', 'task_name', 'status', 'is_deleted'
    ];

    /**
     * Relation to User::class by id_user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
