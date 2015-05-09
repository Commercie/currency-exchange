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
     * @param int $timestamp
     *   The timestamp of the moment this rate was obtained.
     * @param string $source_currency_code
     *   The code of the source currency.
     * @param string $destination_currency_code
     *   The code of the destination currency.
     * @param string $rate
     *   The exchange rate.
     */
    protected function __construct(
      $timestamp,
      $source_currency_code,
      $destination_currency_code,
      $rate
    ) {
        $this->destinationCurrencyCode = $destination_currency_code;
        $this->rate = $rate;
        $this->sourceCurrencyCode = $source_currency_code;
        $this->timestamp = $timestamp;
    }

    /**
     * Creates a new instance.
     *
     * @param int $timestamp
     *   The timestamp of the moment this rate was obtained.
     * @param string $sourceCurrencyCode
     *   The code of the source currency.
     * @param string $destinationCurrencyCode
     *   The code of the destination currency.
     * @param string $rate
     *   The exchange rate.
     *
     * @return static
     */
    public static function create(
      $timestamp,
      $sourceCurrencyCode,
      $destinationCurrencyCode,
      $rate
    ) {
        return new static($timestamp, $sourceCurrencyCode, $destinationCurrencyCode, $rate);
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
