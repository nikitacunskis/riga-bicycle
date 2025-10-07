<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('places', function (Blueprint $t) {
            // DOUBLE is fine for WGS84; 15–16 sig. digits.
            $t->double('lat')->nullable()->after('coordinates');
            $t->double('lng')->nullable()->after('lat');
            $t->index(['lat', 'lng'], 'places_lat_lng_idx');
        });

        DB::statement("
            UPDATE places
            SET
                lat = CAST(SUBSTRING_INDEX(TRIM(coordinates), '/',  1) AS DECIMAL(10,8)),
                lng = CAST(SUBSTRING_INDEX(TRIM(coordinates), '/', -1) AS DECIMAL(11,8))
            WHERE coordinates REGEXP '^-?[0-9]+(\\.[0-9]+)?\\s*/\\s*-?[0-9]+(\\.[0-9]+)?$'
        ");

        DB::statement("
            UPDATE places SET lat = NULL WHERE lat IS NOT NULL AND (lat < -90 OR lat > 90)
        ");
        DB::statement("
            UPDATE places SET lng = NULL WHERE lng IS NOT NULL AND (lng < -180 OR lng > 180)
        ");
    }

    public function down(): void
    {
        Schema::table('places', function (Blueprint $t) {
            $t->dropIndex('places_lat_lng_idx');
            $t->dropColumn(['lat','lng']);
        });
    }
};
