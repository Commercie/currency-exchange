<?php

/**
 * @file
 * Contains \Commercie\Tests\CurrencyExchange\FixedExchangeRateProviderTrait.
 */

namespace Commercie\Tests\CurrencyExchange;
use Commercie\CurrencyExchange\FixedExchangeRateProviderTrait;

/**
 * @coversDefaultClass \Commercie\CurrencyExchange\FixedExchangeRateProviderTrait
 */
class FixedExchangeRateProviderTraitTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The class under test.
     *
     * @var \Commercie\CurrencyExchange\FixedExchangeRateProviderTrait
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = $this->getMockForTrait(FixedExchangeRateProviderTrait::class);
        $this->sut->expects($this->any())
            ->method('loadAll')
            ->willReturn($this->prepareExchangeRates());
    }

    /**
     * @covers ::load
     */
    public function testLoad()
    {
        $expectedRates = $this->prepareExchangeRates();

        $reverseRate = '0.511291';

        // Test rates that are stored in config.
        $this->assertSame($expectedRates['EUR']['NLG'],
          $this->sut->load('EUR', 'NLG')->getRate());
        $this->assertSame($expectedRates['EUR']['DEM'],
          $this->sut->load('EUR', 'DEM')->getRate());

        // Test a rate that is calculated on-the-fly.
        $this->assertSame($reverseRate,
          $this->sut->load('DEM', 'EUR')->getRate());

        // Test an unavailable exchange rate.
        $this->assertNull($this->sut->load('UAH', 'EUR'));
        $this->assertNull($this->sut->load('EUR', 'UAH'));
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
     * @covers ::loadMultiple
     */
    public function testLoadMultiple()
    {
        $expectedRates = $this->prepareExchangeRates();

        $returnedRates = $this->sut->loadMultiple([
            // Test a directly available exchange rate.
          'EUR' => ['NLG'],
            // Test a reverse exchange rate.
          'NLG' => ['EUR'],
            // Test an unavailable exchange rate.
          'ABC' => ['XXX'],
        ]);

        $this->assertSame($expectedRates['EUR']['NLG'],
          $returnedRates['EUR']['NLG']->getRate());
        $this->assertSame($expectedRates['NLG']['EUR'],
          $returnedRates['NLG']['EUR']->getRate());
        $this->assertNull($returnedRates['ABC']['XXX']);
    }
}
