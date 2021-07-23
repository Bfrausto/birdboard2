<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class Project extends Model
{
    use RecordsActivity;

    use HasFactory;

    protected $guarded=[];



/**
 * Prepare a date for array / JSON serialization.
 *
 * @param  \DateTimeInterface  $date
 * @return string
 */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function path()
    {
        return "/projects/{$this->id}";
    }
    public function owner()
    {
        return $this->belongsTo(User::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }


    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }


}
