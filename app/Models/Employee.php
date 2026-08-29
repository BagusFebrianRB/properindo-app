<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Contracts\Activity;

class Employee extends Model
{
    use LogsActivity;

    protected $fillable = [
        'employee_code',
        'name',
        'department_id',
        'jabatan_id',
        'email',
        'status',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'pic_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['employee_code', 'name', 'department_id', 'jabatan_id', 'email', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->merge([
            'employee_name' => $this->name,
        ]);
    }
}
