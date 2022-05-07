<?php

namespace App\Models;

use App\Traits\HasStaticTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Revenue
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\RevenueFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Revenue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Revenue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Revenue query()
 * @method static \Illuminate\Database\Eloquent\Builder|Revenue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Revenue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Revenue whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Revenue extends Model
{
    use HasFactory, HasStaticTableName;

    protected $fillable = [
        'amount',
        'user_id',
        'training_package_id',
        'gym_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainingPackage()
    {
        return $this->belongsTo(TrainingPackage::class);
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }
}
