<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Communication\Plugin\Api;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductApiTransfer;
use Spryker\Shared\ProductApi\ProductApiConstants;
use Spryker\Zed\Api\Dependency\Plugin\ApiPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductApi\Business\ProductApiFacade getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class ProductApiPlugin extends AbstractPlugin implements ApiPluginInterface
{

    /**
     * @api
     *
     * @return string
     */
    public function getResourceType()
    {
        return ProductApiConstants::RESOURCE_TYPE;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function find(ApiRequestTransfer $apiRequestTransfer)
    {
        return $this->getFacade()->findProducts($apiRequestTransfer);
    }

    /**
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function get($idCustomer, ApiFilterTransfer $apiFilterTransfer)
    {
        return $this->getFacade()->getProduct($idCustomer, $apiFilterTransfer);
    }

    /**
     * @param array $customer
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function add(ApiDataTransfer $apiDataTransfer)
    {
        $customerTransfer = new ProductApiTransfer();
        $customerTransfer->fromArray($apiDataTransfer->getData(), true);

        return $this->getFacade()->addCustomer($customerTransfer);
    }

    /**
     * @param int $idCustomer
     * @param array $customer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function update($idCustomer, ApiDataTransfer $apiDataTransfer)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($apiDataTransfer->getData(), true);
        $customerTransfer->setIdCustomer($idCustomer);

        return $this->getFacade()->updateCustomer($customerTransfer);
    }

    /**
     * @param int $idCustomer
     *
     * @return bool
     */
    public function delete($idCustomer)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        return $this->getFacade()->deleteCustomer($customerTransfer);
    }

}
