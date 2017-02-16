<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?>



<div class="contacts-shops">
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        ".default",
        array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => "/include/shops_about.php",
            "EDIT_TEMPLATE" => "standard.php"
        ),
        false
    );?>
</div>

    <div class="kontakty_text">
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.include",
            ".default",
            array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/include/kontakty_text.php",
                "EDIT_TEMPLATE" => "standard.php"
            ),
            false
        );?>


    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>