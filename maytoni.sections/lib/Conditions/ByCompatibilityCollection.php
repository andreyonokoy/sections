<?php

namespace Bitrix\Maytoni\Sections\Conditions;

use Bitrix\Maytoni\Sections\MatchingResult\Basic as BasicMatchingResult;
use Bitrix\Maytoni\Sections\MatchingResult\MatchingResultInterface;
use Bitrix\Maytoni\Sections\ProductParametersModel;
use Bitrix\Maytoni\Sections\Configuration\ConfigurationModel;


class ByCompatibilityCollection extends Basic implements ConditionsInterface
{
    protected string $parentCondition = 'ByViewCollection';

    public function match(ProductParametersModel $parameters, ConfigurationModel $row): bool
    {
        return $row->getView() === $parameters->getView() && $row->getCollection() === $parameters->getCollection();
    }

    public function setMatchingType(ProductParametersModel $parameters): string
    {
        return MatchingTypes::Many;
    }

    public function getDefaultPriority()
    {
        return 100000;
    }

    public function process(ProductParametersModel $parameters): MatchingResultInterface
    {
        $parametersClone = clone $parameters;

        $matching = new BasicMatchingResult();
        $brand = $parametersClone->getBrand();
        $condition = $this->parentCondition;
        $compatibilityCollections = $parametersClone->getAdditional('collection_compatibility');

        $reflection = new \ReflectionClass($this);

        $matching->setCondition($reflection->getShortName());

        $priority = $this->getDefaultPriority();
        $matching->setPriority($priority);
        $matching->setType($this->setMatchingType($parametersClone));

        foreach ($compatibilityCollections as $collection) {
            $parametersClone->updateCollection($collection);
            foreach ($this->configuration->getByBrandCondition($brand, $condition) as $row) {
                if ($this->match($parametersClone, $row)) {
                    $matching = $this->setMultiMatches($parametersClone, $row, $matching);
                }
            }
        }


        return $matching;
    }


    protected function setMultiMatches(
        ProductParametersModel $parameters,
        ConfigurationModel $row,
        MatchingResultInterface $matching
    ): MatchingResultInterface {
        $section = $row->getSectionMatch();
        $matching->setMain($section);
        $matching->setAll($section);
        return $matching;
    }


}