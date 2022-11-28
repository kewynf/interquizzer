<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'discord_id',
    ];

    public function exams()
    {
        return $this->hasMany(Exams::class);
    }
}
