<?php

/**
 * @file
 * Contains \BartFeenstra\CurrencyExchange\AbstractStackedExchangeRateProvider.
 */

namespace BartFeenstra\CurrencyExchange;

/**
 * Gets exchange rates from multiple exchange rate providers.
 */
abstract class AbstractStackedExchangeRateProvider implements ExchangeRateProviderInterface
{

    /**
     * Gets the available exchange rate providers.
     *
     * @return \BartFeenstra\CurrencyExchange\ExchangeRateProviderInterface[]
     *   Keys are exchange rate provider IDs and values are exchange rate
     *   providers. They are ordered by priority, with the highest priority
     *   first.
     */
    abstract protected function getExchangeRateProviders();

    public function load($sourceCurrencyCode, $destinationCurrencyCode)
    {
        if ($sourceCurrencyCode == $destinationCurrencyCode) {
            return ExchangeRate::create(time(), $sourceCurrencyCode,
              $destinationCurrencyCode, 1);
        }

        foreach ($this->getExchangeRateProviders() as $exchangeRateProviderId => $exchangeRateProvider) {
            $rate = $exchangeRateProvider->load($sourceCurrencyCode,
              $destinationCurrencyCode);
            if ($rate instanceof ExchangeRateInterface) {
                return $rate->setExchangeRateProviderId($exchangeRateProviderId);
            }
        }

        return null;
    }

    public function loadMultiple(array $currencyCodes)
    {
        $exchangeRates = [];

        foreach ($currencyCodes as $sourceCurrencyCode => $destinationCurrencyCodes) {
            // Include all requested rates as unavailable from the start, so
            // they are included in the return value, even if they cannot be
            //loaded later on.
            $exchangeRates[$sourceCurrencyCode] = array_fill_keys($destinationCurrencyCodes,
              null);

            // Set rates for identical source and destination currencies.
            foreach ($destinationCurrencyCodes as $index => $destinationCurrencyCode) {
                if ($sourceCurrencyCode == $destinationCurrencyCode) {
                    $exchangeRates[$sourceCurrencyCode][$destinationCurrencyCode] = ExchangeRate::create(null,
                      $sourceCurrencyCode, $destinationCurrencyCode, 1);
                    // Prevent the rate from being loaded by any exchange rate
                    // providers.
                    unset($currencyCodes[$sourceCurrencyCode][$index]);
                }
            }
        }

        foreach ($this->getExchangeRateProviders() as $exchangeRateProviderId => $exchangeRateProvider) {
            $currencyCodes = array_filter($currencyCodes);
            if ($currencyCodes) {
                foreach ($exchangeRateProvider->loadMultiple($currencyCodes) as $sourceCurrencyCode => $destinationCurrencyCodes) {
                    foreach ($destinationCurrencyCodes as $destinationCurrencyCode => $rate) {
                        if ($rate instanceof ExchangeRateInterface) {
                            $exchangeRates[$sourceCurrencyCode][$destinationCurrencyCode] = $rate->setExchangeRateProviderId($exchangeRateProviderId);
                            // Prevent the rate from being loaded again by other
                            // exchange rate providers.
                            $index = array_search($destinationCurrencyCode,
                              $currencyCodes[$sourceCurrencyCode]);
                            unset($currencyCodes[$sourceCurrencyCode][$index]);
                        }

                    }
                }
            }
        }

        return $exchangeRates;
    }

}
