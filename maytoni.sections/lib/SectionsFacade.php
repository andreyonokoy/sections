<?php

namespace Bitrix\Maytoni\Sections;

use Bitrix\Maytoni\Sections\DataMapper\HighLoadDataMapper;
use Bitrix\Maytoni\Sections\Configuration\ConfigurationBasic;
use Bitrix\Maytoni\Sections\Configuration\ConfigurationRepository;

class SectionsFacade
{

    public function __construct(protected SectionsService $service)
    {
    }

    public function getByProductParameters(
        string $brand,
        string $collection,
        string $view,
        string $series,
        string $type,
        array $additional = []
    ): array {
        $parameters = new ProductParametersModel($brand, $collection, $view, $series, $type, $additional);
        return $this->service->findSection($parameters);
    }


}