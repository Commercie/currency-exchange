<?php

/**
 * @file
 * Contains \BartFeenstra\CurrencyExchange\FixedExchangeRateProviderTrait.
 */

namespace BartFeenstra\CurrencyExchange;

/**
 * Provides a base for exchange rate providers with fixed rates.
 */
trait FixedExchangeRateProviderTrait
{

    /**
     * Loads all exchange rates.
     *
     * @return array[]
     *   Keys are the ISO 4217 codes of source currencies, values are arrays of
     *   which the keys are ISO 4217 codes of destination currencies and values
     *   are \BartFeenstra\CurrencyExchange\ExchangeRateInterface objects, or NULL for
     *   combinations of currencies for which no exchange rate could be found.
     */
    abstract protected function loadAll();

    /**
     * Implements \BartFeenstra\CurrencyExchange\ExchangeRateProviderInterface::load().
     */
    public function load($sourceCurrencyCode, $destinationCurrencyCode)
    {
        $rate = null;

        $exchangeRates = $this->loadAll();

        if (isset($exchangeRates[$sourceCurrencyCode][$destinationCurrencyCode])) {
            $rate = $exchangeRates[$sourceCurrencyCode][$destinationCurrencyCode];
        }
        // Conversion rates are two-way. If a reverse rate is unavailable, set it.
        elseif (isset($exchangeRates[$destinationCurrencyCode][$sourceCurrencyCode])) {
            $rate = bcdiv(1,
              $exchangeRates[$destinationCurrencyCode][$sourceCurrencyCode],
              6);
        }

        if ($rate) {
            return new ExchangeRate(null, $sourceCurrencyCode,
              $destinationCurrencyCode, $rate);
        }

        return null;
    }

    /**
     * Implements \BartFeenstra\CurrencyExchange\ExchangeRateProviderInterface::loadMultiple().
     */
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

}
