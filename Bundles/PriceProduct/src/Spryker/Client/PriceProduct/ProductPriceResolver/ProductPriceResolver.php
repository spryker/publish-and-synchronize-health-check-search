<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct\ProductPriceResolver;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToQuoteClientInterface;
use Spryker\Client\PriceProduct\PriceProductConfig;
use Spryker\Service\PriceProduct\PriceProductServiceInterface;
use Spryker\Shared\PriceProduct\PriceProductConfig as SharedPriceProductConfig;

class ProductPriceResolver implements ProductPriceResolverInterface
{
    protected const PRICE_KEY_SEPARATOR = '-';

    /**
     * @var \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @var \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @var \Spryker\Client\PriceProduct\PriceProductConfig
     */
    protected $priceProductConfig;

    /**
     * @var \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer|null
     */
    protected static $currencyTransfer;

    /**
     * @var string|null
     */
    protected static $currentPriceMode;

    /**
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface $priceClient
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface $currencyClient
     * @param \Spryker\Client\PriceProduct\PriceProductConfig $priceProductConfig
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToQuoteClientInterface $quoteClient
     * @param \Spryker\Service\PriceProduct\PriceProductServiceInterface $priceProductService
     */
    public function __construct(
        PriceProductToPriceClientInterface $priceClient,
        PriceProductToCurrencyClientInterface $currencyClient,
        PriceProductConfig $priceProductConfig,
        PriceProductToQuoteClientInterface $quoteClient,
        PriceProductServiceInterface $priceProductService
    ) {
        $this->priceProductConfig = $priceProductConfig;
        $this->priceClient = $priceClient;
        $this->currencyClient = $currencyClient;
        $this->quoteClient = $quoteClient;
        $this->priceProductService = $priceProductService;
    }

    /**
     * {@inheritDoc}
     *
     * @phpstan-param array<mixed> $priceMap
     *
     * @param array $priceMap
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolve(array $priceMap): CurrentProductPriceTransfer
    {
        $priceProductTransfers = $this->convertPriceMapToPriceProductTransfers($priceMap);

        return $this->resolveTransfer($priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveTransfer(array $priceProductTransfers): CurrentProductPriceTransfer
    {
        $currentProductPriceTransfer = new CurrentProductPriceTransfer();
        if (!$priceProductTransfers) {
            return $currentProductPriceTransfer;
        }

        $priceProductFilter = $this->buildPriceProductFilterWithCurrentValues();

        return $this->prepareCurrentProductPriceTransfer(
            $priceProductTransfers,
            $currentProductPriceTransfer,
            $priceProductFilter
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductPriceTransferByPriceProductFilter(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): CurrentProductPriceTransfer {
        $currentProductPriceTransfer = new CurrentProductPriceTransfer();
        if (!$priceProductTransfers) {
            return $currentProductPriceTransfer;
        }

        $priceProductFilter = $this->buildPriceProductFilterWithCurrentValues($priceProductFilterTransfer);

        return $this->prepareCurrentProductPriceTransfer(
            $priceProductTransfers,
            $currentProductPriceTransfer,
            $priceProductFilter
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilter
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    protected function prepareCurrentProductPriceTransfer(
        array $priceProductTransfers,
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        PriceProductFilterTransfer $priceProductFilter
    ): CurrentProductPriceTransfer {
        $priceProductTransfer = $this->priceProductService->resolveProductPriceByPriceProductFilter(
            $priceProductTransfers,
            $priceProductFilter
        );

        if (!$priceProductTransfer) {
            return $currentProductPriceTransfer;
        }

        /** @var string $priceMode */
        $priceMode = $priceProductFilter->requirePriceMode()->getPriceMode();
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->requireMoneyValue()->getMoneyValue();

        $price = $this->getPriceValueByPriceMode($moneyValueTransfer, $priceMode);

        if ($price === null) {
            return $currentProductPriceTransfer;
        }

        $priceProductFilterAllPriceTypes = clone $priceProductFilter;
        $priceProductFilterAllPriceTypes->setPriceTypeName(null);
        $priceProductFilterAllPriceTypes->setPriceDimension($priceProductTransfer->getPriceDimension());

        $priceProductAllPriceTypesTransfers = $this->priceProductService->resolveProductPricesByPriceProductFilter(
            $priceProductTransfers,
            $priceProductFilterAllPriceTypes
        );

        $prices = [];
        foreach ($priceProductAllPriceTypesTransfers as $priceProductOnePriceTypeTransfer) {
            /** @var \Generated\Shared\Transfer\MoneyValueTransfer $onePriceTypeMoneyValueTransfer */
            $onePriceTypeMoneyValueTransfer = $priceProductOnePriceTypeTransfer->requireMoneyValue()->getMoneyValue();
            $prices[$priceProductOnePriceTypeTransfer->getPriceTypeName()] = $this->getPriceValueByPriceMode($onePriceTypeMoneyValueTransfer, $priceMode);
        }

        return $currentProductPriceTransfer
            ->setPrice($price)
            ->setPrices($prices)
            ->setCurrency($priceProductFilter->getCurrency())
            ->setQuantity($priceProductFilter->getQuantity())
            ->setPriceMode($priceMode)
            ->setSumPrice($price * $priceProductFilter->getQuantity())
            ->setPriceData($moneyValueTransfer->getPriceData())
            ->setPriceDimension($priceProductTransfer->getPriceDimension());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer|null $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function buildPriceProductFilterWithCurrentValues(
        ?PriceProductFilterTransfer $priceProductFilterTransfer = null
    ): PriceProductFilterTransfer {
        $currencyTransfer = $this->getCurrencyTransfer();
        $priceMode = $this->getCurrentPriceMode();
        $priceTypeName = $this->priceProductConfig->getPriceTypeDefaultName();

        $quoteTransfer = $this->quoteClient->getQuote();

        $builtPriceProductFilterTransfer = new PriceProductFilterTransfer();

        if ($priceProductFilterTransfer) {
            $builtPriceProductFilterTransfer->fromArray(
                $priceProductFilterTransfer->toArray(),
                true
            );
        }

        $builtPriceProductFilterTransfer
            ->setPriceMode($priceMode)
            ->setCurrency($currencyTransfer)
            ->setCurrencyIsoCode($currencyTransfer->getCode())
            ->setPriceTypeName($priceTypeName)
            ->setQuote($quoteTransfer);

        return $builtPriceProductFilterTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer(): CurrencyTransfer
    {
        if (static::$currencyTransfer === null) {
            static::$currencyTransfer = $this->currencyClient->getCurrent();
        }

        return static::$currencyTransfer;
    }

    /**
     * @return string
     */
    protected function getCurrentPriceMode(): string
    {
        if (static::$currentPriceMode === null) {
            static::$currentPriceMode = $this->priceClient->getCurrentPriceMode();
        }

        return static::$currentPriceMode;
    }

    /**
     * @phpstan-param array<mixed> $priceMap
     *
     * @param array $priceMap
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function convertPriceMapToPriceProductTransfers(array $priceMap): array
    {
        /** @var \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers */
        $priceProductTransfers = [];

        foreach ($priceMap as $currencyCode => $prices) {
            foreach (SharedPriceProductConfig::PRICE_MODES as $priceMode) {
                if (!isset($prices[$priceMode])) {
                    continue;
                }

                foreach ($prices[$priceMode] as $priceType => $priceAmount) {
                    $index = implode(static::PRICE_KEY_SEPARATOR, [
                        $currencyCode,
                        $priceType,
                    ]);

                    if (!isset($priceProductTransfers[$index])) {
                        $priceProductTransfers[$index] = (new PriceProductTransfer())
                            ->setPriceDimension(
                                (new PriceProductDimensionTransfer())
                                    ->setType($this->priceProductConfig->getPriceDimensionDefault())
                            )
                            ->setMoneyValue(
                                (new MoneyValueTransfer())
                                    ->setCurrency((new CurrencyTransfer())->setCode($currencyCode))
                            )
                            ->setPriceTypeName($priceType);
                    }
                    /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
                    $moneyValueTransfer = $priceProductTransfers[$index]->requireMoneyValue()->getMoneyValue();

                    if ($priceMode === $this->priceProductConfig->getPriceModeIdentifierForNetType()) {
                        $moneyValueTransfer->setNetAmount($priceAmount);
                        $priceProductTransfers[$index]->setMoneyValue($moneyValueTransfer);

                        continue;
                    }

                    $moneyValueTransfer->setGrossAmount($priceAmount);
                    $priceProductTransfers[$index]->setMoneyValue($moneyValueTransfer);
                }
            }
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param string $priceMode
     *
     * @return int|null
     */
    protected function getPriceValueByPriceMode(MoneyValueTransfer $moneyValueTransfer, string $priceMode): ?int
    {
        if ($priceMode === $this->priceProductConfig->getPriceModeIdentifierForNetType()) {
            return $moneyValueTransfer->getNetAmount();
        }

        return $moneyValueTransfer->getGrossAmount();
    }
}
