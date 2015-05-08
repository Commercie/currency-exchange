<?php

/**
 * @file
 * Contains \BartFeenstra\Tests\CurrencyExchange\HistoricalExchangeRateProviderTest.
 */

namespace BartFeenstra\Tests\CurrencyExchange;

use BartFeenstra\CurrencyExchange\HistoricalExchangeRateProvider;

/**
 * @coversDefaultClass \BartFeenstra\CurrencyExchange\HistoricalExchangeRateProvider
 */
class HistoricalExchangeRateProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The class under test.
     *
     * @var \BartFeenstra\CurrencyExchange\HistoricalExchangeRateProvider
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new HistoricalExchangeRateProvider();
    }

    /**
     * @covers ::load
     */
    public function testLoad()
    {
        $expected_rates = $this->prepareExchangeRates();

        $reverse_rate = '0.511291';

        // Test rates that are stored in config.
        $this->assertSame($expected_rates['EUR']['NLG'],
          $this->sut->load('EUR', 'NLG')->getRate());
        $this->assertSame($expected_rates['EUR']['DEM'],
          $this->sut->load('EUR', 'DEM')->getRate());

        // Test a rate that is calculated on-the-fly.
        $this->assertSame($reverse_rate,
          $this->sut->load('DEM', 'EUR')->getRate());

        // Test an unavailable exchange rate.
        $this->assertNull($this->sut->load('UAH', 'EUR'));
        $this->assertNull($this->sut->load('EUR', 'UAH'));
    }

    /**
     * Stores random exchange rates in the mocked config and returns them.
     *
     * @return array
     */
    protected function prepareExchangeRates()
    {
        $rates = array(
          'EUR' => array(
            'DEM' => '1.95583',
            'NLG' => '2.20371',
          ),
          'NLG' => array(
            'EUR' => '0.453780',
          ),
        );

        return $rates;
    }

    /**
     * @covers ::loadMultiple
     */
    public function testLoadMultiple()
    {
        $expected_rates = $this->prepareExchangeRates();

        $returned_rates = $this->sut->loadMultiple(array(
            // Test a rate that is stored in config.
          'EUR' => array('NLG'),
            // Test a reverse exchange rate.
          'NLG' => array('EUR'),
            // Test an unavailable exchange rate.
          'ABC' => array('XXX'),
        ));

        $this->assertSame($expected_rates['EUR']['NLG'],
          $returned_rates['EUR']['NLG']->getRate());
        $this->assertSame($expected_rates['NLG']['EUR'],
          $returned_rates['NLG']['EUR']->getRate());
        $this->assertNull($returned_rates['ABC']['XXX']);
    }
}
