<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'facebook_page_id',
        'name',
        'description',
        'flow_data',
        'is_active',
        'trigger_type',
        'trigger_values',
    ];

    protected $casts = [
        'flow_data' => 'array',
        'trigger_values' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facebookPage()
    {
        return $this->belongsTo(FacebookPage::class);
    }
}
