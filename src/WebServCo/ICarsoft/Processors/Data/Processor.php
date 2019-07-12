<?php
namespace WebServCo\ICarsoft\Processors\Data;

use WebServCo\ICarsoft\Delimiter;
use WebServCo\ICarsoft\Exceptions\ProcessorException;

final class Processor extends \WebServCo\ICarsoft\Processors\AbstractProcessor
{
    /*
    * Process body with frames.
    * First part is the title, rest is frame data.
    */
    protected function processBodyParts()
    {
        $bodyParts = explode(Delimiter::FRAME_SECTION, $this->bodyData);
        if (empty($bodyParts[1])) { // no frame data
            throw new ProcessorException('Error processing body section');
        }
        $i = 0;
        foreach ($bodyParts as $part) {
            if (empty($i)) {
                $this->titleData = $this->filterSectionData($part);
            } else {
                $this->frameData[] = $this->filterSectionData($part);
            }
            $i++;
        }
        return true;
    }

    protected function processContent()
    {
        if (!is_array($this->frameData)) { // fault, data
                throw new ProcessorException('Error procesisng frames');
        }
        foreach ($this->frameData as $frame) {
            $this->frames[] = $this->processFrame($frame);
        }
    }

    protected function processFrame($data)
    {
        $frame = [];
        $lines = $this->getLines($data);
        foreach ($lines as $line) {
            $parts = $this->getSectionParts($line, Delimiter::FRAME_DATA);

            $key = $this->filterKey($parts[0]);
            $value = isset($parts[1]) ? $parts[1] : null;

            if (\WebServCo\Framework\Utils\Strings::startsWith($key, 'Frame ', false)) {
                $keyParts = explode(' ', $key);
                $key = $keyParts[0];
                $valueParts = [$keyParts[1]];
            } else {
                $valueParts = preg_split('/(?<=[0-9.])(?=[^0-9.]+)/i', (string) $value, 2);
            }

            $frame[$key] = [
                'value' => $this->filterValue($valueParts[0]),
                'units' => !empty($valueParts[1]) ? $this->filterValue($valueParts[1]) : null,
            ];
        }
        return $frame;
    }
}
