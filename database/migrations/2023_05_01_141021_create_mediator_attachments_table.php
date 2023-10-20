<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mediator_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mediator_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mediator_attachments');
    }
};
