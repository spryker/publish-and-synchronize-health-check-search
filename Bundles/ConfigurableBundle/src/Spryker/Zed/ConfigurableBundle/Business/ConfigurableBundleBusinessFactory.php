<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundle\ConfigurableBundleConfig getConfig()
 */
class ConfigurableBundleBusinessFactory extends AbstractBusinessFactory
{
}