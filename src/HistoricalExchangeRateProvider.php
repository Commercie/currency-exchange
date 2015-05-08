<?php

/**
 * @file
 * Contains \BartFeenstra\CurrencyExchange\HistoricalExchangeRateProvider.
 */

namespace BartFeenstra\CurrencyExchange;

use Symfony\Component\Yaml\Yaml;

/**
 * Provides historical exchange rates.
 */
class HistoricalExchangeRateProvider implements ExchangeRateProviderInterface
{

    public function loadMultiple(array $currencyCodes)
    {
        $rates = [];
        foreach ($currencyCodes as $sourceCurrencyCode => $destinationCurrencyCodes) {
            foreach ($destinationCurrencyCodes as $destinationCurrencyCode) {
                $rates[$sourceCurrencyCode][$destinationCurrencyCode] = $this->load($sourceCurrencyCode,
                  $destinationCurrencyCode);
            }
        }

        return $rates;
    }

    public function load($sourceCurrencyCode, $destinationCurrencyCode)
    {
        $rate = null;

        $exchangeRates = Yaml::parse(file_get_contents(__DIR__ . '/../resources/historical_rates.yml'));

        if (isset($exchangeRates[$sourceCurrencyCode][$destinationCurrencyCode])) {
            $rate = $exchangeRates[$sourceCurrencyCode][$destinationCurrencyCode];
        }

        // Conversion rates are two-way. If a reverse rate is unavailable, set it.
        if (!$rate) {
            if (isset($exchangeRates[$destinationCurrencyCode][$sourceCurrencyCode])) {
                $rate = bcdiv(1,
                  $exchangeRates[$destinationCurrencyCode][$sourceCurrencyCode],
                  6);
            }
        }

        if ($rate) {
            return new ExchangeRate(null, $sourceCurrencyCode,
              $destinationCurrencyCode, $rate);
        }

        return null;
    }
}
