<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\{Model, Prunable, SoftDeletes};

class Question extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    use Prunable;

    protected $fillable = [
        'question',
        'draft',
    ];

    protected $casts = [
        'draft' => 'bool',
    ];

    public function likes(): Attribute
    {
        return new Attribute(get: fn () => $this->votes->sum('like'));
    }

    public function unlikes(): Attribute
    {
        return new Attribute(get: fn () => $this->votes->sum('unlike'));
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function prunable(): Builder
    {
        return static::where('deleted_at', '<', now()->subMonth());
    }
}
