<?php
namespace WebServCo\ICarsoft\Processors;

trait FilterTrait
{
    protected function filterKey($data)
    {
        return trim($data);
    }

    protected function filterValue($data)
    {
        return trim($data);
    }

    protected function filterTitleValue($data)
    {
        return trim($data, " \t\n\r\0\x0B:");
    }

    protected function filterSectionData($data)
    {
        return trim($data, " \t\n\r\0\x0B:");
    }
}
