<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Dependency;

use Spryker\Zed\Touch\Business\TouchFacade;

class UrlToTouchBridge implements UrlToTouchInterface
{

    /**
     * @var TouchFacade
     */
    protected $touchFacade;

    /**
     * ProductCategoryToTouchBridge constructor.
     *
     * @param TouchFacade $touchFacade
     */
    public function __construct($touchFacade)
    {
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchActive($itemType, $itemId)
    {
        return $this->touchFacade->touchActive($itemType, $itemId);
    }

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchDeleted($itemType, $itemId)
    {
        return $this->touchFacade->touchDeleted($itemType, $itemId);
    }

}
