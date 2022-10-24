<?php

namespace App\Models;

use FunctionName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'correct_answer',
        'share',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Tag')->withTimestamps();
    }

    public function category(): HasOne
    {
        return $this->HasOne('App\Models\Category');
    }

    public function download_users(): BelongsToMany
    {
        return $this->BelongsToMany('App\Models\User', 'question_download')->withTimestamps();
    }

    public function isDownloadedBy(?User $user): bool
    {
        return $user
            ? (bool)$this->download->where('user_id', $user->id)->count()
            : false;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    public function likes(): BelongsToMany
    {
        return $this->BelongsToMany('App\Models\User', 'likes')->withTimestamps();
    }

    public function isLikedBy(?User $user): bool
    {
        return $user
            ? (bool)$this->likes->where('id', $user->id)->count()
            : false;
    }
}
