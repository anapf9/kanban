<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['title', 'slug', 'order'];

    public $timestamps = false; //Ps because in tutorial this migration haven`t timestamps

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('order');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
