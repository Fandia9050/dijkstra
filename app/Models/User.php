<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [
       
    ];
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['permissions_with_assign'];

    public function getPermissionsWithAssignAttribute()
    {
        $allPermissions = Permission::all();
        $userPermissions = $this->permissions(); // dari relasi di atas

        return $allPermissions->map(function ($permission) use ($userPermissions) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'assigned' => $userPermissions->contains('id', $permission->id),
            ];
        });
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
    
    // relasi user → permission lewat role
    public function permissions()
    {
        return $this->roles()
                    ->with('permissions') // eager load biar gak N+1
                    ->get()
                    ->pluck('permissions')
                    ->flatten()
                    ->unique('id');
    }

    public function getRoleIdAttribute()
    {
        return $this->roles->first()->id ?? null;
    }

    public function getRoleNameAttribute()
    {
        return $this->roles->first()->name ?? null;
    }
}
