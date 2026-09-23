<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotReply extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facebook_page_id',
        'keyword',
        'reply_text',
        'reply_type',
        'attachment',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'attachment' => 'array',
    ];

    /**
     * Get the Facebook page that owns this bot reply.
     */
    public function facebookPage()
    {
        return $this->belongsTo(FacebookPage::class);
    }
}
