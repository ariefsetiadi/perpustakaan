<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('officer_id', 20)->unique();
            $table->string('fullname');
            $table->boolean('isAdmin')->default(0);
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->enum('gender', ['Laki-Laki', 'Perempuan']);
            $table->string('address');
            $table->string('phone', 15)->unique();
            $table->string('image')->nullable();
            // $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            // $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
