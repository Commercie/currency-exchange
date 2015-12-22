<?php

/**
 * @file Contains \Commercie\Tests\CurrencyExchange\ProcessedExchangeRateProviderDecoratorTest.
 */

namespace Commercie\Tests\CurrencyExchange;

use Commercie\CurrencyExchange\ExchangeRate;
use Commercie\CurrencyExchange\ExchangeRateInterface;
use Commercie\CurrencyExchange\ExchangeRateProviderInterface;
use Commercie\CurrencyExchange\ProcessedExchangeRateProviderDecorator;

/**
 * @coversDefaultClass \Commercie\CurrencyExchange\ProcessedExchangeRateProviderDecorator
 */
class ProcessedExchangeRateProviderDecoratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The decorated exchange rate provider.
     *
     * @var \Commercie\CurrencyExchange\ExchangeRateProviderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $decoratedExchangeRateProvider;

    /**
     * The process callback.
     *
     * @var callable
     *   The callable takes \Commercie\CurrencyExchange\ExchangeRateInterface as
     *   its only argument, and MUST return
     *   \Commercie\CurrencyExchange\ExchangeRateInterface.
     */
    protected $processCallback;

    /**
     * The subject under test.
     *
     * @var \Commercie\CurrencyExchange\ProcessedExchangeRateProviderDecorator
     */
    protected $sut;

    public function setUp()
    {
        $this->decoratedExchangeRateProvider = $this->getMock(ExchangeRateProviderInterface::class);

        $this->processCallback = function (
          ExchangeRateInterface $exchangeRate = null
        ) {
            if ($exchangeRate) {
                // We set a dynamic property on the exchange rate to prove it
                // was processed.
                $exchangeRate->processed = true;
            }

            return $exchangeRate;
        };

        $this->sut = new ProcessedExchangeRateProviderDecorator($this->decoratedExchangeRateProvider,
          $this->processCallback);
    }

    /**
     * @covers ::load
     */
    public function testLoad()
    {
        $sourceCurrencyCode = 'NLG';
        $destinationCurrencyCOde = 'EUR';
        $rate = '2.20371';
        $decoratedExchangeRate = new ExchangeRate($sourceCurrencyCode,
          $destinationCurrencyCOde, $rate);

        $this->decoratedExchangeRateProvider->expects($this->once())
          ->method('load')
          ->with($sourceCurrencyCode, $destinationCurrencyCOde)
          ->willReturn($decoratedExchangeRate);

        $retrievedExchangeRate = $this->sut->load($sourceCurrencyCode,
          $destinationCurrencyCOde);
        $this->assertTrue($retrievedExchangeRate->processed);
    }

    /**
     * @covers ::load
     */
    public function testLoadWithoutExchangeRate()
    {
        $sourceCurrencyCode = 'nlg';
        $destinationCurrencyCOde = 'EUR';
        $decoratedExchangeRate = null;

        $this->decoratedExchangeRateProvider->expects($this->once())
          ->method('load')
          ->with($sourceCurrencyCode, $destinationCurrencyCOde)
          ->willReturn($decoratedExchangeRate);

        $retrievedExchangeRate = $this->sut->load($sourceCurrencyCode,
          $destinationCurrencyCOde);
        $this->assertNull($retrievedExchangeRate);
    }

    /**
     * @covers ::loadMultiple
     */
    public function testLoadMultiple()
    {
        $sourceCurrencyCode = 'nlg';
        $destinationCurrencyCode = 'EUR';
        $nonExistentCurrencyCode = 'foo';
        $sourceToDestinationRate = '2.20371';
        $destinationToSourceRate = '0,45378021609014';
        $sourceToDestinationDecoratedExchangeRate = new ExchangeRate($sourceCurrencyCode,
          $destinationCurrencyCode, $sourceToDestinationRate);
        $destinationToSourceDecoratedExchangeRate = new ExchangeRate($destinationCurrencyCode,
          $sourceCurrencyCode, $destinationToSourceRate);
        $sourceIdenticalDecoratedExchangeRate = new ExchangeRate($sourceCurrencyCode,
          $sourceCurrencyCode, 1);
        $destinationIdenticalDecoratedExchangeRate = new ExchangeRate($destinationCurrencyCode,
          $destinationCurrencyCode, 1);

        $currencyCodes = [
          $sourceCurrencyCode => [
            $sourceCurrencyCode,
            $destinationCurrencyCode,
            $nonExistentCurrencyCode,
          ],
          $destinationCurrencyCode => [
            $sourceCurrencyCode,
            $destinationCurrencyCode,
            $nonExistentCurrencyCode,
          ],
        ];

        $decoratedExchangeRates = [
          $sourceCurrencyCode => [
            $sourceCurrencyCode => $sourceIdenticalDecoratedExchangeRate,
            $destinationCurrencyCode => $sourceToDestinationDecoratedExchangeRate,
            $nonExistentCurrencyCode => null,
          ],
          $destinationCurrencyCode => [
            $sourceCurrencyCode => $destinationToSourceDecoratedExchangeRate,
            $destinationCurrencyCode => $destinationIdenticalDecoratedExchangeRate,
            $nonExistentCurrencyCode => null,
          ],
          $nonExistentCurrencyCode => [
            $sourceCurrencyCode => null,
            $destinationCurrencyCode => null,
            $nonExistentCurrencyCode => null,
          ],
        ];
        $this->decoratedExchangeRateProvider->expects($this->once())
          ->method('loadMultiple')
          ->with($currencyCodes)
          ->willReturn($decoratedExchangeRates);

        $retrievedExchangeRates = $this->sut->loadMultiple($currencyCodes);
        // Ensure that these are the exchange rates that were returned by the
        // decorated provider.
        $this->assertSame($decoratedExchangeRates, $retrievedExchangeRates);
        // Ensure the process callback was executed for all exchange rates.
        $this->assertTrue($retrievedExchangeRates[$sourceCurrencyCode][$sourceCurrencyCode]->processed);
        $this->assertTrue($retrievedExchangeRates[$sourceCurrencyCode][$destinationCurrencyCode]->processed);
        $this->assertTrue($retrievedExchangeRates[$destinationCurrencyCode][$sourceCurrencyCode]->processed);
        $this->assertTrue($retrievedExchangeRates[$destinationCurrencyCode][$destinationCurrencyCode]->processed);
    }

}
