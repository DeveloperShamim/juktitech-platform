<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentAutomation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facebook_page_id',
        'trigger_word',
        'reply_comment',
        'private_reply',
    ];

    /**
     * Get the Facebook page that owns this comment automation.
     */
    public function facebookPage()
    {
        return $this->belongsTo(FacebookPage::class);
    }
}
