<?php

declare(strict_types=1);

namespace WebServCo\ICarsoft\Processors;

use RuntimeException;

use function file_get_contents;
use function is_readable;

abstract class AbstractProcessor
{
    use FilterTrait;
    use GetTrait;
    use ProcessorTrait;

    protected string $fileData;

    abstract protected function processBodyParts(): bool;

    abstract protected function processContent(): bool;

    public function __construct(string $filePath)
    {
        if (!is_readable($filePath)) {
            throw new RuntimeException('File path not readable');
        }
        $fileData = file_get_contents($filePath);
        if ($fileData === false) {
            throw new RuntimeException('Error loading file data.');
        }
        $this->fileData = $fileData;
        $this->header = [];
        $this->title = [];
        $this->info = [];
        $this->content = [];
        $this->frames = [];
    }

    public function run(): void
    {
        /* process file */

        $this->processFileParts();
        $this->processBodyParts();

        /* process data*/

        $this->processHeader();
        $this->processTitleAndInfo();
        $this->processContent();
    }
}
