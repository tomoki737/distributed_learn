<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'question_id'
    ];

    public function questions(): BelongsToMany {
        return $this->belongsToMany('App\Models\Question')->withTimestamps();
    }
}
