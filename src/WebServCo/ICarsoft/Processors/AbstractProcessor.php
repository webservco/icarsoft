<?php
namespace WebServCo\ICarsoft\Processors;

use RuntimeException;

abstract class AbstractProcessor
{
    protected string $fileData;

    use FilterTrait;
    use GetTrait;
    use ProcessorTrait;

    abstract protected function processBodyParts(): bool;
    abstract protected function processContent(): bool;

    public function __construct(string $filePath)
    {
        if (!is_readable($filePath)) {
            throw new RuntimeException('File path not readable');
        }
        $this->fileData = file_get_contents($filePath);
        $this->header = [];
        $this->title = [];
        $this->info = [];
        $this->content = [];
        $this->frames = [];
    }

    public function run()
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
