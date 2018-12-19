<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Project
 *
 * @property int                                                              $id
 * @property int                                                              $user_id
 * @property string                                                           $title
 * @property int                                                              $normalize
 * @property int                                                              $scale
 * @property string|null                                                      $data_url
 * @property string|null                                                      $columns
 * @property string|null                                                      $configuration
 * @property string|null                                                      $result
 * @property \Illuminate\Support\Carbon                                       $created_at
 * @property \Illuminate\Support\Carbon                                       $updated_at
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
 * @property string                                                           $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereStatus($value)
 * @property string|null $task_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereTaskId($value)
 */
class Project extends Model
{
    /**
     * Statuses of the project running.
     */
    const STATUSES = ['new', 'pending', 'finished', 'failed'];

    public $table = 'projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'status', 'task_id', 'title', 'normalize', 'scale',
        'data_url', 'columns', 'configuration', 'result',
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
     * Get normalize flag.
     *
     * @return int
     */
    public function getNormalize()
    {
        return $this->normalize;
    }

    /**
     * Get scale flag.
     *
     * @return int
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Get data url.
     *
     * @return null|string
     */
    public function getDataUrl()
    {
        return $this->data_url;
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
     * Set status of the project.
     *
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Set task ID of the running project.
     *
     * @param $taskId
     */
    public function setTaskId($taskId) {
        $this->task_id = $taskId;
    }

    /**
     * Get configuration of the project.
     *
     * @return null|string
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Set configuration of the project.
     *
     * @param $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Get checked columns for the algorithm.
     *
     * @return null|string
     */
    public function getCheckedColumns()
    {
        return $this->columns;
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
