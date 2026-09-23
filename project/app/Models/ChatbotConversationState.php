<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotConversationState extends Model
{
    protected $fillable = [
        'sender_id',
        'facebook_page_id',
        'flow_id',
        'current_node_id',
    ];

    public function facebookPage()
    {
        return $this->belongsTo(FacebookPage::class);
    }

    public function flow()
    {
        return $this->belongsTo(BotFlow::class, 'flow_id');
    }
}
