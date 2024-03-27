<?php

use App\Models\Itinerary;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->foreignIdFor(Itinerary::class)->constrained();  
        });
    }

    public function down()
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropForeign(['itinerary_id']);
            $table->dropColumn('itinerary_id');
        });
    }
};
