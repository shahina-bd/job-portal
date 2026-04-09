<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'job_post_id',
        'employee_id',
        'employer_id',
        'status',
        'apply_date',
    ];

    protected $casts = [
        'apply_date' => 'datetime',
    ];

    public function job()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }
}
