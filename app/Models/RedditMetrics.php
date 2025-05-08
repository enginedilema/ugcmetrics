<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedditMetrics extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_profile_id', 
        'date', 
        'followers', 
        'comments_count',
        'posts_count',
        'upvotes_count',
        'interaction_ratio',
        'top_post_title',
        'top_post_upvotes',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function socialProfile()
    {
        return $this->belongsTo(SocialProfile::class);
    }
}
