<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address', 'phonenumber', 'userType'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


   /**
    * Get the track options associated with the user.
    */
    public function employee()
    {
        return $this->hasMany(Employee::class);
    }


   /**
    * Get the track options associated with the user.
    */
    public function physicalTrack()
    {
        return $this->hasOne(PhysicalTrack::class);
    }


   /**
    * Get the track options associated with the user.
    */
    public function SpiritualTrack()
    {
        return $this->hasOne(SpiritualTrack::class);
    }        


   /**
    * Get the track options associated with the user.
    */
    public function SleepTrack()
    {
        return $this->hasOne(SleepTrack::class);
    }


   /**
    * Get the reminder time associated with the user.
    */
    public function reminderTime()
    {
        return $this->hasOne(ReminderTime::class);
    }

   /**
    * Get the nutrition Data associated with the user.
    */
    public function nutritionStore()
    {
        return $this->hasMany(NutritionStore::class);
    }


   /**
    * Get the Sleep Data associated with the user.
    */
    public function sleepStore()
    {
        return $this->hasMany(SleepStore::class);
    }


   /**
    * Get the physicalStore Data associated with the user.
    */
    public function physicalStore()
    {
        return $this->hasMany(PhysicalStore::class);
    }


   /**
    * Get the SpiritualStore Data associated with the user.
    */
    public function SpiritualStore()
    {
        return $this->hasMany(SpiritualStore::class);
    }

}
