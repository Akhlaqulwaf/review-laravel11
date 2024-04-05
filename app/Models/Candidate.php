<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $table = 'candidates';
    protected $fillable = [
        'name',
        'job_id',
        'email',
        'phone'
    ];

    public function job(){
        return $this->belongsTo(job::class);
    }

    public function skillSet(){
        return $this->hasMany(SkillSet::class);
    }
}
