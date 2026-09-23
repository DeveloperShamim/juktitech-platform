<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StolenDomain extends Model {
    protected $fillable = ['domain_name', 'ip_address', 'full_url', 'status', 'cache_signal', 'is_blocked'];
}