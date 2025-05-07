<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YouTubeMetrics extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_profile_id',
        'date',
        'subscribers',
        'views',
        'likes',
        'comments',
        'video_count',
        'average_watch_time',
        'channel_quality_score',
    ];

    public function socialProfile()
    {
        return $this->belongsTo(SocialProfile::class);
    }
}
