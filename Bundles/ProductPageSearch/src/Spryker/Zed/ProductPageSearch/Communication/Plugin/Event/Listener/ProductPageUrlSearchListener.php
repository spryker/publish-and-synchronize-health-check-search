<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductPageUrlSearchListener extends AbstractProductPageSearchListener implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $productAbstractIds = $this->getValidProductIds($eventTransfers);
        if (empty($productAbstractIds)) {
            return;
        }

        $this->publish($productAbstractIds);
    }

    /**
     * @param array $eventTransfers
     *
     * @return array
     */
    protected function getValidProductIds(array $eventTransfers)
    {
        $validEventTransfers = [];
        foreach ($eventTransfers as $eventTransfer) {
            if (
                in_array(SpyUrlTableMap::COL_URL, $eventTransfer->getModifiedColumns()) ||
                in_array(SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT, $eventTransfer->getModifiedColumns())
            ) {
                $validEventTransfers[] = $eventTransfer;
            }
        }

        return $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys(
            $validEventTransfers,
            SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT
        );
    }
}
