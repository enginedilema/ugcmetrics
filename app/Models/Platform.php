<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{

    const REDDIT_ID = 1;
    const INSTAGRAM_ID = 2;
    const TIKTOK_ID = 3;
    const YOUTUBE_ID = 4;
    const TWITCH_ID = 5;
    const LINKEDIN_ID = 6;
    const FACEBOOK_ID = 7;
    const TWITTER_ID = 8;

    /** @use HasFactory<\Database\Factories\PlatformFactory> */
    use HasFactory;
    protected $fillable = ['name', 'description', 'base_url'];

    public function socialProfiles()
    {
        return $this->hasMany(SocialProfile::class);
    }


}
