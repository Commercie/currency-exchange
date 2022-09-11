<?php

/**
 * @file
 * Contains \Commercie\Tests\CurrencyExchange\HistoricalExchangeRateProviderTest.
 */

namespace Commercie\Tests\CurrencyExchange;

use Commercie\CurrencyExchange\HistoricalExchangeRateProvider;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Commercie\CurrencyExchange\HistoricalExchangeRateProvider
 */
class HistoricalExchangeRateProviderTest extends TestCase
{

    /**
     * The class under test.
     *
     * @var \Commercie\CurrencyExchange\HistoricalExchangeRateProvider
     */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new HistoricalExchangeRateProvider();
    }

    /**
     * Returns predefined exchange rates.
     *
     * @return array[]
     */
    protected function prepareExchangeRates()
    {
        $rates = [
          'EUR' => [
            'DEM' => '1.95583',
            'NLG' => '2.20371',
          ],
          'NLG' => [
            'EUR' => '0.453780',
          ],
        ];

        return $rates;
    }

    /**
     * @covers ::loadAll
     * @covers ::loadMultiple
     */
    public function testLoadAll()
    {
        $expectedRates = $this->prepareExchangeRates();

        $returnedRates = $this->sut->loadMultiple(array(
            // Test a directly available exchange rate.
          'EUR' => ['NLG'],
            // Test a reverse exchange rate.
          'NLG' => ['EUR'],
            // Test an unavailable exchange rate.
          'ABC' => ['XXX'],
        ));

        $this->assertSame($expectedRates['EUR']['NLG'],
          $returnedRates['EUR']['NLG']->getRate());
        $this->assertSame($expectedRates['NLG']['EUR'],
          $returnedRates['NLG']['EUR']->getRate());
        $this->assertNull($returnedRates['ABC']['XXX']);
    }
}
