<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory, SoftDeletes;

    const MONTH_EXPIRED = 6;

    protected $fillable = [
        'imageable_type',
        'imageable_id',
        'path',
        'old_name',
    ];

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::url($this->path)
        );
    }

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
