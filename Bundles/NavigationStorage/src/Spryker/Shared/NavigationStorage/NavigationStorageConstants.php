<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\NavigationStorage;

class NavigationStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing translation messages
     *
     * @api
     */
    const NAVIGATION_SYNC_STORAGE_QUEUE = 'sync.storage.category';

    /**
     * Specification:
     * - Queue name as used for processing translation messages
     *
     * @api
     */
    const NAVIGATION_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.category.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const RESOURCE_NAME = 'navigation';
}
