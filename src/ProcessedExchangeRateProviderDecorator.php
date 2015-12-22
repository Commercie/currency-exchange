<?php

/**
 * @file
 * Contains \Commercie\CurrencyExchange\ProcessedExchangeRateProviderDecorator.
 */

namespace Commercie\CurrencyExchange;

/**
 * Provides a exchange rate provider decorator to process exchange rates.
 */
class ProcessedExchangeRateProviderDecorator implements ExchangeRateProviderInterface
{

    /**
     * The decorated exchange rate provider.
     *
     * @var \Commercie\CurrencyExchange\ExchangeRateProviderInterface
     */
    protected $decoratedExchangeRateProvider;

    /**
     * The process callback.
     *
     * @var callable
     *   The callable takes \Commercie\CurrencyExchange\ExchangeRateInterface as
     *   its only argument, and MUST return
     *   \Commercie\CurrencyExchange\ExchangeRateInterface.
     */
    protected $processCallback;

    /**
     * Creates a new instance.
     *
     * @param \Commercie\CurrencyExchange\ExchangeRateProviderInterface $decoratedExchangeRateProvider
     *   The decorated exchange rate provider.
     * @param callable $processCallback
     *   A process callback that takes
     *   \Commercie\CurrencyExchange\ExchangeRateInterface|null as its only
     *   argument, and MUST return
     *   \Commercie\CurrencyExchange\ExchangeRateInterface|null.
     */
    public function __construct(
      ExchangeRateProviderInterface $decoratedExchangeRateProvider,
      callable $processCallback
    ) {
        $this->decoratedExchangeRateProvider = $decoratedExchangeRateProvider;
        $this->processCallback = $processCallback;
    }

    public function load($sourceCurrencyCode, $destinationCurrencyCode)
    {
        $exchangeRate = $this->decoratedExchangeRateProvider->load($sourceCurrencyCode,
          $destinationCurrencyCode);

        return call_user_func($this->processCallback, $exchangeRate);
    }

    public function loadMultiple(array $currencyCodes)
    {
        $exchangeRates = $this->decoratedExchangeRateProvider->loadMultiple($currencyCodes);
        foreach ($exchangeRates as $sourceCurrencyCode => $exchangeRatePerSourceCurrency) {
            foreach ($exchangeRatePerSourceCurrency as $destinationCurrencyCode => $exchangeRate) {
                $exchangeRates[$sourceCurrencyCode][$destinationCurrencyCode] = call_user_func($this->processCallback,
                  $exchangeRate);
            }
        }

        return $exchangeRates;
    }

}
