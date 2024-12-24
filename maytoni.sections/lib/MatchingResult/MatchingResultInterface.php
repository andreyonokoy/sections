<?php

namespace Bitrix\Maytoni\Sections\MatchingResult;


interface MatchingResultInterface
{
    public function getAll(): array;

    public function getMain(): string;

    public function setAll(string $section): void;

    public function setType(string $type): void;

    public function getType(): string;

    public function getCondition(): string;

}