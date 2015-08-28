<?php

/**
 * @file
 * Contains \Commercie\CurrencyExchange\ExchangeRate.
 */

namespace Commercie\CurrencyExchange;

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
     * @param string $sourceCurrencyCode
     *   The code of the source currency.
     * @param string $destinationCurrencyCode
     *   The code of the destination currency.
     * @param string $rate
     *   The exchange rate.
     */
    public function __construct(
      $sourceCurrencyCode,
      $destinationCurrencyCode,
      $rate
    ) {
        $this->destinationCurrencyCode = $destinationCurrencyCode;
        $this->rate = $rate;
        $this->sourceCurrencyCode = $sourceCurrencyCode;
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

}
