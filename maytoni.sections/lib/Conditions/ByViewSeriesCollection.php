<?php

namespace Bitrix\Maytoni\Sections\Conditions;

use Bitrix\Maytoni\Sections\ProductParametersModel;
use Bitrix\Maytoni\Sections\Configuration\ConfigurationModel;


class ByViewSeriesCollection extends Basic implements ConditionsInterface
{

    public function match(ProductParametersModel $parameters, ConfigurationModel $row): bool
    {
        return $row->getView() === $parameters->getView() && $row->getSeries() === $parameters->getSeries(
            ) && $row->getCollection() === $parameters->getCollection();
    }

    public function setMatchingType(ProductParametersModel $parameters): string
    {
        return MatchingTypes::One;
    }

    public function getDefaultPriority(): int
    {
        return 1000;
    }


}