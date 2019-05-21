<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Command\Input;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

interface PropelCommandInputBuilderInterface
{
    /**
     * @param \Symfony\Component\Console\Input\InputDefinition $propelCommandDefinition
     * @param \Symfony\Component\Console\Input\InputDefinition $originalPropelCommandDefinition
     *
     * @return \Symfony\Component\Console\Input\InputInterface
     */
    public function buildInput(InputDefinition $propelCommandDefinition, InputDefinition $originalPropelCommandDefinition): InputInterface;
}
