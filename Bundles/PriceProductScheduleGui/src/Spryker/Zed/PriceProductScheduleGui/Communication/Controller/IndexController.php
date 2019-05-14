<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    protected const REQUEST_KEY_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    protected const REQUEST_KEY_ID_PRODUCT_CONCRETE = 'id-product-concrete';
    protected const REQUEST_KEY_ID_PRICE_TYPE = 'id-price-type';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $idProductAbstract = $request->query->get(static::REQUEST_KEY_ID_PRODUCT_ABSTRACT);
        $idPriceType = $request->query->get(static::REQUEST_KEY_ID_PRICE_TYPE);
        $priceProductScheduleTable = $this->getFactory()
            ->createPriceProductScheduleAbstractTable($idProductAbstract, $idPriceType);

        return $this->jsonResponse(
            $priceProductScheduleTable->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function concreteTableAction(Request $request): JsonResponse
    {
        $idProductConcrete = $request->query->get(static::REQUEST_KEY_ID_PRODUCT_CONCRETE);
        $idPriceType = $request->query->get(static::REQUEST_KEY_ID_PRICE_TYPE);
        $priceProductScheduleTable = $this->getFactory()
            ->createPriceProductScheduleConcreteTable($idProductConcrete, $idPriceType);

        return $this->jsonResponse(
            $priceProductScheduleTable->fetchData()
        );
    }
}
