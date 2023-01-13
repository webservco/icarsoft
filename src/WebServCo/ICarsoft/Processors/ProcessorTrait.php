<?php

declare(strict_types=1);

namespace WebServCo\ICarsoft\Processors;

use InvalidArgumentException;
use OutOfBoundsException;
use UnexpectedValueException;
use WebServCo\ICarsoft\Delimiter;

use function array_key_exists;
use function explode;

trait ProcessorTrait
{
    protected string $fileData;

    protected array $header;

    protected ?string $headerData = null;
    protected ?string $bodyData = null;

    protected ?string $titleData = null;
    protected ?string $contentData = null;

    protected array $frameData = [];

    protected array $title = [];

    protected array $info = [];

    abstract protected function filterKey(string $data): string;

    abstract protected function filterValue(string $data): string;

    abstract protected function filterTitleValue(string $data): string;

    abstract protected function filterSectionData(string $data): string;

    protected function getLines(string $data): array
    {
        return explode(Delimiter::LINE, $data);
    }

    protected function getSectionParts(string $data, string $delimiter): array
    {
        // PHPStan seems enforces non-empty string requirement, even though explode would throw exception anyway.
        if ($delimiter === '') {
            throw new InvalidArgumentException('Delimiter is empty.');
        }

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
        if ($this->headerData === null) {
            throw new UnexpectedValueException('No header data');
        }

        $lines = explode(Delimiter::LINE, $this->headerData);

        foreach ($lines as $line) {
            $parts = explode(Delimiter::HEADER_DATA, $this->filterSectionData($line));
            $value = $parts[1] ?? '';
            $this->header[$this->filterKey($parts[0])] = $this->filterValue($value);
        }

        return true;
    }

    protected function processTitleAndInfo(): bool
    {
        if ($this->titleData === null) {
            throw new UnexpectedValueException('titleData is null.');
        }
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
