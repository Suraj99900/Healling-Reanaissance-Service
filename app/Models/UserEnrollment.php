<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEnrollment extends Model
{
    use HasFactory;

    protected $table = 'user_enrollments';

    protected $fillable = [
        'username',
        'full_name',
        'phone',
        'email',
        'address',
        'additional_info',
        'status',
        'deleted',
        'added_on',
    ];

    // Optionally, add scopes for active/not deleted
    public function scopeActive($query)
    {
        return $query->where('status', 1)->where('deleted', 0);
    }
}