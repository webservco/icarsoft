<?php
namespace WebServCo\ICarsoft\Processors;

abstract class AbstractProcessor
{
    protected $fileData;

    use FilterTrait;
    use GetTrait;
    use ProcessorTrait;

    abstract protected function processBodyParts();
    abstract protected function processContent();

    public function __construct($filePath)
    {
        if (!is_readable($filePath)) {
            throw new \WebServCo\ICarsoft\Exceptions\ICarsoftException('File path not readable');
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
