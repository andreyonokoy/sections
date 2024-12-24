<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/options.php");
Loc::loadMessages(__FILE__);

$module_id = "maytoni.sections";

if ($APPLICATION->GetGroupRight($module_id) < "S") {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

Loader::includeModule($module_id);

$request = \Bitrix\Main\HttpApplication::getInstance()->getContext()->getRequest();

$aTabs = [
    [
        "DIV"     => "edit1",
        "TAB"     => Loc::getMessage("MAYTONI_SECTIONS_TAB_SETTINGS"),
        "TITLE"   => Loc::getMessage("MAYTONI_SECTIONS_TAB_TITLE"),
        "OPTIONS" => [
            [
                "configurations_hl_name",
                Loc::getMessage("MAYTONI_SECTIONS_CONFIGURATION_HL_NAME"),
                "",
                [
                    "text",
                    100,
                ],
            ],
        ],
    ],
    [
        "DIV"   => "edit2",
        "TAB"   => Loc::getMessage("MAIN_TAB_RIGHTS"),
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS"),
    ],
];

if ($request->isPost() && $request["Update"] && check_bitrix_sessid()) {
    foreach ($aTabs as $aTab) {
        foreach ($aTab["OPTIONS"] as $arOption) {
            if (!is_array($arOption)) {
                continue;
            }
            if ($arOption["note"]) {
                continue;
            }

            $optionName = $arOption[0];
            $optionValue = $request->getPost($optionName);
            Option::set($module_id, $optionName, is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
        }
    }
}

$tabControl = new CAdminTabControl('tabControl', $aTabs);

$tabControl->Begin();

?>
    <form method="post" name="MAYTONI_SECTIONS_settings"
          action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx(
              $request["mid"]
          ) ?>&lang=<?= $request["lang"] ?>">
        <?
        echo bitrix_sessid_post();
        foreach ($aTabs as $aTab):
            if ($aTab["OPTIONS"]):
                $tabControl->BeginNextTab();
                __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
            endif;
        endforeach;
        $tabControl->BeginNextTab();
        require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php";

        $tabControl->Buttons();
        ?>
        <input type="submit" name="Update" value="<?= GetMessage("MAIN_SAVE") ?>">
        <input type="reset" name="reset" value="<?= GetMessage("MAIN_RESET") ?>">
    </form>

<?php
$tabControl->End();

?>