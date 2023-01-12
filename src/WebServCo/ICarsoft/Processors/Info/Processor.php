<?php
namespace WebServCo\ICarsoft\Processors\Info;

use OutOfBoundsException;
use WebServCo\ICarsoft\Delimiter;

final class Processor extends \WebServCo\ICarsoft\Processors\AbstractProcessor
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
            $value = isset($parts[1]) ? $parts[1] : null;

            $this->content[$key] = $this->filterValue($value);
        }
        return true;
    }
}
