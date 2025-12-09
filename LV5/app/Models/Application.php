<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'status',
        'priority',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function store(Task $task)
    {
        $this->authorizeStudent();

        $lastPriority = Application::where('user_id', auth()->id())->max('priority') ?? 0;

        Application::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'task_id' => $task->id,
            ],
            [
                'status' => 'pending',
                'priority' => $lastPriority + 1,
            ]
        );

        return back()->with('success', 'Prijava je zaprimljena.');
    }

}

