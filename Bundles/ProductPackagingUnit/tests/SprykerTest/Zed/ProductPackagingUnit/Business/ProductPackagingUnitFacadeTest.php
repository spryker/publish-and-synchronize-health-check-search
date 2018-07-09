<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Generated\Shared\DataBuilder\ProductPackagingUnitTypeBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitAmountEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group Facade
 * @group ProductPackagingUnitFacadeTest
 * Add your own group annotations below this line
 */
class ProductPackagingUnitFacadeTest extends ProductPackagingUnitMocks
{
    protected const PACKAGING_TYPE_DEFAULT = 'item';
    protected const PACKAGING_TYPE = 'box';

    protected const ITEM_QUANTITY = 2;
    protected const PACKAGE_AMOUNT = 4;

    protected const GROUP_KEY = 'GROUP_KEY_DUMMY';
    protected const GROUP_KEY_FORMAT = '%s_amount_%s_sales_unit_id_%s';
    protected const AMOUNT_VALUE = 5;
    protected const SALES_UNIT_ID = 5;

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testInstallProductPackagingUnitTypesShouldPersistInfrastructuralPackagingUnitTypes(): void
    {
        // Assign
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())->build();
        $config = $this->createProductPackagingUnitConfigMock();
        $config->method('getInfrastructuralPackagingUnitTypes')
            ->willReturn([$productPackagingUnitTypeTransfer]);
        $factory = $this->createProductPackagingUnitBusinessFactoryMock($config);
        $facade = $this->createProductPackagingUnitFacadeMock($factory);

        // Action
        $facade->installProductPackagingUnitTypes();

        // Assert
        $productPackagingUnitTypeTransfer = $this->getFacade()->findProductPackagingUnitTypeByName($productPackagingUnitTypeTransfer);
        $this->assertNotNull($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType());
    }

    /**
     * @dataProvider getProductPackagingUnitTypeData
     *
     * @param string $name
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer ...$nameTranslations
     *
     * @return void
     */
    public function testCreateProductPackagingUnitTypeShouldPersistPackagingUnitType(string $name, ProductPackagingUnitTypeTranslationTransfer ... $nameTranslations): void
    {
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())
            ->build()
            ->setName($name);

        foreach ($nameTranslations as $nameTranslation) {
            $productPackagingUnitTypeTransfer->addProductPackagingUnitTypeTranslation($nameTranslation);
        }

        // Action
        $this->getFacade()->createProductPackagingUnitType($productPackagingUnitTypeTransfer);
        $productPackagingUnitTypeTransfer = $this->getFacade()->findProductPackagingUnitTypeByName($productPackagingUnitTypeTransfer);
        $this->assertNotNull($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType());
        // Assert translations persisted
        $this->assertCount($productPackagingUnitTypeTransfer->getTranslations()->count(), $nameTranslations);
    }

    /**
     * @dataProvider getProductPackagingUnitTypeData
     *
     * @expectedException \Spryker\Zed\ProductPackagingUnit\Business\Exception\ProductPackagingUnitTypeNotFoundException
     *
     * @param string $name
     *
     * @return void
     */
    public function testDeleteProductPackagingUnitTypeShouldDeletePackagingUnitType(string $name): void
    {
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())
            ->build()
            ->setName($name);

        $this->getFacade()->createProductPackagingUnitType($productPackagingUnitTypeTransfer);

        // Action
        $productPackagingUnitTypeDeleted = $this->getFacade()->deleteProductPackagingUnitType($productPackagingUnitTypeTransfer);
        $this->assertTrue($productPackagingUnitTypeDeleted);
        // Assert exception thrown
        $this->getFacade()->getProductPackagingUnitTypeById($productPackagingUnitTypeTransfer);
    }

    /**
     * @return array
     */
    public function getProductPackagingUnitTypeData(): array
    {
        return [
            [
                'packaging_unit_type.test1.name',
                (new ProductPackagingUnitTypeTranslationTransfer())
                    ->setLocaleCode('en_US')
                    ->setName('name1'),
                (new ProductPackagingUnitTypeTranslationTransfer())
                    ->setLocaleCode('de_DE')
                    ->setName('Name1'),
            ],
        ];
    }

    /**
     * @dataProvider getProductPackagingUnitTypeDataForNameChange
     *
     * @param string $name
     * @param string $newName
     *
     * @return void
     */
    public function testUpdateProductPackagingUnitTypeShouldUpdatePackagingUnitType(string $name, string $newName): void
    {
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())
            ->build()
            ->setName($name);

        $productPackagingUnitTypeTransfer = $this->getFacade()->createProductPackagingUnitType($productPackagingUnitTypeTransfer);

        // Action
        $productPackagingUnitTypeTransfer->setName($newName);
        $productPackagingUnitTypeTransfer = $this->getFacade()->updateProductPackagingUnitType($productPackagingUnitTypeTransfer);
        $this->assertEquals($productPackagingUnitTypeTransfer->getName(), $newName);
    }

    /**
     * @return array
     */
    public function getProductPackagingUnitTypeDataForNameChange(): array
    {
        return [
            [
                'packaging_unit_type.test1.name',
                'packaging_unit_type.test2.name',
            ],
        ];
    }

    /**
     * @return void
     */
    public function testExpandCartChangeTransferWithAmountLeadProduct(): void
    {
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $this->tester->haveProductPackagingLeadProduct([
            SpyProductPackagingLeadProductEntityTransfer::FK_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingLeadProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);
        $itemProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE_DEFAULT]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $itemProductPackagingUnitType->getIdProductPackagingUnitType(),
        ]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
        ], [
            SpyProductPackagingUnitAmountEntityTransfer::DEFAULT_AMOUNT => static::PACKAGE_AMOUNT,
        ]);

        $code = 'MYCODE' . random_int(1, 100);
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);

        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $itemProductConcreteTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );

        $productMeasurementSalesUnitEntityTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $boxProductConcreteTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
        );

        $productMeasurementSalesUnitTransfer = (new ProductMeasurementSalesUnitTransfer())
            ->fromArray($productMeasurementSalesUnitEntityTransfer->toArray(), true);

        $cartChange = (new CartChangeTransfer())
            ->addItem(
                (new ItemTransfer())
                    ->setId($boxProductConcreteTransfer->getIdProductConcrete())
                    ->setSku($boxProductConcreteTransfer->getSku())
                    ->setQuantity(static::ITEM_QUANTITY)
                    ->setAmount(static::PACKAGE_AMOUNT)
                    ->setAmountSalesUnit($productMeasurementSalesUnitTransfer)
            );

        $this->getFacade()->expandCartChangeWithAmountLeadProduct($cartChange);

        foreach ($cartChange->getItems() as $itemTransfer) {
            $this->assertNotNull($itemTransfer->getAmountLeadProduct());
            $this->assertNotNull($itemTransfer->getAmountLeadProduct()->getSku());
            $this->assertEquals($itemProductConcreteTransfer->getSku(), $itemTransfer->getAmountLeadProduct()->getSku());
        }
    }

    /**
     * @return void
     */
    public function testPreCheckCartAvailability(): void
    {
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote($this->createTestQuoteTransfer())
            ->addItem($this->createTestPackagingUnitItemTransfer());

        // Action
        $cartPreCheckResponseTransfer = $this->getFacade()->preCheckCartAvailability($cartChangeTransfer);
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckoutAvailabilityPreCheck(): void
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $quoteTransfer = $this->createTestQuoteTransfer()
            ->addItem($this->createTestPackagingUnitItemTransfer());

        // Action
        $this->getFacade()
            ->checkoutAvailabilityPreCheck($quoteTransfer, $checkoutResponseTransfer);

        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateProductPackagingUnitLeadProductAvailability(): void
    {
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $this->tester->haveProductPackagingLeadProduct([
            SpyProductPackagingLeadProductEntityTransfer::FK_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingLeadProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);
        $itemProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE_DEFAULT]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $itemProductPackagingUnitType->getIdProductPackagingUnitType(),
        ]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::HAS_LEAD_PRODUCT => true,
        ], [
            SpyProductPackagingUnitAmountEntityTransfer::DEFAULT_AMOUNT => static::PACKAGE_AMOUNT,
        ]);

        $this->getFacade()->updateProductPackagingUnitLeadProductAvailability($boxProductConcreteTransfer->getSku());
    }

    /**
     * @return void
     */
    public function testSetCustomAmountPrice(): void
    {
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $this->tester->haveProductPackagingLeadProduct([
            SpyProductPackagingLeadProductEntityTransfer::FK_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingLeadProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);
        $itemProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE_DEFAULT]);

        $itemProductPackagingUnit = $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $itemProductPackagingUnitType->getIdProductPackagingUnitType(),
        ]);

        $boxProductPackagingUnit = $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
        ], [
            SpyProductPackagingUnitAmountEntityTransfer::DEFAULT_AMOUNT => static::PACKAGE_AMOUNT,
        ]);

        $originUnitGrossPrice = 6000;

        $productPackagingUnitAmountTransfer = (new ProductPackagingUnitAmountTransfer())
            ->setIsVariable(true)
            ->setDefaultAmount(4);

        $productPackagingUnitTransfer = (new ProductPackagingUnitTransfer())
            ->setProductPackagingUnitAmount($productPackagingUnitAmountTransfer);

        $cartChange = (new CartChangeTransfer())
            ->addItem(
                (new ItemTransfer())
                    ->setSku($boxProductConcreteTransfer->getSku())
                    ->setQuantity(static::ITEM_QUANTITY)
                    ->setAmount(6)
                    ->setProductPackagingUnit($productPackagingUnitTransfer)
                    ->setOriginUnitGrossPrice($originUnitGrossPrice)
            );

        $this->getFacade()->setCustomAmountPrice($cartChange);

        foreach ($cartChange->getItems() as $itemTransfer) {
            $this->assertNotEquals($itemTransfer->getUnitGrossPrice(), $originUnitGrossPrice);
            $this->assertEquals($itemTransfer->getUnitGrossPrice(), 9000);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createTestQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->setStore(
                (new StoreTransfer())
                    ->setIdStore(1)
                    ->setName('DE')
            );
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createTestPackagingUnitItemTransfer(): ItemTransfer
    {
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $this->tester->haveProductPackagingLeadProduct([
            SpyProductPackagingLeadProductEntityTransfer::FK_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingLeadProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);
        $itemProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE_DEFAULT]);

        $itemProductPackagingUnit = $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $itemProductPackagingUnitType->getIdProductPackagingUnitType(),
        ]);

        $boxProductPackagingUnit = $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::HAS_LEAD_PRODUCT => true,
        ], [
            SpyProductPackagingUnitAmountEntityTransfer::DEFAULT_AMOUNT => static::PACKAGE_AMOUNT,
        ]);

        return (new ItemTransfer())
            ->setQuantity(static::ITEM_QUANTITY)
            ->setId($boxProductConcreteTransfer->getIdProductConcrete())
            ->setSku($boxProductConcreteTransfer->getSku())
            ->setAmount(static::PACKAGE_AMOUNT)
            ->setAmountLeadProduct(
                (new ProductPackagingLeadProductTransfer())
                    ->setSku($boxProductConcreteTransfer->getSku())
            );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testExpandCartChangeGroupKeyWithAmountSalesUnitNoSalesUnitIsDefined(): void
    {
        // Assign
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithountAmountSalesUnitForGroupKeyGeneration(static::GROUP_KEY, static::AMOUNT_VALUE);

        // Act
        $cartChangeTransfer = $this->getFacade()->expandCartChangeGroupKeyWithAmount($cartChangeTransfer);
        $itemTransfer = $cartChangeTransfer->getItems()[0];

        // Assert
        $this->assertSame(static::GROUP_KEY, $itemTransfer->getGroupKey());
    }

    /**
     * @return void
     */
    public function testExpandCartChangeGroupKeyWithAmountSalesUnitIfSalesUnitIsDefined(): void
    {
        // Assign
        $expectedGroupKey = sprintf(static::GROUP_KEY_FORMAT, static::GROUP_KEY, static::AMOUNT_VALUE, static::SALES_UNIT_ID);
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithAmountSalesUnitForGroupKeyGeneration(static::GROUP_KEY, static::AMOUNT_VALUE, static::SALES_UNIT_ID);

        // Act
        $cartChangeTransfer = $this->getFacade()->expandCartChangeGroupKeyWithAmount($cartChangeTransfer);
        $itemTransfer = $cartChangeTransfer->getItems()[0];

        // Assert
        $this->assertSame($expectedGroupKey, $itemTransfer->getGroupKey());
    }

    /**
     * @dataProvider calculateAmountNormalizedSalesUnitValues
     *
     * @param int $amount
     * @param float $conversion
     * @param int $precision
     * @param int $expectedResult
     *
     * @return void
     */
    public function testCalculateAmountNormalizedSalesUnitValueCalculatesCorrectValues(int $amount, float $conversion, int $precision, int $expectedResult): void
    {
        // Assign
        $quoteTransfer = $this->tester->createQuoteTransferForValueCalculation($amount, $conversion, $precision);

        // Act
        $updatedQuoteTransfer = $this->getFacade()->calculateAmountSalesUnitValueInQuote($quoteTransfer);

        // Assert
        $itemTransfer = $updatedQuoteTransfer->getItems()[0];
        $this->assertSame($expectedResult, $itemTransfer->getAmountSalesUnit()->getValue());
    }

    /**
     * @return array
     */
    public function calculateAmountNormalizedSalesUnitValues(): array
    {
        return [
            [7, 1.25, 1000, 5600],
            [7, 1.25, 100, 560],
            [7, 1.25, 10, 56],
            [7, 1.25, 1, 6],
            [10, 5, 1, 2],
            [13, 7, 1000, 1857],
            [13, 7, 100, 186],
            [13, 7, 10, 19],
            [13, 7, 1, 2],
        ];
    }

    /**
     * @dataProvider itemAdditionAmounts
     *
     * @param bool $expectedIsSuccess
     * @param int $defaultAmount
     * @param int $quoteAmount
     * @param int|null $minRestriction
     * @param int|null $maxRestriction
     * @param int|null $intervalRestriction
     *
     * @return void
     */
    public function testValidateItemAddAmountRestrictions(
        bool $expectedIsSuccess,
        int $defaultAmount,
        int $quoteAmount,
        ?int $minRestriction,
        ?int $maxRestriction,
        ?int $intervalRestriction
    ): void {
        // Assign
        $productAmountOverride = [
            SpyProductPackagingUnitAmountEntityTransfer::DEFAULT_AMOUNT => $defaultAmount,
            SpyProductPackagingUnitAmountEntityTransfer::AMOUNT_MIN => $minRestriction,
            SpyProductPackagingUnitAmountEntityTransfer::AMOUNT_MAX => $maxRestriction,
            SpyProductPackagingUnitAmountEntityTransfer::AMOUNT_INTERVAL => $intervalRestriction,
        ];

        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $this->tester->haveProductPackagingLeadProduct([
            SpyProductPackagingLeadProductEntityTransfer::FK_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingLeadProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([
            SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE,
        ]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
        ], $productAmountOverride);

        $code = 'MYCODE' . random_int(1, 100);
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);

        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $itemProductConcreteTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );

        $productMeasurementSalesUnitEntityTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $boxProductConcreteTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
        );

        $productMeasurementSalesUnitTransfer = (new ProductMeasurementSalesUnitTransfer())
            ->fromArray($productMeasurementSalesUnitEntityTransfer->toArray(), true);

        $cartChangeTransfer = $this->tester->createCartChangeTransferForProductPackagingUnitValidation($boxProductConcreteTransfer, $productMeasurementSalesUnitTransfer, $quoteAmount, static::ITEM_QUANTITY);

        // Act
        $cartPreCheckResponseTransfer = $this->getFacade()->validateItemAddAmountRestrictions($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedIsSuccess, $cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return array
     */
    public function itemAdditionAmounts(): array
    {
        return [
            [true, 1, 2, 1, null, 1], // general rule
            [true, 1, 7, 7, null, 1], // min equals new amount
            [true, 1, 5, 5, 5,    1], // max equals new amount
            [true, 1, 7, 0, null, 7], // interval matches new amount
            [false, 1, 5, 7, 7,    7], // min, max, interval matches new amount
            [false, 1, 5, 8, null, 1], // min above new amount
            [false, 1, 5, 1, 3,    1], // max below new amount
            [false, 1, 5, 1, null, 3], // interval does not match new amount
        ];
    }

    /**
     * @return void
     */
    public function testExpandOrderItemWithAmountSalesUnit(): void
    {
        // Assign
        $code = 'MYCODE' . random_int(1, 100);
        $productTransfer = $this->tester->haveProduct();
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);
        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $productTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );
        $productMeasurementBaseUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        $productMeasurementSalesUnitTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $productTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
        );

        $productMeasurementSalesUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);
        $productMeasurementSalesUnitTransfer->setProductMeasurementBaseUnit($productMeasurementBaseUnitTransfer);

        $amountSalesUnitTransfer = (new ProductMeasurementSalesUnitTransfer())->fromArray($productMeasurementSalesUnitTransfer->toArray(), true);
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setAmountSalesUnit($amountSalesUnitTransfer);

        //Act
        $salesOrderItemEntity = $this->getFacade()->expandSalesOrderItemWithAmountSalesUnit(
            $itemTransfer,
            new SpySalesOrderItemEntityTransfer()
        );

        //Assert
        $this->assertSame($productMeasurementUnitTransfer->getName(), $salesOrderItemEntity->getAmountMeasurementUnitName());
        $this->assertSame($productMeasurementUnitTransfer->getName(), $salesOrderItemEntity->getAmountBaseMeasurementUnitName());
        $this->assertSame($amountSalesUnitTransfer->getPrecision(), $salesOrderItemEntity->getAmountMeasurementUnitPrecision());
        $this->assertSame($amountSalesUnitTransfer->getConversion(), $salesOrderItemEntity->getAmountMeasurementUnitConversion());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemWithAmountAndAmountSku(): void
    {
        // Assign
        $itemTransfer = $this->createTestPackagingUnitItemTransfer();

        //Act
        $salesOrderItemEntity = $this->getFacade()->expandSalesOrderItemWithAmountAndAmountSku(
            $itemTransfer,
            new SpySalesOrderItemEntityTransfer()
        );

        //Assert
        $this->assertSame($itemTransfer->getAmount(), $salesOrderItemEntity->getAmount());
        $this->assertSame($itemTransfer->getAmountLeadProduct()->getSku(), $salesOrderItemEntity->getAmountSku());
    }

    /**
     * @return void
     */
    public function hydrateOrderWithAmountSalesUnit(): void
    {
        // Assign
        $salesOrderEntity = $this->tester->create();
        $orderTransfer = (new OrderTransfer())->fromArray($salesOrderEntity->toArray(), true);

        $salesOrderItemEntities = $salesOrderEntity->getItems();
        foreach ($salesOrderItemEntities as $salesOrderItem) {
            $itemTransfer = (new ItemTransfer())->fromArray($salesOrderItem->toArray(), true);
            $orderTransfer->addItem($itemTransfer);
        }

        //Act
        $orderTransfer = $this->getFacade()->hydrateOrderWithAmountSalesUnit($orderTransfer);

        //Assert
        $this->assertInstanceOf(OrderTransfer::class, $orderTransfer);
    }
}
