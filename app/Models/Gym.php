<?php

namespace App\Models;

use App\Traits\HasStaticTableName;
use App\Traits\HasTracking;
use App\Traits\HasTrackingRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Gym
 *
 * @property int $id
 * @property string $name
 * @property int $city_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\City $city
 * @property-read \App\Models\User|null $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $managers
 * @property-read int|null $managers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TrainingPackage[] $trainingPackages
 * @property-read int|null $training_packages_count
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Database\Factories\GymFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gym newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gym query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gym whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Gym extends Model
{
    use HasFactory, HasTracking, HasTrackingRelations, HasStaticTableName;

    protected $fillable = [
        'name',
        'city_id',
        'created_by',
        'updated_by'
    ];

    public function cover()
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function managers()
    {
        return $this->hasMany(User::class);
    }

    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }

    public function trainingPackages()
    {
        return $this->belongsToMany(TrainingPackage::class);
    }

    public function trainingSessionUsers()
    {
        return $this->hasMany(TrainingSessionUser::class);
    }

}
