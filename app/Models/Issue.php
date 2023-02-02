<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Issue extends Model
{
    use HasFactory;

    protected $attributes = [
        'status' => Status::OPEN,
    ];

    protected $fillable = [
        'topic',
        'description',
        'category_id',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    protected static function booted()
    {
        static::addGlobalScope(new DefaultStatuesScope());
    }

    public function issueCategory(): BelongsTo
    {
        return $this->belongsTo(IssueCategory::class, 'category_id');
    }
}
