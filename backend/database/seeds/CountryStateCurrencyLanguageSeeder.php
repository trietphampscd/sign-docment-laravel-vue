<?php

use Illuminate\Database\Seeder;

class CountryStateCurrencyLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $country = factory(App\Country::class,10)->create();
      $state = factory(App\State::class,10)->create();
      $currency = factory(App\Currency::class,10)->create();
      $language = factory(App\Language::class,10)->create();        
    }
}
