<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('code', 15)->unique();
            $table->bigInteger('officer_id')->unsigned();
            $table->bigInteger('member_id')->unsigned();
            $table->date('loan_date');
            $table->date('return_date');
            $table->enum('status', ['Dipinjam', 'Dikembalikan']);
            $table->timestamps();

            $table->foreign('officer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
