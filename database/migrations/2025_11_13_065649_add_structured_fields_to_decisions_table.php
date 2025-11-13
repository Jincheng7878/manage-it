<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('decisions', function (Blueprint $table) {
            // SWOT fields
            $table->text('swot_strengths')->nullable()->after('notes');
            $table->text('swot_weaknesses')->nullable()->after('swot_strengths');
            $table->text('swot_opportunities')->nullable()->after('swot_weaknesses');
            $table->text('swot_threats')->nullable()->after('swot_opportunities');

            // Work Breakdown Structure
            $table->text('wbs')->nullable()->after('swot_threats');

            // Risk matrix & cost breakdown
            $table->text('risk_matrix')->nullable()->after('wbs');
            $table->text('cost_breakdown')->nullable()->after('risk_matrix');
        });
    }

    public function down(): void
    {
        Schema::table('decisions', function (Blueprint $table) {
            $table->dropColumn([
                'swot_strengths',
                'swot_weaknesses',
                'swot_opportunities',
                'swot_threats',
                'wbs',
                'risk_matrix',
                'cost_breakdown',
            ]);
        });
    }
};
