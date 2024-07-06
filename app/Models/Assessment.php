<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Assessment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'score',
        'test_id',
        'judge_id',
        'type'
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function judge(): BelongsTo
    {
        return $this->belongsTo(User::class,'judge_id')->where('role', 'judge');
    }
}
