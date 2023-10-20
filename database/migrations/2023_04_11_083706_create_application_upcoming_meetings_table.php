<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ApplicationMeetingStatuses;

return new class extends Migration
{
    public function up()
    {
        Schema::create('application_upcoming_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->date('date');
            $table->time("start");
            $table->time("end");
            $table->string('address')->nullable();
            $table->text('url')->nullable();
            $table->string('code')->nullable();
            $table->string('status')->default(ApplicationMeetingStatuses::UNCONFIRMED->value);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('application_upcoming_meetings');
    }
};
