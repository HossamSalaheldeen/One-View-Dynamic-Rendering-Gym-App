<?php

use App\Models\Gym;
use App\Models\TrainingSession;
use App\Models\User;
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
        Schema::create('training_session_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TrainingSession::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->time('time')->nullable();
            $table->date('date')->nullable();
            $table->boolean('is_attended')->default(false);
            $table->boolean('is_expired')->default(false);
            $table->foreignIdFor(Gym::class)->constrained();
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
        Schema::dropIfExists('training_session_user');
    }
};
