<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'role_id', 'email', 'password',
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
     * Get id of the user.
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get name of the user.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name for user.
     *
     * @param string $name
     */
    public function setName($name = null)
    {
        $this->name = $name;
    }

    /**
     * Get email of the user.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set name for user.
     *
     * @param bool $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Set role id for user.
     *
     * @param bool $roleId
     */
    public function setRole($roleId)
    {
        $this->role_id = $roleId;
    }

    /**
     * Get registration date of the user.
     *
     * @return mixed
     */
    public function getRegistrationDate()
    {
        return $this->created_at;
    }

    /**
     * Check if user has a role.
     *
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role = 'admin')
    {
        switch ($role) {
            case 'admin':
                return $this->isAdministrator();
            case 'partner':
                return $this->isClient();
            default:
                return false;
        }
    }

    /**
     * Check if user is administrator.
     *
     * @return bool
     */
    public function isAdministrator(): bool
    {
        return $this->role_id === Role::ADMIN_ROLE_ID;
    }

    /**
     * Check if user is partner.
     *
     * @return bool
     */
    public function isClient(): bool
    {
        return $this->role_id === Role::CLIENT_ROLE_ID;
    }

    /**
     * Get user's policy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
