<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_visitor_id',
        'method',
        'endpoint',
        'request_payload',
        'response_payload',
        'status_code',
        'ip_address',
        'time_spent',
        'user_agent',
        'created_at',
        'updated_at',
    ];
}
