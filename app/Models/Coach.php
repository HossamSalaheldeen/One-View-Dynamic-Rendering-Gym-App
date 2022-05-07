<?php

namespace App\Models;

use App\Traits\HasStaticTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Coach
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TrainingSession[] $trainingSessions
 * @property-read int|null $training_sessions_count
 * @method static \Database\Factories\CoachFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Coach newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coach newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coach query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coach whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coach whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coach whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Coach extends Model
{
    use HasFactory, HasStaticTableName;

    protected $fillable = [
        'name'
    ];

    public function trainingSessions()
    {
        return $this->belongsToMany(TrainingSession::class);
    }
}
