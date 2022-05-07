<?php

namespace App\Models;

use App\Traits\HasStaticTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TrainingPackage
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Gym|null $gym
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TrainingSession[] $trainingSessions
 * @property-read int|null $training_sessions_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\TrainingPackageFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPackage query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPackage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPackage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingPackage extends Model
{
    use HasFactory, HasStaticTableName;

    protected $fillable = [
        'name',
        'price',
    ];

    protected $appends = ['dollar_price'];

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }

    public function getDollarPriceAttribute()
    {
        return $this->price / 100 ;
    }

    public function trainingSessions()
    {
        return $this->belongsToMany(TrainingSession::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function gyms()
    {
        return $this->belongsToMany(Gym::class);
    }

    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }

}
