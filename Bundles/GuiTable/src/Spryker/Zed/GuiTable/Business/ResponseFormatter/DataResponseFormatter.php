<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Business\ResponseFormatter;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;

class DataResponseFormatter implements DataResponseFormatterInterface
{
    /**
     * @var \Spryker\Zed\GuiTableExtension\Dependency\Plugin\ResponseColumnValueFormatterPluginInterface[]
     */
    protected $responseColumnValueFormatterPlugins;

    /**
     * @param \Spryker\Zed\GuiTableExtension\Dependency\Plugin\ResponseColumnValueFormatterPluginInterface[] $responseColumnValueFormatterPlugins
     */
    public function __construct(array $responseColumnValueFormatterPlugins = [])
    {
        $this->responseColumnValueFormatterPlugins = $responseColumnValueFormatterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return mixed[]
     */
    public function formatGuiTableDataResponse(
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): array {
        $guiTableDataResponseArray = $guiTableDataResponseTransfer->toArray(true, true);
        $guiTableDataResponseArray = $this->executeResponseColumnValueFormatterPlugins(
            $guiTableDataResponseArray,
            $guiTableConfigurationTransfer
        );

        return $guiTableDataResponseArray;
    }

    /**
     * @param array $guiTableDataResponseArray
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return mixed[]
     */
    protected function executeResponseColumnValueFormatterPlugins(
        array $guiTableDataResponseArray,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): array {
        $indexedPlugins = $this->indexPluginsByColumnIds($guiTableConfigurationTransfer);
        $guiTableData = $guiTableDataResponseArray[GuiTableDataResponseTransfer::DATA];

        foreach ($guiTableData as $tableRowKey => $tableRowData) {
            foreach ($tableRowData as $columnId => $columnValue) {
                if (isset($indexedPlugins[$columnId])) {
                    $guiTableData[$tableRowKey][$columnId] = $indexedPlugins[$columnId]->formatColumnValue($columnValue);
                }
            }
        }

        $guiTableDataResponseArray[GuiTableDataResponseTransfer::DATA] = $guiTableData;

        return $guiTableDataResponseArray;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Spryker\Zed\GuiTableExtension\Dependency\Plugin\ResponseColumnValueFormatterPluginInterface[]
     */
    protected function indexPluginsByColumnIds(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $indexedPlugins = [];
        foreach ($this->responseColumnValueFormatterPlugins as $responseColumnValueFormatterPlugin) {
            foreach ($guiTableConfigurationTransfer->getColumns() as $guiTableColumnConfigurationTransfer) {
                if ($guiTableColumnConfigurationTransfer->getType() === $responseColumnValueFormatterPlugin->getColumnType()) {
                    $indexedPlugins[$guiTableColumnConfigurationTransfer->getId()] = $responseColumnValueFormatterPlugin;
                }
            }
        }

        return $indexedPlugins;
    }
}
