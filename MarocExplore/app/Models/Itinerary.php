<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Destination;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'category_id', 'duration', 'image','user_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
