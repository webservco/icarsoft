<?php

declare(strict_types=1);

namespace WebServCo\ICarsoft\Processors\Data;

use OutOfBoundsException;
use UnexpectedValueException;
use WebServCo\ICarsoft\Delimiter;
use WebServCo\ICarsoft\Processors\AbstractProcessor;

use function array_key_exists;
use function explode;
use function is_array;
use function mb_strpos;
use function preg_split;

final class Processor extends AbstractProcessor
{
    /*
    * Process body with frames.
    * First part is the title, rest is frame data.
    */
    protected function processBodyParts(): bool
    {
        if ($this->bodyData === null) {
            throw new UnexpectedValueException('bodyData is null');
        }
        $bodyParts = explode(Delimiter::FRAME_SECTION, $this->bodyData);
        if (!array_key_exists(1, $bodyParts)) {
            // no frame data
            throw new OutOfBoundsException('Error processing body section');
        }
        $index = 0;
        foreach ($bodyParts as $part) {
            $this->processBodyPart($index, $part);
            $index += 1;
        }

        return true;
    }

    protected function processContent(): bool
    {
        // fault, data
        foreach ($this->frameData as $frame) {
            $this->frames[] = $this->processFrame($frame);
        }

        return true;
    }

    protected function processFrame(string $data): array
    {
        $frame = [];
        $lines = $this->getLines($data);
        foreach ($lines as $line) {
            $parts = $this->getSectionParts($line, Delimiter::FRAME_DATA);

            $key = $this->filterKey($parts[0]);
            $value = $parts[1] ?? '';

            if (mb_strpos($key, 'Frame', 0) === 0) {
                $keyParts = explode(' ', $key);
                $key = $keyParts[0];
                $valueParts = [$keyParts[1]];
            } else {
                $valueParts = preg_split('/(?<=[0-9.])(?=[^0-9.]+)/i', $value, 2);
            }

            if (!is_array($valueParts)) {
                throw new UnexpectedValueException('valueParts is not an array');
            }

            $frame[$key][] = [
                'value' => $this->filterValue($valueParts[0]),
                'units' => array_key_exists(1, $valueParts) ? $this->filterValue($valueParts[1]) : null,
            ];
        }

        return $frame;
    }

    private function processBodyPart(int $index, string $part): bool
    {
        if ($index === 0) {
            $this->titleData = $this->filterSectionData($part);

            return true;
        }
        $this->frameData[] = $this->filterSectionData($part);

        return true;
    }
}
