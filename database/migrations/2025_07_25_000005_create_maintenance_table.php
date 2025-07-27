<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('maintenance', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(false); // true = maintenance on
            $table->string('message')->nullable();
            $table->string('active')->nullable();
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('maintenance');
    }
};
