<?php

/**
 * @file
 * Contains \Commercie\CurrencyExchange\HistoricalExchangeRateProvider.
 */

namespace Commercie\CurrencyExchange;

/**
 * Provides historical exchange rates.
 */
class HistoricalExchangeRateProvider implements ExchangeRateProviderInterface
{

    use FixedExchangeRateProviderTrait;

    protected function loadAll() {
        static $exchangeRates;

        if (is_null($exchangeRates)) {
            $objectExchangeRates = json_decode(file_get_contents(__DIR__ . '/../resources/historical_rates.json'));
            foreach ((array) $objectExchangeRates as $sourceCurrencyCode => $objectExchangeRate) {
                $exchangeRates[$sourceCurrencyCode] = (array) $objectExchangeRate;
            }
        }

        return $exchangeRates;
    }

}
