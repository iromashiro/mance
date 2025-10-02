<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'image_path',
        'caption',
        'upload_order',
    ];

    public $timestamps = false;

    /**
     * Relationships
     */
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
