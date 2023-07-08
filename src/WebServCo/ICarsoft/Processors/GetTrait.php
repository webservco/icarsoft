<?php

declare(strict_types=1);

namespace WebServCo\ICarsoft\Processors;

trait GetTrait
{
    protected array $header;

    protected array $title = [];

    protected array $info = [];

    protected array $content;

    protected array $frames;

    public function getContent(): array
    {
        return $this->content;
    }

    public function getHeader(): array
    {
        return $this->header;
    }

    public function getFrames(): array
    {
        return $this->frames;
    }

    public function getInfo(): array
    {
        return $this->info;
    }

    public function getTitle(): array
    {
        return $this->title;
    }
}
