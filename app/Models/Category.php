<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Relationships
     */
    public function applications()
    {
        return $this->belongsToMany(Application::class, 'app_categories');
    }

    public function appCategories()
    {
        return $this->hasMany(AppCategory::class);
    }
}
