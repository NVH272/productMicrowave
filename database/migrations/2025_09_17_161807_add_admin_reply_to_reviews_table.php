<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'admin_reply')) {
                $table->text('admin_reply')->nullable()->after('comment');
            }
            if (!Schema::hasColumn('reviews', 'admin_id')) {
                $table->foreignId('admin_id')->nullable()->after('admin_reply')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('reviews', 'replied_at')) {
                $table->timestamp('replied_at')->nullable()->after('admin_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'replied_at')) {
                $table->dropColumn('replied_at');
            }
            if (Schema::hasColumn('reviews', 'admin_id')) {
                $table->dropConstrainedForeignId('admin_id');
            }
            if (Schema::hasColumn('reviews', 'admin_reply')) {
                $table->dropColumn('admin_reply');
            }
        });
    }
};
