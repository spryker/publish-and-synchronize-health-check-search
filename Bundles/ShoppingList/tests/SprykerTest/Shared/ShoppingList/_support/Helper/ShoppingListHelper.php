<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ShoppingList\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ShoppingListBuilder;
use Generated\Shared\DataBuilder\ShoppingListItemBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Orm\Zed\Permission\Persistence\SpyPermissionQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroup;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroupToPermission;
use Spryker\Shared\ShoppingList\ShoppingListConfig;
use Spryker\Zed\ShoppingList\Communication\Plugin\ReadShoppingListPermissionPlugin;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ShoppingListHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function haveShoppingList(array $seed = []): ShoppingListTransfer
    {
        $shoppingListTransfer = $this->buildShoppingList($seed);

        return $this->getLocator()->shoppingList()->facade()->createShoppingList($shoppingListTransfer)->getShoppingList();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function buildShoppingList(array $seed = []): ShoppingListTransfer
    {
        /** @var \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer */
        $shoppingListTransfer = (new ShoppingListBuilder($seed))->build();

        return $shoppingListTransfer;
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function buildShoppingListItem(array $seed = []): ShoppingListItemTransfer
    {
        /** @var \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer */
        $shoppingListItemTransfer = (new ShoppingListItemBuilder($seed))->build();

        return $shoppingListItemTransfer;
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function haveShoppingListItem(array $seed = []): ShoppingListItemTransfer
    {
        return $this->getLocator()->shoppingList()->facade()->addShoppingListItem(
            $this->buildShoppingListItem($seed)
        )->getShoppingListItem();
    }

    /**
     * @param string $name
     * @param array $permissionKeys
     *
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    public function haveShoppingListPermissionGroup($name, array $permissionKeys): ShoppingListPermissionGroupTransfer
    {
        $shoppingListPermissionGroupEntity = new SpyShoppingListPermissionGroup();
        $shoppingListPermissionGroupEntity->setName($name);

        foreach ($permissionKeys as $permissionKey) {
            $permissionEntity = SpyPermissionQuery::create()
                ->filterByKey($permissionKey)
                ->findOneOrCreate();

            $quotePermissionGroupToPermissionEntity = new SpyShoppingListPermissionGroupToPermission();
            $quotePermissionGroupToPermissionEntity
                ->setSpyPermission($permissionEntity);

            $shoppingListPermissionGroupEntity->addSpyShoppingListPermissionGroupToPermission($quotePermissionGroupToPermissionEntity);
        }

        $shoppingListPermissionGroupEntity->save();

        $shoppingListPermissionGroupTransfer = new ShoppingListPermissionGroupTransfer();
        $shoppingListPermissionGroupTransfer->fromArray($shoppingListPermissionGroupEntity->toArray(), true);

        return $shoppingListPermissionGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $guestCustomer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function haveReadOnlyAccessToSharedShoppingList(
        CustomerTransfer $guestCustomer,
        ShoppingListTransfer $shoppingListTransfer
    ): ShoppingListTransfer {

        $permissionGroupTransfer = $this->haveShoppingListPermissionGroup(
            ShoppingListConfig::PERMISSION_GROUP_READ_ONLY,
            [
                ReadShoppingListPermissionPlugin::KEY,
            ]
        );

        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
            ->setIdCompanyUser($guestCustomer->getCompanyUserTransfer()->getIdCompanyUser())
            ->setShoppingListOwnerId($shoppingListTransfer->getIdCompanyUser())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdShoppingListPermissionGroup($permissionGroupTransfer->getIdShoppingListPermissionGroup());

        $this->getLocator()->shoppingList()->facade()->shareShoppingListWithCompanyUser($shoppingListShareRequestTransfer);

        return $shoppingListTransfer;
    }
}
