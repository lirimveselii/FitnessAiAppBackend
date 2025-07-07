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
        Schema::table('exercises', function (Blueprint $table) {
             $table->enum('source_type', ['default', 'ai', 'user'])->default('default')->after('rest_seconds');
        $table->unsignedBigInteger('created_by')->nullable()->after('source_type');
        $table->boolean('is_public')->default(false)->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
             $table->dropForeign(['created_by']);
        $table->dropColumn(['source_type', 'created_by', 'is_public']);
        });
    }
};
