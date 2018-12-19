<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Project
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property int $normalize
 * @property int $scale
 * @property string|null $data_url
 * @property string|null $columns
 * @property string|null $configuration
 * @property string|null $result
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereColumns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereConfiguration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereDataUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereNormalize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereScale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereUserId($value)
 * @mixin \Eloquent
 */
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
     * Set data url of the project.
     *
     * @param null $dataUrl
     */
    public function setDataUrl($dataUrl = null)
    {
        $this->data_url = $dataUrl;
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
