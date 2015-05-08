<?php

/**
 * @file
 * Contains \BartFeenstra\CurrencyExchange\ExchangeRateProviderInterface.
 */

namespace BartFeenstra\CurrencyExchange;

/**
 * Defines a currency exchange rate provider.
 */
interface ExchangeRateProviderInterface
{

    /**
     * Returns the exchange rate for two currencies.
     *
     * @param string $sourceCurrencyCode
     * @param string $destinationCurrencyCode
     *
     * @return \BartFeenstra\CurrencyExchange\ExchangeRateInterface|null
     */
    public function load($sourceCurrencyCode, $destinationCurrencyCode);

    /**
     * Returns the exchange rates for multiple currency combinations.
     *
     * @param array[] $currencyCodes
     *   Keys are the ISO 4217 codes of source currencies, values are arrays that
     *   contain ISO 4217 codes of destination currencies. Example:
     *   [
     *     'EUR' => ['NLG', 'DEM', 'XXX'],
     *   ]
     *
     * @return array[]
     *   Keys are the ISO 4217 codes of source currencies, values are arrays of
     *   which the keys are ISO 4217 codes of destination currencies and values
     *   are \BartFeenstra\CurrencyExchange\ExchangeRateInterface objects, or NULL for
     *   combinations of currencies for which no exchange rate could be found.
     */
    public function loadMultiple(array $currencyCodes);

}
