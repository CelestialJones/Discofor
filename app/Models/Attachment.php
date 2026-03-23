<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'article_id',
        'disk',
        'file_path',
        'original_name',
        'mime_type',
        'size',
    ];

    /**
     * Get the article that owns the attachment.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Delete the physical file when the record is removed.
     */
    protected static function booted(): void
    {
        static::deleting(function (Attachment $attachment) {
            if ($attachment->file_path && Storage::disk($attachment->disk)->exists($attachment->file_path)) {
                Storage::disk($attachment->disk)->delete($attachment->file_path);
            }
        });
    }

    /**
     * Human-readable size helper.
     */
    public function getHumanSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = (float) $this->size;
        $power = 0;

        while ($size >= 1024 && $power < count($units) - 1) {
            $size /= 1024;
            $power++;
        }

        return number_format($size, $power === 0 ? 0 : 2) . ' ' . $units[$power];
    }
}
