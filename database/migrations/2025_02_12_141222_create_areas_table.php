<?php

use App\Enums\AreaEnum;
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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('area_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('area_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('user_areas', function (Blueprint $table){
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('area_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('user_programs', function (Blueprint $table){
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
        Schema::dropIfExists('area_parameters');
        Schema::dropIfExists('user_areas');
        Schema::dropIfExists('user_programs');
    }
};
