<?php
namespace WebServCo\ICarsoft\Processors;

use WebServCo\ICarsoft\Delimiter;
use WebServCo\ICarsoft\Exceptions\ProcessorException;

trait ProcessorTrait
{
    protected $fileData;
    protected $headerData;
    protected $bodyData;

    protected $titleData;
    protected $infoData;
    protected $contentData;
    protected $frameData;

    protected $title;
    protected $info;

    abstract protected function filterKey($data);

    abstract protected function filterValue($data);

    abstract protected function filterTitleValue($data);

    abstract protected function filterSectionData($data);

    protected function getLines($data)
    {
        $lines = explode(Delimiter::LINE, $data);
        if (empty($lines)) {
            throw new ProcessorException('Error processing frame lines');
        }
        return $lines;
    }

    protected function getSectionParts($data, $delimiter)
    {
        return explode($delimiter, $this->filterSectionData($data));
    }

    protected function processFileParts()
    {
        $fileParts = explode(Delimiter::HEADER_SECTION, $this->fileData);
        if (empty($fileParts[1])) { // no body
            throw new ProcessorException('Error processing header section');
        }
        $this->headerData = $this->filterSectionData($fileParts[0]);
        $this->bodyData = $this->filterSectionData($fileParts[1]);
        return true;
    }

    protected function processHeader()
    {
        if (empty($this->headerData)) {
            throw new ProcessorException('No header data');
        }

        $lines = explode(Delimiter::LINE, $this->headerData);
        if (empty($lines)) {
            throw new ProcessorException('Error processing header lines');
        }

        foreach ($lines as $line) {
            $parts = explode(Delimiter::HEADER_DATA, $this->filterSectionData($line));
            $value = isset($parts[1]) ? $parts[1] : null;
            $this->header[$this->filterKey($parts[0])] = $this->filterValue($value);
        }
        return true;
    }

    protected function processTitleAndInfo()
    {
        $titleLines = explode(Delimiter::TITLE_SECTION, $this->titleData, 2);
        $titleItems = explode(Delimiter::TITLE_DATA, $titleLines[0]);
        foreach ($titleItems as $k => $v) {
            $this->title[$k] = $this->filterTitleValue($v);
        }
        /* Info */
        if (!empty($titleLines[1])) {
            $infoItems = explode(Delimiter::INFO_ITEMS, $titleLines[1]);
            foreach ($infoItems as $item) {
                $parts = explode(Delimiter::INFO_DATA, $item);
                if (!isset($parts[1])) {
                    throw new ProcessorException('Error processing info item');
                }
                $this->info[$this->filterKey($parts[0])] = $this->filterValue($parts[1]);
            }
        }
        return true;
    }
}
