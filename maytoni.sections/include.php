<?php

use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Maytoni\Sections\SectionsFacade;
use Bitrix\Maytoni\Sections\SectionsService;
use Bitrix\Maytoni\Sections\DataMapper\HighLoadDataMapper;
use Bitrix\Maytoni\Sections\Configuration\ConfigurationBasic;
use Bitrix\Maytoni\Sections\Configuration\ConfigurationRepository;

defined('B_PROLOG_INCLUDED') || die();

$configurationsHlName = \COption::GetOptionString('maytoni.sections', 'configurations_hl_name');
$pathToConditions = \COption::GetOptionString('maytoni.sections', 'path_to_conditions');

ServiceLocator::getInstance()->add(
    'maytoni.sections.service',
    function ($configurationsHlName,$pathToConditions) {
        return new SectionsService(new ConfigurationBasic(new ConfigurationRepository(new HighLoadDataMapper($configurationsHlName))),$pathToConditions);
    }
);

ServiceLocator::getInstance()->add(
    'maytoni.sections.facade',
    function ($pathToConditions) {
        $service = ServiceLocator::getInstance()->get('maytoni.sections.service');
        return new SectionsFacade($service, $pathToConditions);
    }
);