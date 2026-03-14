<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('whatsapp', 20)->nullable()->after('bio');
            $table->string('facebook_url')->nullable()->after('whatsapp');
            $table->string('tiktok_url')->nullable()->after('facebook_url');
            $table->string('instagram_url')->nullable()->after('tiktok_url');
            $table->string('twitter_url')->nullable()->after('instagram_url');
            $table->string('youtube_url')->nullable()->after('twitter_url');
            $table->string('linkedin_url')->nullable()->after('youtube_url');
            $table->string('website_url')->nullable()->after('linkedin_url');
            $table->string('city', 100)->nullable()->after('website_url');
            $table->string('country', 100)->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
