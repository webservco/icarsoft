<?php
namespace WebServCo\ICarsoft\Processors;

trait FilterTrait
{
    protected function filterKey(string $data): string
    {
        return trim($data);
    }

    protected function filterValue(string $data): string
    {
        return trim($data);
    }

    protected function filterTitleValue(string $data): string
    {
        return trim($data, " \t\n\r\0\x0B:");
    }

    protected function filterSectionData(string $data): string
    {
        return trim($data, " \t\n\r\0\x0B:");
    }
}
