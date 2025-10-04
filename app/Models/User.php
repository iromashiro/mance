<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'category',
        'role',
        'profile_photo_path',
        'banned_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'banned_at' => 'datetime',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Relationships
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    public function favorites()
    {
        return $this->hasMany(UserFavorite::class);
    }

    public function favoriteApplications()
    {
        return $this->belongsToMany(Application::class, 'user_favorites');
    }

    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    public function newsViews()
    {
        return $this->hasMany(NewsView::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function complaintResponses()
    {
        return $this->hasMany(ComplaintResponse::class, 'admin_id');
    }

    public function complaintVotes()
    {
        return $this->hasMany(ComplaintVote::class);
    }
}
