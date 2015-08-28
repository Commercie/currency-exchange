<?php

/**
 * @file Contains \Commercie\Tests\CurrencyExchange\AbstractStackedExchangeRateProviderTest.
 */

namespace Commercie\Tests\CurrencyExchange;

use Commercie\CurrencyExchange\AbstractStackedExchangeRateProvider;
use Commercie\CurrencyExchange\ExchangeRate;
use Commercie\CurrencyExchange\ExchangeRateInterface;
use Commercie\CurrencyExchange\ExchangeRateProviderInterface;

/**
 * @coversDefaultClass \Commercie\CurrencyExchange\AbstractStackedExchangeRateProvider
 */
class AbstractStackedExchangeRateProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The class under test.
     *
     * @var \Commercie\CurrencyExchange\AbstractStackedExchangeRateProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = $this->getMockForAbstractClass(AbstractStackedExchangeRateProvider::class);
    }

    /**
     * @covers ::load
     */
    public function testLoad()
    {
        $sourceCurrencyCode = 'EUR';
        $destinationCurrencyCode = 'NLG';
        $rate = new ExchangeRate($sourceCurrencyCode,
          $destinationCurrencyCode, '2.20371');

        $exchangeRateProviderA = $this->getMock(ExchangeRateProviderInterface::class);
        $exchangeRateProviderA->expects($this->once())
          ->method('load')
          ->with($sourceCurrencyCode, $destinationCurrencyCode)
          ->willReturn(null);

        $exchangeRateProviderB = $this->getMock(ExchangeRateProviderInterface::class);
        $exchangeRateProviderB->expects($this->once())
          ->method('load')
          ->with($sourceCurrencyCode, $destinationCurrencyCode)
          ->willReturn($rate);

        $exchangeRateProviderC = $this->getMock(ExchangeRateProviderInterface::class);
        $exchangeRateProviderC->expects($this->never())
          ->method('load');

        $this->sut->expects($this->atLeastOnce())
          ->method('getExchangeRateProviders')
          ->willReturn([
            $exchangeRateProviderA,
            $exchangeRateProviderB,
            $exchangeRateProviderC,
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
        $this->assertInstanceOf(ExchangeRateInterface::class, $rate);
        $this->assertSame('1', $rate->getRate());
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

        $exchangeRateProviderA = $this->getMock(ExchangeRateProviderInterface::class);
        $returnedRatesA = [
          $sourceCurrencyCodeA => [
            $destinationCurrencyCodeA => new ExchangeRate($sourceCurrencyCodeA,
              $destinationCurrencyCodeA, $rateA),
          ],
          $sourceCurrencyCodeB => [
            $destinationCurrencyCodeB => null,
          ],
        ];
        $exchangeRateProviderA->expects($this->once())
          ->method('loadMultiple')
          ->with($requested_rates_plugin_a)
          ->willReturn($returnedRatesA);

        $exchangeRateProviderB = $this->getMock(ExchangeRateProviderInterface::class);
        $returnedRatesB = [
          $sourceCurrencyCodeA => [
            $destinationCurrencyCodeA => null,
          ],
          $sourceCurrencyCodeB => [
            $destinationCurrencyCodeB => new ExchangeRate($sourceCurrencyCodeA,
              $destinationCurrencyCodeA, $rateB),
          ],
        ];
        $exchangeRateProviderB->expects($this->once())
          ->method('loadMultiple')
          ->with($requested_rates_plugin_b)
          ->willReturn($returnedRatesB);

        $exchangeRateProviderC = $this->getMock(ExchangeRateProviderInterface::class);
        $exchangeRateProviderC->expects($this->never())
          ->method('loadMultiple');

        $this->sut->expects($this->atLeastOnce())
          ->method('getExchangeRateProviders')
          ->willReturn([
            $exchangeRateProviderA,
            $exchangeRateProviderB,
            $exchangeRateProviderC,
          ]);

        $exchangeRates = $this->sut->loadMultiple($requested_rates_provider);
        $this->assertSame($returnedRatesA[$sourceCurrencyCodeA][$destinationCurrencyCodeA],
          $exchangeRates[$sourceCurrencyCodeA][$destinationCurrencyCodeA]);
        $this->assertSame('1',
          $exchangeRates[$sourceCurrencyCodeA][$sourceCurrencyCodeA]->getRate());
        $this->assertSame($returnedRatesB[$sourceCurrencyCodeB][$destinationCurrencyCodeB],
          $exchangeRates[$sourceCurrencyCodeB][$destinationCurrencyCodeB]);
        $this->assertSame('1',
          $exchangeRates[$sourceCurrencyCodeB][$sourceCurrencyCodeB]->getRate());
    }

}
