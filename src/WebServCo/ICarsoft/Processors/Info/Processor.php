<?php

declare(strict_types=1);

namespace WebServCo\ICarsoft\Processors\Info;

use OutOfBoundsException;
use WebServCo\ICarsoft\Delimiter;
use WebServCo\ICarsoft\Processors\AbstractProcessor;

use function array_key_exists;
use function explode;

final class Processor extends AbstractProcessor
{
    /*
    * Body with no frames
    */
    protected function processBodyParts(): bool
    {
        $bodyParts = explode(Delimiter::TITLE_SECTION, $this->bodyData, 2);
        if (!array_key_exists(1, $bodyParts)) {
            // No data.
            throw new OutOfBoundsException('Error processing body section');
        }
        $this->titleData = $this->filterSectionData($bodyParts[0]);
        $this->contentData = $this->filterSectionData($bodyParts[1]);

        return true;
    }

    protected function processContent(): bool
    {
        $lines = $this->getLines($this->contentData);
        foreach ($lines as $line) {
            $parts = $this->getSectionParts($line, Delimiter::INFO_DATA);

            $key = $this->filterKey($parts[0]);
            $value = $parts[1] ?? null;

            $this->content[$key] = $this->filterValue($value);
        }

        return true;
    }
}
