<?php

namespace App\Models;

use App\Models\Itinerary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    public function itineraries()
    {
        return $this->HasMany(Itinerary::class);
    }
}
