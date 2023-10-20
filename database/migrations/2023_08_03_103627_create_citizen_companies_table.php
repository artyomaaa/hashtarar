<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('citizen_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('company_name');
            $table->string('address');
            $table->string('registration_number');
            $table->string('name_of_representative');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('citizen_companies');
    }
};
