<?php

namespace App;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelSheet
{
    private ?Spreadsheet $spreadsheet;
    private ?Worksheet $worksheet;

    public static function getInstance(): ExcelSheet
    {
        return new self();
    }

    public function getAvailableSheets(string $filePath, array $needle): array
    {
        $this->initFile($filePath);

        $availableSheets = [];
        foreach ($needle as $sheetName) {
            $worksheet = $this->spreadsheet->getSheetByName($sheetName);
            $isAvailable = (bool)$worksheet;

            $availableSheets[$sheetName] = $isAvailable;
        }

        return $availableSheets;
    }

    public function getHeader(string $filePath, ?string $sheetName = null): array
    {
        $this->initFile($filePath, $sheetName);

        $firstRow = $this->worksheet->getRowIterator()->current();

        return $this->getRowData($firstRow);
    }

    public function getTotalRowCount(string $filePath, ?string $sheetName = null): int
    {
        $this->initFile($filePath, $sheetName);

        return $this->worksheet->getHighestRow();
    }

    /**
     * @param string $filePath Path to the Excel file.
     * @param int $offset 1-based offset.
     * @param int $limit
     * @param string|null $sheetName
     * @return array
     */
    public function getRows(
        string $filePath,
        int $offset = 1,
        int $limit = 20,
        ?string $sheetName = null,
    ): array {
        $header = $this->getHeader($filePath, $sheetName);

        $data = [];
        foreach ($this->worksheet->getRowIterator($offset, $offset + $limit) as $index => $row) {
            if ($index === 1) {
                // Ignore header for row data. Use ::getHeader() for that data.

                continue;
            }

            if ($index > $this->getTotalRowCount($filePath, $sheetName)) {
                // Exit loop if there is no data left.

                break;
            }

            $rowData = $this->getRowData($row, $header);
            if ($this->isEmptyRow($rowData)) {
                continue;
            }

            $data[] = $rowData;
        }

        return $data;
    }

    private function initFile(string $filePath, ?string $sheetName = null): void
    {
        $this->spreadsheet = IOFactory::load($filePath);
        $this->setWorksheet($sheetName);
    }

    private function setWorksheet(?string $name): void
    {
        if (!$name) {
            $this->worksheet = $this->spreadsheet->getActiveSheet();
            return;
        }

        $this->worksheet = $this->spreadsheet->getSheetByName($name);
    }

    private function getRowData(Row $row, ?array $header = null): array
    {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $data = [];
        foreach ($cellIterator as $cell) {
            $data[] = $cell->getValue();
        }

        if (!$header) {
            return $data;
        }

        // Adds the key of the header as key for the data values.
        // This allows easy access such as $rowData['name'];
        return array_combine($header, $data);
    }

    private function isEmptyRow(array $rowData): bool
    {
        $filtered = array_filter($rowData);

        return count($filtered) === 0;
    }
}
