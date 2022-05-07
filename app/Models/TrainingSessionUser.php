<?php

namespace App\Models;

use App\Traits\HasStaticTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TrainingSessionUser extends Pivot
{
    use HasFactory, HasStaticTableName;

    public function scopeAttended($q, $isAttended)
    {
        return $q->where('is_attended', $isAttended);
    }

    public function scopeExpired($q, $isExpired)
    {
        return $q->where('is_expired', $isExpired);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainingSession()
    {
        return $this->belongsTo(TrainingSession::class);
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }
}
