<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStripeCoulmnsInSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_subscription_plan', function (Blueprint $table) {
            $table->string('stripe_id')->collation('utf8mb4_bin');
            $table->string('stripe_plan');
            $table->integer('quantity');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_subscription_plan', function (Blueprint $table) {
            $table->dropColumn('stripe_id');
            $table->dropColumn('stripe_plan');
            $table->dropColumn('quantity');
            $table->dropColumn('trial_ends_at');
            $table->dropColumn('ends_at');
        });
    }
}
