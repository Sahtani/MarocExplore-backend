<?php

namespace App\Models;

use App\Models\Itinerary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;
    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

}
