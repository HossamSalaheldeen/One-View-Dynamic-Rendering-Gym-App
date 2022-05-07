<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Enums\PlanMandatoryStatus;
use App\Enums\RoleEnum;
use App\Traits\HasStaticTableName;
use Cog\Laravel\Ban\Traits\Bannable;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $national_id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property int|null $city_id
 * @property int|null $gym_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attendance[] $attendances
 * @property-read int|null $attendances_count
 * @property-read \App\Models\Attachment|null $avatar
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Gym|null $gym
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TrainingPackage[] $trainingPackages
 * @property-read int|null $training_packages_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGymId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNationalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements BannableContract
{
    use HasApiTokens, HasFactory, Notifiable, Bannable , HasRoles, HasStaticTableName;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'national_id',
        'gender',
        'email',
        'date_of_birth',
        'password',
        'city_id',
        'gym_id'
    ];

//    protected $with = ['avatar'];
//    protected $dateFormat = 'm-d-Y';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    protected $appends = ['resource'];

//    /**
//     * @return string
//     */
//    public function getResourceAttribute()
//    {
//        return $this->getTable();
//    }

    /**
     * @param $value
     * hashing password when saving
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function scopeNotAdmin($q) {
        return $q->whereDoesntHave('roles',function ($q){
            $q->where('name', RoleEnum::ADMIN);
        });
    }

    public function avatar()
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function trainingSessions()
    {
        return $this->belongsToMany(TrainingSession::class)
            ->withPivot('time', 'date', 'is_attended', 'is_expired', 'gym_id')
            ->withTimestamps()
            ->using(TrainingSessionUser::class);
    }

    public function notAttendedTrainingSessions()
    {
        return $this->belongsToMany(TrainingSession::class)
            ->withPivot('time', 'date', 'is_attended', 'is_expired', 'gym_id')
            ->withTimestamps()
            ->using(TrainingSessionUser::class)
            ->wherePivot('is_attended', false);
    }

    public function attendedTrainingSessions()
    {
        return $this->belongsToMany(TrainingSession::class)
            ->withPivot('time', 'date', 'is_attended', 'is_expired', 'gym_id')
            ->withTimestamps()
            ->using(TrainingSessionUser::class)
            ->wherePivot('is_attended', true);
    }

    public function trainingPackages()
    {
        return $this->belongsToMany(TrainingPackage::class);
    }


    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }


}
