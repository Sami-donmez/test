<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name',50)->nullable();
            $table->string('contact_name',50);
            $table->string('contact_email',100);
            $table->string('contact_phone',20)->nullable();
            $table->string('contact_address',20)->nullable();
            $table->string('zip',20)->nullable();
            $table->string('city',20)->nullable();
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
        Schema::dropIfExists('customers');
    }
}
