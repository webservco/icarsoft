<?php
namespace WebServCo\ICarsoft\Processors;

trait GetTrait
{
    /**
     * @var array<string,string>
     */
    protected array $header;

    /**
     * @var array<string,string>
     */
    protected array $title;

    /**
     * @var array<string,string>
     */
    protected array $info;

    /**
     * @var array<string,string>
     */
    protected array $content;

    /**
     * @var array<int,array<int|string,array<int,array<string,string|null>>>>
     */
    protected array $frames;

    /**
     * @return array<string,string>
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @return array<string,string>
     */
    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * @return array<int,array<int|string,array<int,array<string,string|null>>>>
     */
    public function getFrames(): array
    {
        return $this->frames;
    }

    /**
     * @return array<string,string>
     */
    public function getInfo(): array
    {
        return $this->info;
    }

    /**
     * @return array<string,string>
     */
    public function getTitle(): array
    {
        return $this->title;
    }
}
