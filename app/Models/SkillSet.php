<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillSet extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "skill_sets";
    protected $fillable = [
        'candidate_id',
        'skill_id'
    ];

    public function candidate(){
        return $this->hasMany(Candidate::class);
    }

    public function skill(){
        return $this->hasMany(Skill::class);
    }
}
