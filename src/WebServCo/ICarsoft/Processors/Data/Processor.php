<?php

declare(strict_types=1);

namespace WebServCo\ICarsoft\Processors\Data;

use WebServCo\ICarsoft\Delimiter;
use WebServCo\ICarsoft\Exceptions\ProcessorException;
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
        $bodyParts = explode(Delimiter::FRAME_SECTION, $this->bodyData);
        if (!array_key_exists(1, $bodyParts)) {
            // no frame data
            throw new ProcessorException('Error processing body section');
        }
        $i = 0;
        foreach ($bodyParts as $part) {
            if ($i === 0) {
                $this->titleData = $this->filterSectionData($part);
            } else {
                $this->frameData[] = $this->filterSectionData($part);
            }
            $i++;
        }

        return true;
    }

    protected function processContent(): bool
    {
        // fault, data
        if (!is_array($this->frameData)) {
                throw new ProcessorException('Error processing frames');
        }
        foreach ($this->frameData as $frame) {
            $this->frames[] = $this->processFrame($frame);
        }

        return true;
    }

    /**
     * @return array<int|string,array<int,array<string,string|null>>>
     */
    protected function processFrame(string $data): array
    {
        $frame = [];
        $lines = $this->getLines($data);
        foreach ($lines as $line) {
            $parts = $this->getSectionParts($line, Delimiter::FRAME_DATA);

            $key = $this->filterKey($parts[0]);
            $value = $parts[1] ?? null;

            if (mb_strpos($key, 'Frame', 0) === 0) {
                $keyParts = explode(' ', $key);
                $key = $keyParts[0];
                $valueParts = [$keyParts[1]];
            } else {
                $valueParts = preg_split('/(?<=[0-9.])(?=[^0-9.]+)/i', (string) $value, 2);
            }

            $frame[$key][] = [
                'value' => $this->filterValue($valueParts[0]),
                'units' => array_key_exists(1, $valueParts) ? $this->filterValue($valueParts[1]) : null,
            ];
        }

        return $frame;
    }
}
