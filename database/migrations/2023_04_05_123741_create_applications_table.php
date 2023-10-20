<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ApplicationStatuses;

return new class extends Migration {
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_type_id')->constrained();
            $table->foreignId('citizen_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('mediator_id')->nullable()->constrained('users');
            $table->foreignId('judge_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('number');
            $table->string('status')->default(ApplicationStatuses::NEW->value);
            $table->text('application')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
};
