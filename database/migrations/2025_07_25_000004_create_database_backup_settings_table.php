<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('database_backup_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('frequency')->default(5);
            $table->timestamp('last_backup')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('database_backup_settings');
    }
};
