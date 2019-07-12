<?php
namespace WebServCo\ICarsoft\Processors;

trait GetTrait
{
    protected $header;
    protected $title;
    protected $info;
    protected $content;
    protected $frames;

    public function getContent()
    {
        return $this->content;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getFrames()
    {
        return $this->frames;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
