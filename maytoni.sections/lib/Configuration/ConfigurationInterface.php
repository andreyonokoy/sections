<?php

namespace Bitrix\Maytoni\Sections\Configuration;

interface ConfigurationInterface
{
    public function getByBrand(string $brand): array;

    public function getByBrandCondition(string $brand,string $condition): array;

}