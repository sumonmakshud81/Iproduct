<?php

use Illuminate\Database\Seeder;

class PaymentSettingsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 2; $i++) {
            \App\PaymentSettings::create([
                'status' => 'inactive',
                'method' => $i === 0 ? "Paypal" : "Stripe",
                'type' => "sandbox",
                'client_id' => $i === 0 ? "<client_id>" : "<stripe_api_key>",
                'secret_key' => $i === 0 ? "<secret_key>" : "<stripe_publishable_key>",
                'created_by' => 1,
                'updated_by' => 1
            ]);
        }
    }
}
