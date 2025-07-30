<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsDefaultCardColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_cards', function (Blueprint $table) {
            $table->boolean('is_default')->default(0)->after('id');
            $table->string('stripe_token_id')->default(0)->after('is_default');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_cards', function (Blueprint $table) {
            $table->dropColumn('is_default');
            $table->dropColumn('stripe_token_id');
        });
    }
}
