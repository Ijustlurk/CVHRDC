<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('maintenance', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'maintenance_mode', 'maintenance_message'
            $table->string('value')->nullable(); // e.g., 'on', 'off
            $table->boolean('active')->default(false); // true = maintenance on
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('maintenance');
    }
};
