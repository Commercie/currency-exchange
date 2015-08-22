<?php

/**
 * @file
 * Contains \BartFeenstra\CurrencyExchange\ExchangeRate.
 */

namespace BartFeenstra\CurrencyExchange;

/**
 * Provides an exchange rate.
 */
class ExchangeRate implements ExchangeRateInterface
{

    /**
     * The timestamp of the moment this rate was obtained.
     *
     * @var int
     */
    protected $timestamp;

    /**
     * The code of the destination currency.
     *
     * @var string
     */
    protected $destinationCurrencyCode;

    /**
     * The ID of the exchange rate provider that provided this rate.
     *
     * The ID is arbitrary and depends on the application.
     *
     * @return string|null
     */
    protected $exchangeRateProviderId;

    /**
     * The code of the source currency.
     *
     * @var string
     */
    protected $sourceCurrencyCode;

    /**
     * The exchange rate.
     *
     * @var string
     */
    protected $rate;

    /**
     * Constructs a new instance.
     *
     * @param string $source_currency_code
     *   The code of the source currency.
     * @param string $destination_currency_code
     *   The code of the destination currency.
     * @param string $rate
     *   The exchange rate.
     */
    public function __construct(
      $source_currency_code,
      $destination_currency_code,
      $rate
    ) {
        $this->destinationCurrencyCode = $destination_currency_code;
        $this->rate = $rate;
        $this->sourceCurrencyCode = $source_currency_code;
    }

    public function getDestinationCurrencyCode()
    {
        return $this->destinationCurrencyCode;
    }

    public function setDestinationCurrencyCode($currencyCode)
    {
        $this->destinationCurrencyCode = $currencyCode;

        return $this;
    }

    public function getSourceCurrencyCode()
    {
        return $this->sourceCurrencyCode;
    }

    public function setSourceCurrencyCode($currencyCode)
    {
        $this->sourceCurrencyCode = $currencyCode;

        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getExchangeRateProviderId()
    {
        return $this->exchangeRateProviderId;
    }

    public function setExchangeRateProviderId($id)
    {
        $this->exchangeRateProviderId = $id;

        return $this;
    }

}
