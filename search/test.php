<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");


/*$APPLICATION->IncludeComponent(
   "bitrix:search.page",
   "catalog",
   Array(
       "RESTART" => $arParams["RESTART"],
       "NO_WORD_LOGIC" => $arParams["NO_WORD_LOGIC"],
       "USE_LANGUAGE_GUESS" => "Y",
       "CHECK_DATES" => $arParams["CHECK_DATES"],
       "arrFILTER" => array("iblock_".$arParams["IBLOCK_TYPE"]),
       "arrFILTER_iblock_".$arParams["IBLOCK_TYPE"] => array(10, 12),
       "USE_TITLE_RANK" => "Y",
       "DEFAULT_SORT" => "date",
       "FILTER_NAME" => "arrFilter",
       //"arrFILTER" => array(0 => "iblock_1c_catalog"),
       "SHOW_WHERE" => "N",
       "arrWHERE" => array(),
       "SHOW_WHEN" => "N",
       "PAGE_RESULT_COUNT" => 5000,
       "DISPLAY_TOP_PAGER" => "N",
       "DISPLAY_BOTTOM_PAGER" => "Y",
       "PAGER_TITLE" => "",
       "PAGER_SHOW_ALWAYS" => "N",
       "PAGER_TEMPLATE" => "N",
   ),
   false,
   array('HIDE_ICONS' => 'Y')
);*/

$arElements1 = $APPLICATION->IncludeComponent("bitrix:search.page","suggest",Array(
        "TAGS_SORT" => "NAME",
        "TAGS_PAGE_ELEMENTS" => "150",
        "TAGS_PERIOD" => "30",
        "TAGS_INHERIT" => "Y",
        "FONT_MAX" => "50",
        "FONT_MIN" => "10",
        "COLOR_NEW" => "000000",
        "COLOR_OLD" => "C8C8C8",
        "PERIOD_NEW_TAGS" => "",
        "SHOW_CHAIN" => "Y",
        "COLOR_TYPE" => "Y",
        "WIDTH" => "100%",
        "USE_SUGGEST" => "Y",
        "SHOW_RATING" => "Y",
        "PATH_TO_USER_PROFILE" => "",
        "AJAX_MODE" => "N",
        "RESTART" => "Y",
        "NO_WORD_LOGIC" => "N",
        "USE_LANGUAGE_GUESS" => "Y",
        "CHECK_DATES" => "Y",
        "USE_TITLE_RANK" => "Y",
        "DEFAULT_SORT" => "rank",
        "FILTER_NAME" => "",
        "arrFILTER" => array(0 => "iblock_1c_catalog"),
        "SHOW_WHERE" => "Y",
        "arrWHERE" => array(),
        "SHOW_WHEN" => "Y",
        "PAGE_RESULT_COUNT" => "5000",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "DISPLAY_TOP_PAGER" => "Y",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "Результаты поиска",
        "PAGER_SHOW_ALWAYS" => "Y",
        "PAGER_TEMPLATE" => "",
        "AJAX_OPTION_SHADOW" => "Y",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_ADDITIONAL" => ""
    )
);

global $USER;
if ($USER->IsAdmin()){
	echo("<pre>");
	print_r($arElements);
	echo("</pre>");
}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>