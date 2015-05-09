<?php

/**
 * @file Contains \BartFeenstra\Tests\CurrencyExchange\AbstractStackedExchangeRateProviderTest.
 */

namespace BartFeenstra\Tests\CurrencyExchange;

use BartFeenstra\CurrencyExchange\ExchangeRate;

/**
 * @coversDefaultClass \BartFeenstra\CurrencyExchange\AbstractStackedExchangeRateProvider
 */
class AbstractStackedExchangeRateProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The class under test.
     *
     * @var \BartFeenstra\CurrencyExchange\AbstractStackedExchangeRateProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = $this->getMockForAbstractClass('\BartFeenstra\CurrencyExchange\AbstractStackedExchangeRateProvider');
    }

    /**
     * @covers ::load
     */
    public function testLoad()
    {
        $sourceCurrencyCode = 'EUR';
        $destinationCurrencyCode = 'NLG';
        $rate = '2.20371';

        $exchangeRateProviderA = $this->getMock('\BartFeenstra\CurrencyExchange\ExchangeRateProviderInterface');
        $exchangeRateProviderA->expects($this->once())
          ->method('load')
          ->with($sourceCurrencyCode, $destinationCurrencyCode)
          ->willReturn(null);

        $exchangeRateProviderB = $this->getMock('\BartFeenstra\CurrencyExchange\ExchangeRateProviderInterface');
        $exchangeRateProviderB->expects($this->once())
          ->method('load')
          ->with($sourceCurrencyCode, $destinationCurrencyCode)
          ->willReturn($rate);

        $exchangeRateProviderC = $this->getMock('\BartFeenstra\CurrencyExchange\ExchangeRateProviderInterface');
        $exchangeRateProviderC->expects($this->never())
          ->method('load');

        $this->sut->expects($this->atLeastOnce())
          ->method('getExchangeRateProviders')
          ->willReturn([
            $exchangeRateProviderA,
            $exchangeRateProviderB,
            $exchangeRateProviderC
          ]);

        $this->assertSame($rate,
          $this->sut->load($sourceCurrencyCode, $destinationCurrencyCode));
    }

    /**
     * @covers ::load
     */
    public function testLoadWithIdenticalCurrencies()
    {
        $sourceCurrencyCode = 'EUR';
        $destinationCurrencyCode = 'EUR';

        $rate = $this->sut->load($sourceCurrencyCode, $destinationCurrencyCode);
        $this->assertInstanceOf('\BartFeenstra\CurrencyExchange\ExchangeRateInterface',
          $rate);
        $this->assertSame(1, $rate->getRate());
    }

    /**
     * @covers ::load
     */
    public function testLoadWithoutExchangeRateProviders()
    {
        $sourceCurrencyCode = 'foo';
        $destinationCurrencyCode = 'bar';

        $this->sut->expects($this->atLeastOnce())
          ->method('getExchangeRateProviders')
          ->willReturn([]);

        $this->assertnull($this->sut->load($sourceCurrencyCode,
          $destinationCurrencyCode));
    }

    /**
     * @covers ::loadMultiple
     */
    public function testLoadMultiple()
    {
        $sourceCurrencyCodeA = 'EUR';
        $destinationCurrencyCodeA = 'NLG';
        $rateA = '2.20371';
        $sourceCurrencyCodeB = 'NLG';
        $destinationCurrencyCodeB = 'EUR';
        $rateB = '0.453780216';

        // Convert both currencies to each other and themselves.
        $requested_rates_provider = [
          $sourceCurrencyCodeA => [
            $destinationCurrencyCodeA,
            $sourceCurrencyCodeA
          ],
          $sourceCurrencyCodeB => [
            $destinationCurrencyCodeB,
            $sourceCurrencyCodeB
          ],
        ];
        // By the time plugin A will be called, the identical source and destination
        // currencies will have been processed.
        $requested_rates_plugin_a = [
          $sourceCurrencyCodeA => [$destinationCurrencyCodeA],
          $sourceCurrencyCodeB => [$destinationCurrencyCodeB],
        ];
        // By the time plugin B will be called, the 'A' source and destination
        // currencies will have been processed.
        $requested_rates_plugin_b = [
          $sourceCurrencyCodeB => [$destinationCurrencyCodeB],
        ];

        $exchangeRateProviderA = $this->getMock('\BartFeenstra\CurrencyExchange\ExchangeRateProviderInterface');
        $returnedRatesA = [
          $sourceCurrencyCodeA => [
            $destinationCurrencyCodeA => ExchangeRate::create(null,
              $sourceCurrencyCodeA, $destinationCurrencyCodeA, $rateA),
          ],
          $sourceCurrencyCodeB => [
            $destinationCurrencyCodeB => null,
          ],
        ];
        $exchangeRateProviderA->expects($this->once())
          ->method('loadMultiple')
          ->with($requested_rates_plugin_a)
          ->willReturn($returnedRatesA);

        $exchangeRateProviderB = $this->getMock('\BartFeenstra\CurrencyExchange\ExchangeRateProviderInterface');
        $returnedRatesB = [
          $sourceCurrencyCodeA => [
            $destinationCurrencyCodeA => null,
          ],
          $sourceCurrencyCodeB => [
            $destinationCurrencyCodeB => ExchangeRate::create(null,
              $sourceCurrencyCodeA, $destinationCurrencyCodeA, $rateB),
          ],
        ];
        $exchangeRateProviderB->expects($this->once())
          ->method('loadMultiple')
          ->with($requested_rates_plugin_b)
          ->willReturn($returnedRatesB);

        $exchangeRateProviderC = $this->getMock('\BartFeenstra\CurrencyExchange\ExchangeRateProviderInterface');
        $exchangeRateProviderC->expects($this->never())
          ->method('loadMultiple');

        $this->sut->expects($this->atLeastOnce())
          ->method('getExchangeRateProviders')
          ->willReturn([
            $exchangeRateProviderA,
            $exchangeRateProviderB,
            $exchangeRateProviderC
          ]);

        $returned_rates = $this->sut->loadMultiple($requested_rates_provider);
        $this->assertSame($returnedRatesA[$sourceCurrencyCodeA][$destinationCurrencyCodeA],
          $returned_rates[$sourceCurrencyCodeA][$destinationCurrencyCodeA]);
        $this->assertSame(1,
          $returned_rates[$sourceCurrencyCodeA][$sourceCurrencyCodeA]->getRate());
        $this->assertSame($returnedRatesB[$sourceCurrencyCodeB][$destinationCurrencyCodeB],
          $returned_rates[$sourceCurrencyCodeB][$destinationCurrencyCodeB]);
        $this->assertSame(1,
          $returned_rates[$sourceCurrencyCodeB][$sourceCurrencyCodeB]->getRate());
    }

}
