<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public $table = 'projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'normalize', 'scale', 'data_url', 'columns', 'configuration', 'result'
    ];

    /**
     * Get id of the project.
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get title of the project.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get creation date of the project.
     *
     * @return string
     */
    public function getCreatedDate()
    {
        return $this->created_at;
    }

    /**
     * Get the role's user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
