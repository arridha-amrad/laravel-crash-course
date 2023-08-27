<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    //  Note
    //  I drop tokenable_type and tokenable_id manually before run this migration
    public function up(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->ulidMorphs('tokenable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
