<?php

/**
 * @file
 * Contains BartFeenstra\CurrencyExchange\ExchangeRateInterface;
 */

namespace BartFeenstra\CurrencyExchange;

/**
 * Defines an exchange rate.
 */
interface ExchangeRateInterface
{

    /**
     * Gets the code of the destination currency.
     *
     * @return string
     *   An ISO 4217 code.
     */
    public function getDestinationCurrencyCode();

    /**
     * Sets the code of the destination currency.
     *
     * @param string $currencyCode
     *   An ISO 4217 code.
     *
     * @return $this
     */
    public function setDestinationCurrencyCode($currencyCode);

    /**
     * Gets the code of the source currency.
     *
     * @return string
     *   An ISO 4217 code.
     */
    public function getSourceCurrencyCode();

    /**
     * Sets the code of the source currency.
     *
     * @param string $currencyCode
     *   An ISO 4217 code.
     *
     * @return $this
     */
    public function setSourceCurrencyCode($currencyCode);

    /**
     * Gets the exchange rate.
     *
     * @return string
     */
    public function getRate();

    /**
     * Sets the exchange rate.
     *
     * @param string $rate
     *
     * @return $this
     */
    public function setRate($rate);

    /**
     * Gets the timestamp of the moment this rate was obtained.
     *
     * @return int|null
     *   A Unix timestamp.
     */
    public function getTimestamp();

    /**
     * Sets the timestamp of the moment this rate was obtained.
     *
     * @param int $timestamp
     *   A Unix timestamp.
     *
     * @return $this
     */
    public function setTimestamp($timestamp);

    /**
     * Gets the ID of the exchange rate provider that provided this rate.
     *
     * The ID is arbitrary and depends on the application.
     *
     * @return string|null
     */
    public function getExchangeRateProviderId();

    /**
     * Sets the ID of the exchange rate provider that provided this rate.
     *
     * The ID is arbitrary and depends on the application.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setExchangeRateProviderId($id);

}
