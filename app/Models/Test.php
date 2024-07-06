<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Test extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'content',
        'user_id',
    ];

    protected $table = 'tests';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->where('role', 'contender');
    }

    public function judges(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->where('role', 'judge');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
}
