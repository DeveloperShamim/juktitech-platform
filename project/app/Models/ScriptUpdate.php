<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScriptUpdate extends Model
{
    protected $fillable = ['script_name', 'version', 'file_path', 'file_name', 'release_notes', 'is_active'];

    protected $casts = [
        'is_active' => 'integer',
    ];
}
