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
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->integer('bldgid')->nullable();
            $table->text('campus')->nullable();
            $table->string('bldgname')->nullable();
            $table->string('desc')->nullable();
            $table->string('address')->nullable();
            $table->string('date_completed')->nullable();


            $table->string('bldgfloorarea')->nullable();
            $table->string('bldgtype')->nullable();
            $table->string('bldgnumberoffloors')->nullable();
            $table->string('bldgnumberofrooms')->nullable();
            $table->string('bldgnumberofcrs')->nullable();

            $table->string('bldgCSUcertfilename')->nullable();
            $table->string('bldgFIREcertfilename')->nullable();
            $table->string('bldgOCCUPANCYcertfilename')->nullable();
            $table->string('bldgLGUcertfilename')->nullable();

            $table->string('lat')->nullable();
            $table->string('long')->nullable();

            $table->string('college_office_assigned')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
