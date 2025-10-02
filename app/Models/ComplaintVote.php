<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'user_id',
        'vote_type',
    ];

    public $timestamps = false;

    /**
     * Relationships
     */
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
