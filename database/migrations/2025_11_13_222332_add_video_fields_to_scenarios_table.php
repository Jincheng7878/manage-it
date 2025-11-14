<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scenarios', function (Blueprint $table) {
            // local uploaded mp4
            $table->string('video_path')->nullable()->after('file_path');
            // youtube or other video url
            $table->string('video_url')->nullable()->after('video_path');
        });
    }

    public function down(): void
    {
        Schema::table('scenarios', function (Blueprint $table) {
            $table->dropColumn(['video_path', 'video_url']);
        });
    }
};
