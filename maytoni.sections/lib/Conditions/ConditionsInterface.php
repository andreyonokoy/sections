<?php

namespace Bitrix\Maytoni\Sections\Conditions;

use Bitrix\Maytoni\Sections\ProductParametersModel;
use Bitrix\Maytoni\Sections\MatchingResult\MatchingResultInterface;
use Bitrix\Maytoni\Sections\Configuration\ConfigurationModel;

interface ConditionsInterface
{
    public function process(ProductParametersModel $parameters): MatchingResultInterface;

    public function setMatchingType(ProductParametersModel $parameters): string;

    public function match(ProductParametersModel $parameters, ConfigurationModel $row): bool;
}
