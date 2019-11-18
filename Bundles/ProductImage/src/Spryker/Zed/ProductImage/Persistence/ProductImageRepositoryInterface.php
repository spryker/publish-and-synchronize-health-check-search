<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence;

use Generated\Shared\Transfer\ProductImageCriteriaFilterTransfer;

interface ProductImageRepositoryInterface
{
    /**
     * @param int[] $productIds
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetTransfersByProductIdsAndIdLocale(array $productIds, int $idLocale): array;

    /**
     * @param int[] $productSetIds
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer[][]
     */
    public function getProductImagesByProductSetIds(array $productSetIds): array;

    /**
     * @param \Generated\Shared\Transfer\ProductImageCriteriaFilterTransfer $productImageCriteriaFilterTransfer
     *
     * @return int[]
     */
    public function getProductConcreteIds(ProductImageCriteriaFilterTransfer $productImageCriteriaFilterTransfer): array;
}
