<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'category_id',
        'user_category',
    ];

    /**
     * Relationships
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
