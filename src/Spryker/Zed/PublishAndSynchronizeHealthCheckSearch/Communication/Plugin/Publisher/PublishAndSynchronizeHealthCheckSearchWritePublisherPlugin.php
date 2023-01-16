<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Communication\Plugin\Publisher;

use Spryker\Shared\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig getConfig()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business\PublishAndSynchronizeHealthCheckSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Communication\PublishAndSynchronizeHealthCheckSearchCommunicationFactory getFactory()
 */
class PublishAndSynchronizeHealthCheckSearchWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeCollectionByPublishAndSynchronizeHealthCheckEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            PublishAndSynchronizeHealthCheckSearchConfig::ENTITY_SPY_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_CREATE,
            PublishAndSynchronizeHealthCheckSearchConfig::ENTITY_SPY_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_UPDATE,
            PublishAndSynchronizeHealthCheckSearchConfig::PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_PUBLISH,
        ];
    }
}
