<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;

Loc::loadMessages(__FILE__);

class maytoni_sections extends CModule
{
    protected string $hlBlockName;

    public function __construct()
    {
        include(__DIR__ . "/../version.php");
        if (isset($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->hlBlockName = \COption::GetOptionString('maytoni.sections', 'configurations_hl_name');
        $this->MODULE_ID = 'maytoni.sections';
        $this->MODULE_NAME = Loc::getMessage('MAYTONI_SECTIONS_MODULE_NAME');
        $this->PARTNER_NAME = Loc::getMessage('MAYTONI_SECTIONS_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('MAYTONI_SECTIONS_PARTNER_URI');
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installHL();
    }

    public function doUninstall()
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installHL()
    {
        Loader::includeModule($this->MODULE_ID);
        Loader::includeModule('highloadblock');

        if ($id = $this->addHL()) {
            $this->addFields($id);
        }
    }

    public function checkIfExist(): bool
    {
        $filter = [
            'filter' => ['=NAME' => $this->hlBlockName],
        ];

        return (bool)HighloadBlockTable::getList($filter)->fetch();
    }

    public function addHL(): int|bool
    {
        if ($this->checkIfExist()) {
            return false;
        }

        $result = HighloadBlockTable::add([
            'NAME'       => $this->hlBlockName,
            'TABLE_NAME' => strtolower($this->hlBlockName),
        ]);

        if ($result->isSuccess()) {
            $id = $result->getId();

            HighloadBlockLangTable::add([
                'ID'   => $id,
                'LID'  => 'en',
                'NAME' => '',
            ]);

            return $id;
        }

        return false;
    }

    public function addFields(int $hlId): void
    {
        $UFObject = 'HLBLOCK_' . $hlId;

        $arFields = [
            'UF_BRAND'      => [
                'ENTITY_ID'    => $UFObject,
                'FIELD_NAME'   => 'UF_BRAND',
                'USER_TYPE_ID' => 'string',
                'MANDATORY'    => 'Y',
            ],
            'UF_COLLECTION' => [
                'ENTITY_ID'    => $UFObject,
                'FIELD_NAME'   => 'UF_COLLECTION',
                'USER_TYPE_ID' => 'string',
                'MANDATORY'    => 'Y',
            ],
            'UF_TYPE'       => [
                'ENTITY_ID'    => $UFObject,
                'FIELD_NAME'   => 'UF_TYPE',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY'    => 'Y',
            ],
            'UF_SERIES'     => [
                'ENTITY_ID'    => $UFObject,
                'FIELD_NAME'   => 'UF_SERIES',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY'    => 'Y',
            ],
            'UF_MATCH'      => [
                'ENTITY_ID'    => $UFObject,
                'FIELD_NAME'   => 'UF_MATCH',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY'    => 'Y',
            ],
            'UF_CONDITION'  => [
                'ENTITY_ID'    => $UFObject,
                'FIELD_NAME'   => 'UF_CONDITION',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY'    => 'Y',
            ],
        ];

        foreach ($arFields as $field) {
            $obUserField = new \CUserTypeEntity;
            $obUserField->Add($field);
        }
    }

}