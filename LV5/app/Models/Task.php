<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title_hr',
        'title_en',
        'description_hr',
        'description_en',
        'study_type',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function isAppliedByUser(?int $userId): bool
    {
        if (!$userId) {
            return false;
        }

        return $this->applications->contains('user_id', $userId);
    }

}

