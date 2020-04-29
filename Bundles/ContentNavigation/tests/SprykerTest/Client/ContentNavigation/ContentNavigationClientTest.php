<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ContentNavigation;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentNavigationTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Spryker\Client\ContentNavigation\ContentNavigationClient;
use Spryker\Client\ContentNavigation\ContentNavigationClientInterface;
use Spryker\Client\ContentNavigation\ContentNavigationDependencyProvider;
use Spryker\Client\ContentNavigation\Dependency\Client\ContentNavigationToContentStorageClientInterface;
use Spryker\Client\ContentNavigation\Exception\MissingNavigationTermException;
use Spryker\Shared\ContentNavigation\ContentNavigationConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ContentNavigation
 * @group ContentNavigationClientTest
 * Add your own group annotations below this line
 */
class ContentNavigationClientTest extends Unit
{
    /**
     * @var int
     */
    public const ID_CONTENT_ITEM = 1;

    /**
     * @var int
     */
    public const ID_NAVIGATION = 1;

    /**
     * @var string
     */
    public const CONTENT_KEY = 'test-key';

    /**
     * @var string
     */
    public const WRONG_TERM = 'TERM';

    /**
     * @var string
     */
    public const LOCALE = 'zh-CN';

    /**
     * @var \SprykerTest\Client\ContentNavigation\ContentNavigationClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindContentNavigationValidTransfer(): void
    {
        // Arrange
        $contentTypeContextTransfer = new ContentTypeContextTransfer();
        $contentTypeContextTransfer->setIdContent(static::ID_CONTENT_ITEM);
        $contentTypeContextTransfer->setKey(static::CONTENT_KEY);
        $contentTypeContextTransfer->setTerm(ContentNavigationConfig::CONTENT_TERM_NAVIGATION);
        $contentTypeContextTransfer->setParameters(['id_navigation' => [static::ID_NAVIGATION]]);

        $this->setNavigationStorageClientReturn($contentTypeContextTransfer);

        // Act
        $systemUnderTest = $this->createContentNavigationClient()
            ->executeNavigationTypeByKey(static::CONTENT_KEY, static::LOCALE);

        // Assert
        $this->assertEquals(ContentNavigationTypeTransfer::class, get_class($systemUnderTest));
    }

    /**
     * @return void
     */
    public function testFindContentItemWithWrongTermThrowsException(): void
    {
        // Arrange
        $contentTypeContextTransfer = (new ContentTypeContextTransfer())
            ->setIdContent(static::ID_CONTENT_ITEM)
            ->setKey(static::CONTENT_KEY)
            ->setTerm(static::WRONG_TERM)
            ->setParameters(['id_navigation' => [static::ID_NAVIGATION]]);

        $this->setNavigationStorageClientReturn($contentTypeContextTransfer);

        // Assert
        $this->expectException(MissingNavigationTermException::class);

        // Act
        $this->createContentNavigationClient()->executeNavigationTypeByKey(static::CONTENT_KEY, static::LOCALE);
    }

    /**
     * @return void
     */
    public function testFindNotExistingContentNavigation(): void
    {
        // Arrange
        $this->setNavigationStorageClientReturn(null);

        // Act
        $systemUnderTest = $this->createContentNavigationClient()
            ->executeNavigationTypeByKey(static::CONTENT_KEY, static::LOCALE);

        // Assert
        $this->assertNull($systemUnderTest);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer|null $contentTypeContextTransfer
     *
     * @return void
     */
    protected function setNavigationStorageClientReturn(?ContentTypeContextTransfer $contentTypeContextTransfer): void
    {
        $contentNavigationToContentStorageClientBridge = $this->getMockBuilder(ContentNavigationToContentStorageClientInterface::class)->getMock();
        $contentNavigationToContentStorageClientBridge->method('findContentTypeContextByKey')->willReturn($contentTypeContextTransfer);
        $this->tester->setDependency(ContentNavigationDependencyProvider::CLIENT_CONTENT_STORAGE, $contentNavigationToContentStorageClientBridge);
    }

    /**
     * @return \Spryker\Client\ContentNavigation\ContentNavigationClientInterface
     */
    protected function createContentNavigationClient(): ContentNavigationClientInterface
    {
        return new ContentNavigationClient();
    }
}
