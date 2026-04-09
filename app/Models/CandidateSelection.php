<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateSelection extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'job_post_id',
        'employee_id',
        'employer_id',
        'interview_date',
        'interview_status',
        'status',
    ];

    protected $casts = [
        'interview_date' => 'datetime',
    ];

    public function jobPost()
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
