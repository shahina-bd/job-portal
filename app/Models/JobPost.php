<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'job_description',
        'publish_date',
        'end_date',
        'requirements',
        'salary',
        'currency',
        'job_type',
        'status',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'end_date' => 'date',
        'salary' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
