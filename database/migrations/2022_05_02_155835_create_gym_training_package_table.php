<?php

use App\Models\Gym;
use App\Models\TrainingPackage;
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
        Schema::create('gym_training_package', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Gym::class)->constrained();
            $table->foreignIdFor(TrainingPackage::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gym_training_package');
    }
};
