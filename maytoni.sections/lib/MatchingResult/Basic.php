<?php

namespace Bitrix\Maytoni\Sections\MatchingResult;


class Basic implements MatchingResultInterface
{
    protected string $main;
    protected array $all = [];
    protected string $type;
    protected string $condition;
    protected int $priority;

    public function setMain(string $section): void
    {
        $this->main = $section;
    }

    public function setCondition(string $condition):void
    {
        $this->condition = $condition;
    }

    public function getCondition(): string
    {
        return $this->condition;
    }

    public function isSet(): bool
    {
        return isset($this->main, $this->type,$this->condition);
    }

    public function setAll(string $section): void
    {
        $this->all[] = $section;
    }

    public function getMain(): string
    {
        return $this->main;
    }

    public function getAll(): array
    {
        return $this->all;
    }

    public function hasMain(): bool
    {
        return isset($this->main);
    }

    public function hasAll(): bool
    {
        return count($this->all) > 0;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }
    public function getPriority()
    {
        return $this->priority;
    }



}