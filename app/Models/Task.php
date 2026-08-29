<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['task_name', 'pic_id', 'deadline', 'status', 'priority'];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function pic()
    {
        return $this->belongsTo(Employee::class, 'pic_id');
    }
}
