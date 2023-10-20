<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('application_meeting_recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained('application_meeting_histories')->onDelete('cascade');
            $table->string('name');
            $table->text('path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('application_meeting_recordings');
    }
};
