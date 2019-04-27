<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use UnexpectedValueException;

/**
 * App\Models\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[]
 *                $notifications
 * @property-read \App\Models\Role
 *                    $role
 * @mixin \Eloquent
 * @property int                                                                 $id
 * @property int                                                                 $role_id
 * @property string                                                              $name
 * @property string                                                              $email
 * @property string|null                                                         $email_verified_at
 * @property string                                                              $password
 * @property string|null                                                         $remember_token
 * @property \Illuminate\Support\Carbon                                          $created_at
 * @property \Illuminate\Support\Carbon                                          $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 */
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
     * Set password.
     *
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = \Hash::make($password);
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
     * Get user's policy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Get user's projects.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Check if user has a role.
     *
     * @param string|array $role
     *
     * @return bool
     * @throws \UnexpectedValueException
     */
    public function hasRole($role)
    {
        if (\is_array($role)) {
            return $this->hasAtLeastOneRole($role);
        }

        switch ($role) {
            case 'admin':
                return $this->isAdministrator();
            case 'client':
                return $this->isClient();
            default:
                throw new UnexpectedValueException("Role '" . $role . "' is invalid to check. "
                    . 'Please, have a look at roles in configuration.');
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

    private function hasAtLeastOneRole($roles)
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }
}
