<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;

Route::any('/', function (Request $request) {
    $dollars = $request->get('money', null);

    if ($dollars !== null) {
        try {
            $exchangeRates = new ExchangeRate();
            $euros = $exchangeRates->convert($dollars, 'USD', 'EUR', Carbon::now());
        } catch (\Exception $e) {
            $error = true;
            Log::error('Error fetching exchange rates: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
        }
    }

    return view('welcome', [
        'dollars' => $dollars,
        'euros' => $euros ?? null,
        'error' => $error ?? false,
    ]);
});
