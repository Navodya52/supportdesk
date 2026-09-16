<?php

namespace App\Models;

use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory;

    public const PRIORITY_LOW = 'low';

    public const PRIORITY_MEDIUM = 'medium';

    public const PRIORITY_HIGH = 'high';

    public const PRIORITY_CRITICAL = 'critical';

    public const STATUS_OPEN = 'open';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_PENDING = 'pending';

    public const STATUS_RESOLVED = 'resolved';

    public const STATUS_CLOSED = 'closed';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ticket_number',
        'user_id',
        'assigned_to',
        'category_id',
        'title',
        'description',
        'priority',
        'status',
        'resolution',
        'resolved_at',
        'closed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    /**
     * Boot model events to auto-generate ticket numbers.
     */
    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket): void {
            if (empty($ticket->ticket_number)) {
                $maxId = (int) (static::max('id') ?? 0);
                $ticket->ticket_number = 'SD-'.str_pad((string) ($maxId + 1), 6, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the user who opened the ticket.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the assigned support agent.
     *
     * @return BelongsTo<User, $this>
     */
    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the category of the ticket.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get all comments for the ticket in chronological order.
     *
     * @return HasMany<Comment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->oldest();
    }

    /**
     * Scope query to search ticket number or title.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term): void {
            $q->where('ticket_number', 'like', "%{$term}%")
                ->orWhere('title', 'like', "%{$term}%");
        });
    }

    /**
     * Scope query by status.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        if (blank($status)) {
            return $query;
        }

        return $query->where('status', $status);
    }

    /**
     * Scope query by priority.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeFilterPriority(Builder $query, ?string $priority): Builder
    {
        if (blank($priority)) {
            return $query;
        }

        return $query->where('priority', $priority);
    }

    /**
     * Scope query by category ID.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeFilterCategory(Builder $query, ?string $categoryId): Builder
    {
        if (blank($categoryId)) {
            return $query;
        }

        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope query according to the authenticated user's role.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isAgent()) {
            // Agents can see all tickets or filter by assigned, but at minimum tickets assigned to them or in pool
            return $query;
        }

        return $query->where('user_id', $user->id);
    }
}
