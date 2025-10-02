<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'category_id',
        'title',
        'description',
        'location_address',
        'location_lat',
        'location_lng',
        'status',
        'priority',
        'is_public',
        'resolved_at',
        'resolution_note',
        'satisfaction_rating',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'resolved_at' => 'datetime',
        'location_lat' => 'decimal:8',
        'location_lng' => 'decimal:8',
    ];

    /**
     * Generate ticket number
     */
    public static function generateTicketNumber()
    {
        $date = now()->format('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;
        return sprintf('CPL-%s-%04d', $date, $count);
    }

    /**
     * Scopes
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Check if status can transition
     */
    public function canTransitionTo($newStatus)
    {
        $rules = [
            'pending' => ['in_progress', 'rejected'],
            'in_progress' => ['resolved', 'rejected', 'pending'],
            'resolved' => ['in_progress'],
            'rejected' => []
        ];

        return in_array($newStatus, $rules[$this->status] ?? []);
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ComplaintCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ComplaintImage::class);
    }

    public function responses()
    {
        return $this->hasMany(ComplaintResponse::class);
    }

    public function votes()
    {
        return $this->hasMany(ComplaintVote::class);
    }

    /**
     * Get upvotes count
     */
    public function getUpvotesAttribute()
    {
        return $this->votes()->where('vote_type', 'up')->count();
    }

    /**
     * Get downvotes count
     */
    public function getDownvotesAttribute()
    {
        return $this->votes()->where('vote_type', 'down')->count();
    }
}
