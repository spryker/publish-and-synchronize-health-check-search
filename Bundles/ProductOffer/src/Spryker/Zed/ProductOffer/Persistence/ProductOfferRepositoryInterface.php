<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Persistence;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;

interface ProductOfferRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer|null $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function find(?ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ProductOfferCollectionTransfer;
}
