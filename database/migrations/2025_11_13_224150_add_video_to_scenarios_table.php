<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scenarios', function (Blueprint $table) {

            if (!Schema::hasColumn('scenarios', 'video_path')) {
                $table->string('video_path')->nullable()->after('file_path');
            }

            if (!Schema::hasColumn('scenarios', 'video_url')) {
                $table->string('video_url')->nullable()->after('video_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('scenarios', function (Blueprint $table) {
            if (Schema::hasColumn('scenarios', 'video_path')) {
                $table->dropColumn('video_path');
            }

            if (Schema::hasColumn('scenarios', 'video_url')) {
                $table->dropColumn('video_url');
            }
        });
    }
};
