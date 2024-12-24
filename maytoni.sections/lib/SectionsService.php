<?php

namespace Bitrix\Maytoni\Sections;

use Bitrix\Maytoni\Sections\Conditions\ConditionsInterface;
use Bitrix\Maytoni\Sections\Conditions\MatchingTypes;
use Bitrix\Maytoni\Sections\Configuration\ConfigurationInterface;
use Bitrix\Maytoni\Sections\Exception\SectionsException;

class SectionsService
{
    protected array $conditions = [];

    public function __construct(protected ConfigurationInterface $configuration, protected string $pathToConditions)
    {
    }

    public function findSection(ProductParametersModel $parameters): array
    {
        try {
            $sections = [];
            $brand = $parameters->getBrand();
            foreach ($this->configuration->getByBrand($brand) as $condition => $rows) {
                if (!isset($this->conditions[$brand][$condition])) {
                    $this->conditions[$brand][$condition] = $this->initCondition($condition);
                }
                $sections[] = $this->conditions[$brand][$condition]->process($parameters);
            }

            $sections=$this->validateResult($sections);

            return [
                'sections' => $sections,
            ];
        } catch (SectionsException|\Exception $exception) {
            return [
                'error' => $exception->getMessage(),
            ];
        }
    }

    protected function initCondition(string $condition): ConditionsInterface
    {
        $conditonsClass = $this->pathToConditions . $condition;
        return new $conditonsClass($this->configuration);
    }

    protected function validateResult(array $result): array
    {
        $validatedResult=[];
        $maxPriority=0;
        $noValidateResult=[];

        foreach ($result as $match) {
            if(!$match->isSet())
            {
                continue;
            }
            if ($match->getType()===MatchingTypes::One)
            {
                $priority=$match->getPriority();
                if($priority>$maxPriority)
                {
                    $validatedResult=[];
                    $maxPriority=$priority;
                    $validatedResult[]=$match;
                }
                elseif($priority===$maxPriority)
                {
                    $validatedResult[]=$match;
                }
            }
            else
            {
                $noValidateResult[]=$match;
            }
        }

        $validatedResult=array_merge($validatedResult,$noValidateResult);
        
        if (count($validatedResult) > 2) {
            throw new SectionsException('Found more then one section with same Priority');
        }
        if (count($validatedResult) === 0) {
            throw new SectionsException('No sections found');
        }
        return $validatedResult;
    }


}