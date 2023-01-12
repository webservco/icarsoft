<?php

declare(strict_types=1);

namespace WebServCo\ICarsoft\Processors;

use OutOfBoundsException;
use UnexpectedValueException;
use WebServCo\ICarsoft\Delimiter;

use function array_key_exists;
use function explode;

trait ProcessorTrait
{
    protected ?string $fileData = null;
    protected ?string $headerData = null;
    protected ?string $bodyData = null;

    protected ?string $titleData = null;
    protected ?string $contentData = null;

    /**
     * @var array<int,string>
     */
    protected array $frameData;

    /**
     * @var array<string,string>
     */
    protected array $title = [];

    /**
     * @var array<string,string>
     */
    protected array $info = [];

    abstract protected function filterKey(string $data): string;

    abstract protected function filterValue(string $data): string;

    abstract protected function filterTitleValue(string $data): string;

    abstract protected function filterSectionData(string $data): string;

    /**
     * @return array <int,string>
     */
    protected function getLines(string $data): array
    {
        $lines = explode(Delimiter::LINE, $data);
        if ($lines === []) {
            throw new OutOfBoundsException('Error processing frame lines');
        }

        return $lines;
    }

    /**
     * @return array <int,string>
     */
    protected function getSectionParts(string $data, string $delimiter): array
    {
        return explode($delimiter, $this->filterSectionData($data));
    }

    protected function processFileParts(): bool
    {
        $fileParts = explode(Delimiter::HEADER_SECTION, $this->fileData);
        if (!array_key_exists(1, $fileParts)) {
            // no body
            throw new OutOfBoundsException('Error processing header section');
        }
        $this->headerData = $this->filterSectionData($fileParts[0]);
        $this->bodyData = $this->filterSectionData($fileParts[1]);

        return true;
    }

    protected function processHeader(): bool
    {
        if (empty($this->headerData)) {
            throw new UnexpectedValueException('No header data');
        }

        $lines = explode(Delimiter::LINE, $this->headerData);
        if ($lines === []) {
            throw new UnexpectedValueException('Error processing header lines');
        }

        foreach ($lines as $line) {
            $parts = explode(Delimiter::HEADER_DATA, $this->filterSectionData($line));
            $value = $parts[1] ?? null;
            $this->header[$this->filterKey($parts[0])] = $this->filterValue($value);
        }

        return true;
    }

    protected function processTitleAndInfo(): bool
    {
        $titleLines = explode(Delimiter::TITLE_SECTION, $this->titleData, 2);
        $titleItems = explode(Delimiter::TITLE_DATA, $titleLines[0]);
        foreach ($titleItems as $k => $v) {
            $this->title[$k] = $this->filterTitleValue($v);
        }
        /* Info */
        if (array_key_exists(1, $titleLines)) {
            $infoItems = explode(Delimiter::INFO_ITEMS, $titleLines[1]);
            foreach ($infoItems as $item) {
                $parts = explode(Delimiter::INFO_DATA, $item);
                if (!array_key_exists(1, $parts)) {
                    throw new OutOfBoundsException('Error processing info item');
                }
                $this->info[$this->filterKey($parts[0])] = $this->filterValue($parts[1]);
            }
        }

        return true;
    }
}
