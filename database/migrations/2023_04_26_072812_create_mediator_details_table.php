<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\MediatorStatuses;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mediator_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mediator_company_id')->constrained()->onDelete('cascade');
            $table->string('mediator_specialization')->nullable();
            $table->integer('group_id')->nullable();
            $table->string('status')->default(MediatorStatuses::NEW->value);
            $table->boolean('had_license_before')->default(false);
            $table->text('cv')->nullable();
            $table->text('avatar')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mediator_details');
    }
};
