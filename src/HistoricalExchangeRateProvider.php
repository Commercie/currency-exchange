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

    use FixedExchangeRateProviderTrait;

    protected function loadAll() {
        return Yaml::parse(file_get_contents(__DIR__ . '/../resources/historical_rates.yml'));
    }

}
