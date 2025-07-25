<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
            Schema::create('maintenance', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();      // ex: maintenance_mode
            $table->string('value');              // ex: on/off or message string
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('maintenance');
    }
};
