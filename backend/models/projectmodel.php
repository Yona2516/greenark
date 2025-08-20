<?php
// app/Models/Project.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'short_description', 'category',
        'status', 'budget', 'start_date', 'completion_date', 'location',
        'client_name', 'gallery', 'before_after', 'case_study', 'features',
        'is_featured', 'is_published', 'sort_order', 'seo_meta'
    ];

    protected $casts = [
        'gallery' => 'array',
        'before_after' => 'array',
        'features' => 'array',
        'seo_meta' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'start_date' => 'date',
        'completion_date' => 'date',
        'budget' => 'decimal:2'
    ];

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}