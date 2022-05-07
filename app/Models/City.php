<?php

namespace App\Models;

use App\Traits\HasStaticTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\City
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Gym[] $gyms
 * @property-read int|null $gyms_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $managers
 * @property-read int|null $managers_count
 * @method static \Database\Factories\CityFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class City extends Model
{
    use HasFactory, HasStaticTableName;

    protected $fillable = [
        'name'
    ];

    public function gyms()
    {
        return $this->hasMany(Gym::class);
    }

    public function gymTrainingSessionUsers()
    {
        return $this->hasManyThrough(TrainingSessionUser::class, Gym::class)->attended(true);
    }

    public function managers()
    {
        return $this->hasMany(User::class);
    }

}
