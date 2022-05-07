<?php

namespace App\Models;

use App\Traits\HasStaticTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TrainingSession
 *
 * @property int $id
 * @property string $name
 * @property string $starts_at
 * @property string $finishes_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Coach[] $coaches
 * @property-read int|null $coaches_count
 * @property-read \App\Models\TrainingPackage|null $trainingPackage
 * @method static \Database\Factories\TrainingSessionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereFinishesAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingSession extends Model
{
    use HasFactory, HasStaticTableName;

    protected $fillable = [
        'name',
        'starts_at',
        'finishes_at',
        'is_attended'
    ];

    public function coaches()
    {
        return $this->belongsToMany(Coach::class);
    }

//    public function getCoachesCountAttribute()
//    {
//        return $this->belongsToMany(Coach::class)->count();
//    }

    public function trainingPackages()
    {
        return $this->belongsToMany(TrainingPackage::class);
    }

    public function trainingSessionUser()
    {
        return $this->hasMany(TrainingSessionUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('time', 'date', 'is_attended', 'gym_id')
            ->withTimestamps()
            ->using(TrainingSessionUser::class);
    }
}
