<?php

declare(strict_types=1);

use App\Enums\MediatorApplicationStatuses;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mediator_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('number');
            $table->string('status')->default(MediatorApplicationStatuses::PENDING->value);
            $table->integer('application_type_id');
            $table->text('application_cause')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mediator_applications');
    }
};
