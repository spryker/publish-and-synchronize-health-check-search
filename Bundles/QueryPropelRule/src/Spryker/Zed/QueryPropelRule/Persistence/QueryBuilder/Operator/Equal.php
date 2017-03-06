<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator;

use Propel\Runtime\ActiveQuery\Criteria;

class Equal extends AbstractOperator
{

    const TYPE = 'equal';

    /**
     * @return string
     */
    public function getOperator()
    {
        return Criteria::EQUAL;
    }

}
