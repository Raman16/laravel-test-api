<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskList extends Model
{
    use HasFactory;
    public const NOT_STARTED = 'not_started';
    public const PENDING     = 'pending';
    public const STARTED     = 'started';

    protected $fillable = ['title','todo_list_id','status','description','label_id'];

    public function todo_lists() : BelongsTo {
        return $this->belongsTo('App\Models\TodoList','todo_list_id');
    }
}
