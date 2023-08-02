<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'phone',
        'address',
    ];

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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function scopeSearch($query, $term){
        $term = "%$term%";
        $query->where(function($query) use ($term){
            $query->where('name','like',$term)
            ->orWhere('address','like',$term)
            ->orWhere('phone','like',$term)
            ->orWhereHas('role', function ($query) use ($term) {
                $query->where('name','like',$term);
            });
        });
    }

    public function branch()
    {
        return $this->hasOne(Branch::class);
    }

    public function project()
    {
        return $this->hasOne(Project::class);
    }

    public function branchUser()
    {
        return $this->hasOne(BranchUser::class);
    }

    public function projectUser()
    {
        return $this->hasOne(ProjectUser::class);
    }

    public function businessunit()
    {
        return $this->hasOne(BusinessUnit::class, 'manager_id', 'id');
    }

    public function getUserRoleAttribute() 
    {
        if(session()->has('user_role')) {
            return session('user_role');
        }

        // Get the user role from related model
        $user_role = Auth::user()->role->name;

        // Save it to session
        session(['user_role' => $user_role]);
        return $user_role;
    }

    public function getUserBusinessUnitAttribute() 
    {
        if(session()->has('user_business_unit_id')) {
            return session('user_business_unit_id');
        }

        // Get the user role from related model
        $user_role = Auth::user()->role->name;

        if($user_role == "Admin"){
            $user_business_unit_id = null;
        }elseif($user_role == "Manager"){
            $user_business_unit_id = isset(Auth::user()->businessunit->id) ? Auth::user()->businessunit->id : null;
        }elseif($user_role == "Staff"){
            $user_business_unit_id = null;
        }else{
            $user_business_unit_id = null;
        }

        // Save it to session
        session(['user_business_unit_id' => $user_business_unit_id]);
        return $user_business_unit_id;
    }
}
