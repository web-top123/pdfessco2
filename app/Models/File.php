<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = [

        'description',
        'name',
        'path',
        'real_name',
        'user_id',
        'category_id'

    ];

    protected $hidden = [
        'real_name',
        'user_id',
    ];

    protected $events = [

        'deleting' => FileBeforeDelete::class,

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function breadcrumbs()
    {
         return $this->attributes['breadcrumbs'] = Category::ancestorsAndSelf($this->category_id)->map(function ($category) { return $category->name; });
    }

    public function getPathAttribute($value)
    {
        return url(\Storage::url($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('m.d.Y - h:i');
    }

    public function scopeHasCategory($query)
    {
        return $query->whereNotNull('category_id');
    }
 
}
