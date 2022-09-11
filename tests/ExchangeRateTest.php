<?php

/**
 * @file
 * Contains \Commercie\Tests\CurrencyExchange\ExchangeRateTest.
 */

namespace Commercie\Tests\CurrencyExchange;

use Commercie\CurrencyExchange\ExchangeRate;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Commercie\CurrencyExchange\ExchangeRate
 */
class ExchangeRateTest extends TestCase
{

    /**
     * The exchange rate under test.
     *
     * @var \Commercie\CurrencyExchange\ExchangeRate
     */
    protected $exchangeRate;

    public function setUp(): void
    {
        $sourceCurrencyCode = 'FOO';
        $destinationCurrencyCode = 'BAR';
        $rate = mt_rand();
        $this->exchangeRate = new ExchangeRate($sourceCurrencyCode, $destinationCurrencyCode, $rate);
    }

    /**
     * @covers ::__construct
     */
    public function testCreate()
    {
        $sourceCurrencyCode = 'FOO';
        $destinationCurrencyCode = 'BAR';
        $rate = mt_rand();
        $this->exchangeRate = new ExchangeRate($sourceCurrencyCode, $destinationCurrencyCode, $rate);
        $this->assertInstanceOf(ExchangeRate::class, $this->exchangeRate);
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
