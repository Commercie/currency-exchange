<?php

/**
 * @file
 * Contains \BartFeenstra\CurrencyExchange\Unit\ExchangeRateUnitTest.
 */

namespace BartFeenstra\CurrencyExchange\Unit;

use BartFeenstra\CurrencyExchange\ExchangeRate;

/**
 * @coversDefaultClass \BartFeenstra\CurrencyExchange\ExchangeRate
 *
 * @group Currency
 */
class ExchangeRateUnitTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The exchange rate under test.
     *
     * @var \BartFeenstra\CurrencyExchange\ExchangeRate
     */
    protected $exchangeRate;

    public function setUp()
    {
        $timestamp = mt_rand();
        $source_currency_code = 'FOO';
        $destination_currency_code = 'BAR';
        $rate = mt_rand();
        $this->exchangeRate = new ExchangeRate($timestamp,
          $source_currency_code, $destination_currency_code, $rate);
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $timestamp = mt_rand();
        $source_currency_code = 'FOO';
        $destination_currency_code = 'BAR';
        $rate = mt_rand();
        $this->exchangeRate = new ExchangeRate($timestamp,
          $source_currency_code, $destination_currency_code, $rate);
    }

    /**
     * @covers ::getDestinationCurrencyCode
     * @covers ::setDestinationCurrencyCode
     */
    public function testGetDestinationCurrencyCode()
    {
        $currency_code = 'BAZ';
        $this->assertSame($this->exchangeRate,
          $this->exchangeRate->setDestinationCurrencyCode($currency_code));
        $this->assertSame($currency_code,
          $this->exchangeRate->getDestinationCurrencyCode());
    }

    /**
     * @covers ::getSourceCurrencyCode
     * @covers ::setSourceCurrencyCode
     */
    public function testGetSourceCurrencyCode()
    {
        $currency_code = 'BAZ';
        $this->assertSame($this->exchangeRate,
          $this->exchangeRate->setSourceCurrencyCode($currency_code));
        $this->assertSame($currency_code,
          $this->exchangeRate->getSourceCurrencyCode());
    }

    /**
     * @covers ::getRate
     * @covers ::setRate
     */
    public function testGetRate()
    {
        $rate = mt_rand();
        $this->assertSame($this->exchangeRate,
          $this->exchangeRate->setRate($rate));
        $this->assertSame($rate, $this->exchangeRate->getRate());
    }

    /**
     * @covers ::getTimestamp
     * @covers ::setTimestamp
     */
    public function testGetTimestamp()
    {
        $timestamp = mt_rand();
        $this->assertSame($this->exchangeRate,
          $this->exchangeRate->setTimestamp($timestamp));
        $this->assertSame($timestamp, $this->exchangeRate->getTimestamp());
    }

}
