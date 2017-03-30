<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Persistence;

use Generated\Shared\Transfer\ApiPaginationTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Api\Persistence\ApiPersistenceFactory getFactory()
 */
class ApiQueryContainer extends AbstractQueryContainer implements ApiQueryContainerInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $criteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function createQuery(ModelCriteria $query, PropelQueryBuilderCriteriaTransfer $criteriaTransfer)
    {
        return $this->getFactory()
            ->getPropelQueryBuilder()
            ->createQuery($query, $criteriaTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $json
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    public function createPropelQueryBuilderCriteriaFromJson($json)
    {
        return $this->getFactory()
            ->getPropelQueryBuilder()
            ->createPropelQueryBuilderCriteriaFromJson($json);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $apiPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapPagination(ModelCriteria $query, ApiPaginationTransfer $apiPaginationTransfer)
    {
        return $this->getFactory()
            ->createPaginationQueryMapper()
            ->mapPagination($query, $apiPaginationTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $tableName
     * @param array $tableFields
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param array $allowedFields
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapFields($tableName, array $tableFields, ModelCriteria $query, array $allowedFields)
    {
        return $this->getFactory()
            ->createFieldMapper()
            ->mapFields($tableName, $tableFields, $query, $allowedFields);
    }

    /**
     * @api
     *
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function createApiCollection(array $data)
    {
        return $this->getFactory()
            ->createApiCollectionMapper()
            ->toCollection($data);
    }

}
