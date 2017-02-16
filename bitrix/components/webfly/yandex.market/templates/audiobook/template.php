<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!$arResult["SAVE_IN_FILE"]): ?>
<? echo '<?xml version="1.0" encoding="' . LANG_CHARSET . '"?>'; ?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="<?= $arResult["DATE"] ?>">
        <shop>
            <name><?= $arResult["SITE"] ?></name>
            <company><?= $arResult["COMPANY"] ?></company>
            <url><? if ($arParams ["HTTPS_CHECK"] == "Y"): ?><?= "https://" . $_SERVER["SERVER_NAME"] ?><? else: ?><?= "http://" . $_SERVER["SERVER_NAME"] ?><? endif ?></url>

            <currencies>
                <? if (!empty($arResult["CURRENCIES"])): ?>
                    <? foreach ($arResult["CURRENCIES"] as $k => $cur): ?>
                        <? if (!empty($cur) && $cur != 'RUR'): ?><currency id="<?= $cur ?>"<? if ($cur == 'RUB'): ?> rate="1"<? else: ?> rate="<?= $arResult["WF_AMOUNTS"][$cur] ?>"<? endif; ?>/><? endif; ?>
                    <? endforeach; ?>
                <? else: ?>
                    <currency id="<?= $arParams["CURRENCY"] ?>" rate="1"/>
                <? endif; ?>
            </currencies>

            <categories>
                <? foreach ($arResult["CATEGORIES"] as $arCategory): ?>
                    <category id="<?= $arCategory["ID"] ?>"<?
                    if ($arCategory["PARENT"])
                        echo ' parentId="' . $arCategory['PARENT'] . '"';
                    ?>><?= $arCategory["NAME"] ?></category>
                          <? endforeach; ?>
            </categories>
            <? if ($arParams["LOCAL_DELIVERY_COST"]): ?>
                <local_delivery_cost><?= $arParams["LOCAL_DELIVERY_COST"] ?></local_delivery_cost>
            <? endif ?>
            <offers>
                <? foreach ($arResult["OFFER"] as $arOffer): ?>
                    <offer type="audiobook" id="<?= $arOffer["ID"] ?>" available="<?= $arOffer["AVAIBLE"] ?>">
                        <url><?= $arOffer["URL"] ?></url>
                        <price><?= $arOffer["PRICE"] ?></price>
                        <?if (!empty($arOffer["OLD_PRICE"])):?>
                        <oldprice><?= $arOffer["OLD_PRICE"] ?></oldprice>
                        <?endif?>
                        <currencyId>
                            <? if (!empty($arOffer["CURRENCY"])): ?>
                                <?= $arOffer["CURRENCY"] ?>
                            <? else: ?>
                                <?= $arParams["CURRENCY"] ?>
                            <? endif; ?>
                        </currencyId>
                        <categoryId><?= $arOffer["CATEGORY"] ?></categoryId>
                        <? if ($arOffer["PICTURE"]): ?><picture><?= $arOffer["PICTURE"] ?></picture><? endif ?>
                        <? if ($arOffer["STORE_OFFER"] == "true"): ?><store>true</store><? endif ?>
                        <? if ($arOffer["STORE_OFFER"] == "false"): ?><store>false</store><? endif ?>
                        <? if ($arOffer["STORE_PICKUP"] == "true"): ?><pickup>true</pickup><? endif ?>
                        <? if ($arOffer["STORE_PICKUP"] == "false"): ?><pickup>false</pickup><? endif ?>
                        <? if ($arParams["LOCAL_DELIVERY_COST"] or is_numeric($arOffer["LOCAL_DELIVERY_COST_OFFER"])): ?><delivery>true</delivery><? endif ?>
                        <? if (is_numeric($arOffer["LOCAL_DELIVERY_COST_OFFER"])): ?><local_delivery_cost><?= $arOffer["LOCAL_DELIVERY_COST_OFFER"] ?></local_delivery_cost><? endif ?>
                        <? if (!empty($arOffer["PREFIX_PROP"])): ?>
                            <typePrefix><?= $arOffer["PREFIX_PROP"] ?></typePrefix>
                        <? endif; ?>
                        <name><?= $arOffer["MODEL"] ?></name>
                        <? if ($arOffer["DISPLAY_PROPERTIES"][$arParams["AUTHOR"]]["DISPLAY_VALUE"]): ?><author><?= $arOffer["DISPLAY_PROPERTIES"][$arParams["AUTHOR"]]["DISPLAY_VALUE"] ?></author><? endif ?>				
                        <? if ($arOffer["DISPLAY_PROPERTIES"][$arParams["PUBLISHER"]]["DISPLAY_VALUE"]): ?><publisher><?= $arOffer["DISPLAY_PROPERTIES"][$arParams["PUBLISHER"]]["DISPLAY_VALUE"] ?></publisher><? endif ?>								
                        <? if ($arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"]): ?><year><?= $arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"] ?></year><? endif ?>								
                        <? if ($arOffer["DISPLAY_PROPERTIES"][$arParams["ISBN"]]["DISPLAY_VALUE"]): ?><ISBN><?= $arOffer["DISPLAY_PROPERTIES"][$arParams["ISBN"]]["DISPLAY_VALUE"] ?></ISBN><? endif ?>								
                        <? if ($arOffer["DISPLAY_PROPERTIES"][$arParams["PERFORMED_BY"]]["DISPLAY_VALUE"]): ?><performed_by><?= $arOffer["DISPLAY_PROPERTIES"][$arParams["PERFORMED_BY"]]["DISPLAY_VALUE"] ?></performed_by><? endif ?>								
                        <? if ($arOffer["DISPLAY_PROPERTIES"][$arParams["STORAGE"]]["DISPLAY_VALUE"]): ?><storage><?= $arOffer["DISPLAY_PROPERTIES"][$arParams["STORAGE"]]["DISPLAY_VALUE"] ?></storage><? endif ?>								
                        <? if ($arOffer["DISPLAY_PROPERTIES"][$arParams["FORMAT"]]["DISPLAY_VALUE"]): ?><format><?= $arOffer["DISPLAY_PROPERTIES"][$arParams["FORMAT"]]["DISPLAY_VALUE"] ?></format><? endif ?>								
                        <? if (!empty($arOffer["DESCRIPTION"]) or ! empty($arOffer["DOP_PROPS"])): ?>
                            <description><? if (!empty($arOffer["DOP_PROPS"])): ?><?= $arOffer["DOP_PROPS"] ?>. <? endif ?><?= $arOffer["DESCRIPTION"] ?></description>
                        <? endif; ?>
                        <? if ($arOffer["EXPIRY"]): ?>
                            <expiry><?= $arOffer["EXPIRY"] ?></expiry>
                        <? endif ?>
                        <? if ($arOffer["WEIGHT"]): ?>
                            <weight><?= $arOffer["WEIGHT"] ?></weight>
                        <? endif ?>
                        <? if ($arOffer["DIMENSIONS"]): ?>
                            <dimensions><?= $arOffer["DIMENSIONS"] ?></dimensions>
                        <? endif ?>
                    </offer>
                <? endforeach; ?>
            </offers>
        </shop>
    </yml_catalog>
    <?
else:
    $wf_page = $APPLICATION->GetCurDir();
    $arParams["BIG_CATALOG_PROP"] = trim($arParams["BIG_CATALOG_PROP"]);
    if (!empty($arParams["BIG_CATALOG_PROP"]))
    {
        if ((($arResult["WF_CURR"] - $arParams["BIG_CATALOG_PROP"]) < $arResult["WF_FULL"]))
        {
            if ($arResult["WF_CURR"] < $arResult["WF_FULL"])
            {
                if ($arResult["WF_NUM"] == 1)
                {
                    $savedXML = '<?xml version="1.0" encoding="' . LANG_CHARSET . '"?>';
                    $savedXML .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';
                    $savedXML .= '<yml_catalog date="' . $arResult["DATE"] . '">';
                    $savedXML .= '<shop>';
                    $savedXML .= '<name>' . $arResult["SITE"] . '</name>';
                    $savedXML .= '<company>' . $arResult["COMPANY"] . '</company>';
                    $savedXML .= '<url>';
                    if ($arParams ["HTTPS_CHECK"] == "Y"):
                        $savedXML .= "https://" . $_SERVER["SERVER_NAME"];
                    else:
                        $savedXML .= "http://" . $_SERVER["SERVER_NAME"];
                    endif;
                    $savedXML .= '</url>';
                    $savedXML .= '<currencies>';
                    if (!empty($arResult["CURRENCIES"])):
                        foreach ($arResult["CURRENCIES"] as $k => $cur):
                            if (!empty($cur) && $cur != 'RUR'):
                                $savedXML .= '<currency id="' . $cur . '"' . (($cur == 'RUB') ? ' rate="1"' : ' rate="' . $arResult["WF_AMOUNTS"][$cur] . '"') . '/>';
                            endif;
                        endforeach;
                    else:
                        $savedXML .= '<currency id="' . $arParams["CURRENCY"] . '" rate="1"/>';
                    endif;
                    $savedXML .= '</currencies>';

                    $savedXML .= '<categories>';
                    foreach ($arResult["CATEGORIES"] as $arCategory):
                        $savedXML .= '<category id="' . $arCategory["ID"] . '"';
                        if ($arCategory["PARENT"])
                            $savedXML .= ' parentId="' . $arCategory['PARENT'] . '"';
                        $savedXML .= '>' . $arCategory["NAME"] . '</category>';
                    endforeach;
                    $savedXML .= '</categories>';

                    if ($arParams["LOCAL_DELIVERY_COST"]):
                        $savedXML .= '<local_delivery_cost>' . $arParams["LOCAL_DELIVERY_COST"] . '</local_delivery_cost>';
                    endif;
                    $savedXML .= '<offers>';
                    foreach ($arResult["OFFER"] as $arOffer):
                        $savedXML .= '<offer type="audiobook" id="' . $arOffer["ID"] . '" available="' . $arOffer["AVAIBLE"] . '">';
                        $savedXML .= '<url>' . $arOffer["URL"] . '</url>';
                        $savedXML .= '<price>' . $arOffer["PRICE"] . '</price>';
                        if (!empty($arOffer["OLD_PRICE"])):
                            $savedXML .= '<oldprice>' . $arOffer["OLD_PRICE"] . '</oldprice>';
                        endif;
                        $savedXML .= '<currencyId>';
                        if (!empty($arOffer["CURRENCY"])):
                            $savedXML .= $arOffer["CURRENCY"];
                        else:
                            $savedXML .= $arParams["CURRENCY"];
                        endif;
                        $savedXML .= '</currencyId>';
                        $savedXML .= '<categoryId>' . $arOffer["CATEGORY"] . '</categoryId>';
                        if ($arOffer["PICTURE"]):
                            $savedXML .= '<picture>' . $arOffer["PICTURE"] . '</picture>';
                        endif;
                        if ($arOffer["STORE_OFFER"] == "true"):
                            $savedXML .= '<store>true</store>';
                        endif;
                        if ($arOffer["STORE_OFFER"] == "false"):
                            $savedXML .= '<store>false</store>';
                        endif;
                        if ($arOffer["STORE_PICKUP"] == "true"):
                            $savedXML .= '<pickup>true</pickup>';
                        endif;
                        if ($arOffer["STORE_PICKUP"] == "false"):
                            $savedXML .= '<pickup>false</pickup>';
                        endif;
                        if ($arParams["LOCAL_DELIVERY_COST"] or is_numeric($arOffer["LOCAL_DELIVERY_COST_OFFER"])):
                            $savedXML .= '<delivery>true</delivery>';
                        endif;
                        if (is_numeric($arOffer["LOCAL_DELIVERY_COST_OFFER"])):
                            $savedXML .= '<local_delivery_cost>' . $arOffer["LOCAL_DELIVERY_COST_OFFER"] . '</local_delivery_cost>';
                        endif;
                        if (!empty($arOffer["PREFIX_PROP"])):
                            $savedXML .= '<typePrefix>' . $arOffer["PREFIX_PROP"] . '</typePrefix>';
                        endif;
                        $savedXML .= '<name>' . $arOffer["MODEL"] . '</name>';
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["AUTHOR"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<author>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["AUTHOR"]]["DISPLAY_VALUE"] . '</author>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["PUBLISHER"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<publisher>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["PUBLISHER"]]["DISPLAY_VALUE"] . '</publisher>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<year>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"] . '</year>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["ISBN"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<ISBN>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["ISBN"]]["DISPLAY_VALUE"] . '</ISBN>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["PERFORMED_BY"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<performed_by>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["PERFORMED_BY"]]["DISPLAY_VALUE"] . '</performed_by>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["STORAGE"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<storage>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["STORAGE"]]["DISPLAY_VALUE"] . '</storage>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["FORMAT"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<format>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["FORMAT"]]["DISPLAY_VALUE"] . '</format>';
                        endif;
                        if (!empty($arOffer["DESCRIPTION"]) or ! empty($arOffer["DOP_PROPS"])):
                            if (!empty($arOffer["DOP_PROPS"])):
                                $savedXML .= '<description>' . $arOffer["DOP_PROPS"] . ". " . $arOffer["DESCRIPTION"] . '</description>';
                            else:
                                $savedXML .= '<description>' . $arOffer["DESCRIPTION"] . '</description>';
                            endif;
                        endif;
                        if ($arOffer["EXPIRY"]):
                            $savedXML .= '<expiry>'.$arOffer["EXPIRY"].'</expiry>';
                        endif;
                        if ($arOffer["WEIGHT"]): 
                            $savedXML .= '<weight>'.$arOffer["WEIGHT"].'</weight>';
                        endif;
                        if ($arOffer["DIMENSIONS"]):
                            $savedXML .= '<dimensions>'.$arOffer["DIMENSIONS"].'</dimensions>';
                        endif;
                        $savedXML .= '</offer>';
                    endforeach;
                    file_put_contents("saved_file.xml", $savedXML, LOCK_EX);
                }
                else
                {
                    foreach ($arResult["OFFER"] as $arOffer):
                        $savedXML .= '<offer type="audiobook" id="' . $arOffer["ID"] . '" available="' . $arOffer["AVAIBLE"] . '">';
                        $savedXML .= '<url>' . $arOffer["URL"] . '</url>';
                        $savedXML .= '<price>' . $arOffer["PRICE"] . '</price>';
                        if (!empty($arOffer["OLD_PRICE"])):
                            $savedXML .= '<oldprice>' . $arOffer["OLD_PRICE"] . '</oldprice>';
                        endif;
                        $savedXML .= '<currencyId>';
                        if (!empty($arOffer["CURRENCY"])):
                            $savedXML .= $arOffer["CURRENCY"];
                        else:
                            $savedXML .= $arParams["CURRENCY"];
                        endif;
                        $savedXML .= '</currencyId>';
                        $savedXML .= '<categoryId>' . $arOffer["CATEGORY"] . '</categoryId>';
                        if ($arOffer["PICTURE"]):
                            $savedXML .= '<picture>' . $arOffer["PICTURE"] . '</picture>';
                        endif;
                        if ($arOffer["STORE_OFFER"] == "true"):
                            $savedXML .= '<store>true</store>';
                        endif;
                        if ($arOffer["STORE_OFFER"] == "false"):
                            $savedXML .= '<store>false</store>';
                        endif;
                        if ($arOffer["STORE_PICKUP"] == "true"):
                            $savedXML .= '<pickup>true</pickup>';
                        endif;
                        if ($arOffer["STORE_PICKUP"] == "false"):
                            $savedXML .= '<pickup>false</pickup>';
                        endif;
                        if ($arParams["LOCAL_DELIVERY_COST"] or is_numeric($arOffer["LOCAL_DELIVERY_COST_OFFER"])):
                            $savedXML .= '<delivery>true</delivery>';
                        endif;
                        if (is_numeric($arOffer["LOCAL_DELIVERY_COST_OFFER"])):
                            $savedXML .= '<local_delivery_cost>' . $arOffer["LOCAL_DELIVERY_COST_OFFER"] . '</local_delivery_cost>';
                        endif;
                        if (!empty($arOffer["PREFIX_PROP"])):
                            $savedXML .= '<typePrefix>' . $arOffer["PREFIX_PROP"] . '</typePrefix>';
                        endif;
                        $savedXML .= '<name>' . $arOffer["MODEL"] . '</name>';
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["AUTHOR"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<author>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["AUTHOR"]]["DISPLAY_VALUE"] . '</author>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["PUBLISHER"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<publisher>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["PUBLISHER"]]["DISPLAY_VALUE"] . '</publisher>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<year>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"] . '</year>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["ISBN"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<ISBN>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["ISBN"]]["DISPLAY_VALUE"] . '</ISBN>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["PERFORMED_BY"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<performed_by>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["PERFORMED_BY"]]["DISPLAY_VALUE"] . '</performed_by>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["STORAGE"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<storage>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["STORAGE"]]["DISPLAY_VALUE"] . '</storage>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["FORMAT"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<format>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["FORMAT"]]["DISPLAY_VALUE"] . '</format>';
                        endif;
                        if (!empty($arOffer["DESCRIPTION"]) or ! empty($arOffer["DOP_PROPS"])):
                            if (!empty($arOffer["DOP_PROPS"])):
                                $savedXML .= '<description>' . $arOffer["DOP_PROPS"] . ". " . $arOffer["DESCRIPTION"] . '</description>';
                            else:
                                $savedXML .= '<description>' . $arOffer["DESCRIPTION"] . '</description>';
                            endif;
                        endif;
                        if ($arOffer["EXPIRY"]):
                            $savedXML .= '<expiry>'.$arOffer["EXPIRY"].'</expiry>';
                        endif;
                        if ($arOffer["WEIGHT"]): 
                            $savedXML .= '<weight>'.$arOffer["WEIGHT"].'</weight>';
                        endif;
                        if ($arOffer["DIMENSIONS"]):
                            $savedXML .= '<dimensions>'.$arOffer["DIMENSIONS"].'</dimensions>';
                        endif;
                        $savedXML .= '</offer>';
                    endforeach;
                    file_put_contents("saved_file.xml", $savedXML, FILE_APPEND | LOCK_EX);
                }
                $arResult["WF_NUM"] ++;
                if ($arResult["WF_NUM"] == 21)
                {
                    $savedXML .= '</offers>
</shop>
    </yml_catalog>';
                    file_put_contents("saved_file.xml", $savedXML, FILE_APPEND | LOCK_EX);
                    echo GetMessage("LOAD_FAIL");
                    $_SESSION["FINISH"] = "Yes";
                }
                else
                {
                    $url = $APPLICATION->GetCurPageParam("WF_PAGE={$arResult["WF_NUM"]}", array("WF_PAGE"));
                    LocalRedirect($url);
                }
            }
            else
            {
                if ($arResult["WF_NUM"] == 1)
                {
                    $savedXML = '<?xml version="1.0" encoding="' . LANG_CHARSET . '"?>';
                    $savedXML .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';
                    $savedXML .= '<yml_catalog date="' . $arResult["DATE"] . '">';
                    $savedXML .= '<shop>';
                    $savedXML .= '<name>' . $arResult["SITE"] . '</name>';
                    $savedXML .= '<company>' . $arResult["COMPANY"] . '</company>';
                    $savedXML .= '<url>';
                    if ($arParams ["HTTPS_CHECK"] == "Y"):
                        $savedXML .= "https://" . $_SERVER["SERVER_NAME"];
                    else:
                        $savedXML .= "http://" . $_SERVER["SERVER_NAME"];
                    endif;
                    $savedXML .= '</url>';
                    $savedXML .= '<currencies>';
                    if (!empty($arResult["CURRENCIES"])):
                        foreach ($arResult["CURRENCIES"] as $k => $cur):
                            if (!empty($cur) && $cur != 'RUR'):
                                $savedXML .= '<currency id="' . $cur . '"' . (($cur == 'RUB') ? ' rate="1"' : ' rate="' . $arResult["WF_AMOUNTS"][$cur] . '"') . '/>';
                            endif;
                        endforeach;
                    else:
                        $savedXML .= '<currency id="' . $arParams["CURRENCY"] . '" rate="1"/>';
                    endif;
                    $savedXML .= '</currencies>';

                    $savedXML .= '<categories>';
                    foreach ($arResult["CATEGORIES"] as $arCategory):
                        $savedXML .= '<category id="' . $arCategory["ID"] . '"';
                        if ($arCategory["PARENT"])
                            $savedXML .= ' parentId="' . $arCategory['PARENT'] . '"';
                        $savedXML .= '>' . $arCategory["NAME"] . '</category>';
                    endforeach;
                    $savedXML .= '</categories>';

                    if ($arParams["LOCAL_DELIVERY_COST"]):
                        $savedXML .= '<local_delivery_cost>' . $arParams["LOCAL_DELIVERY_COST"] . '</local_delivery_cost>';
                    endif;
                    $savedXML .= '<offers>';
                    foreach ($arResult["OFFER"] as $arOffer):
                        $savedXML .= '<offer type="audiobook" id="' . $arOffer["ID"] . '" available="' . $arOffer["AVAIBLE"] . '">';
                        $savedXML .= '<url>' . $arOffer["URL"] . '</url>';
                        $savedXML .= '<price>' . $arOffer["PRICE"] . '</price>';
                        if (!empty($arOffer["OLD_PRICE"])):
                            $savedXML .= '<oldprice>' . $arOffer["OLD_PRICE"] . '</oldprice>';
                        endif;
                        $savedXML .= '<currencyId>';
                        if (!empty($arOffer["CURRENCY"])):
                            $savedXML .= $arOffer["CURRENCY"];
                        else:
                            $savedXML .= $arParams["CURRENCY"];
                        endif;
                        $savedXML .= '</currencyId>';
                        $savedXML .= '<categoryId>' . $arOffer["CATEGORY"] . '</categoryId>';
                        if ($arOffer["PICTURE"]):
                            $savedXML .= '<picture>' . $arOffer["PICTURE"] . '</picture>';
                        endif;
                        if ($arOffer["STORE_OFFER"] == "true"):
                            $savedXML .= '<store>true</store>';
                        endif;
                        if ($arOffer["STORE_OFFER"] == "false"):
                            $savedXML .= '<store>false</store>';
                        endif;
                        if ($arOffer["STORE_PICKUP"] == "true"):
                            $savedXML .= '<pickup>true</pickup>';
                        endif;
                        if ($arOffer["STORE_PICKUP"] == "false"):
                            $savedXML .= '<pickup>false</pickup>';
                        endif;
                        if ($arParams["LOCAL_DELIVERY_COST"] or is_numeric($arOffer["LOCAL_DELIVERY_COST_OFFER"])):
                            $savedXML .= '<delivery>true</delivery>';
                        endif;
                        if (is_numeric($arOffer["LOCAL_DELIVERY_COST_OFFER"])):
                            $savedXML .= '<local_delivery_cost>' . $arOffer["LOCAL_DELIVERY_COST_OFFER"] . '</local_delivery_cost>';
                        endif;
                        if (!empty($arOffer["PREFIX_PROP"])):
                            $savedXML .= '<typePrefix>' . $arOffer["PREFIX_PROP"] . '</typePrefix>';
                        endif;
                        $savedXML .= '<name>' . $arOffer["MODEL"] . '</name>';
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["AUTHOR"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<author>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["AUTHOR"]]["DISPLAY_VALUE"] . '</author>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["PUBLISHER"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<publisher>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["PUBLISHER"]]["DISPLAY_VALUE"] . '</publisher>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<year>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"] . '</year>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["ISBN"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<ISBN>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["ISBN"]]["DISPLAY_VALUE"] . '</ISBN>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["PERFORMED_BY"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<performed_by>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["PERFORMED_BY"]]["DISPLAY_VALUE"] . '</performed_by>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["STORAGE"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<storage>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["STORAGE"]]["DISPLAY_VALUE"] . '</storage>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["FORMAT"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<format>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["FORMAT"]]["DISPLAY_VALUE"] . '</format>';
                        endif;
                        if (!empty($arOffer["DESCRIPTION"]) or ! empty($arOffer["DOP_PROPS"])):
                            if (!empty($arOffer["DOP_PROPS"])):
                                $savedXML .= '<description>' . $arOffer["DOP_PROPS"] . ". " . $arOffer["DESCRIPTION"] . '</description>';
                            else:
                                $savedXML .= '<description>' . $arOffer["DESCRIPTION"] . '</description>';
                            endif;
                        endif;
                        if ($arOffer["EXPIRY"]):
                            $savedXML .= '<expiry>'.$arOffer["EXPIRY"].'</expiry>';
                        endif;
                        if ($arOffer["WEIGHT"]): 
                            $savedXML .= '<weight>'.$arOffer["WEIGHT"].'</weight>';
                        endif;
                        if ($arOffer["DIMENSIONS"]):
                            $savedXML .= '<dimensions>'.$arOffer["DIMENSIONS"].'</dimensions>';
                        endif;
                        $savedXML .= '</offer>';
                    endforeach;
                    $savedXML .= '</offers>';
                    $savedXML .= '</shop>';
                    $savedXML .= '</yml_catalog>';
                    file_put_contents("saved_file.xml", $savedXML, LOCK_EX);
                    echo GetMessage("FILE_SAVED_TO", array("#URL#" => $APPLICATION->GetCurDir() . "saved_file.xml"));
                }
                else
                {
                    foreach ($arResult["OFFER"] as $arOffer):
                        $savedXML .= '<offer type="audiobook" id="' . $arOffer["ID"] . '" available="' . $arOffer["AVAIBLE"] . '">';
                        $savedXML .= '<url>' . $arOffer["URL"] . '</url>';
                        $savedXML .= '<price>' . $arOffer["PRICE"] . '</price>';
                        if (!empty($arOffer["OLD_PRICE"])):
                            $savedXML .= '<oldprice>' . $arOffer["OLD_PRICE"] . '</oldprice>';
                        endif;
                        $savedXML .= '<currencyId>';
                        if (!empty($arOffer["CURRENCY"])):
                            $savedXML .= $arOffer["CURRENCY"];
                        else:
                            $savedXML .= $arParams["CURRENCY"];
                        endif;
                        $savedXML .= '</currencyId>';
                        $savedXML .= '<categoryId>' . $arOffer["CATEGORY"] . '</categoryId>';
                        if ($arOffer["PICTURE"]):
                            $savedXML .= '<picture>' . $arOffer["PICTURE"] . '</picture>';
                        endif;
                        if ($arOffer["STORE_OFFER"] == "true"):
                            $savedXML .= '<store>true</store>';
                        endif;
                        if ($arOffer["STORE_OFFER"] == "false"):
                            $savedXML .= '<store>false</store>';
                        endif;
                        if ($arOffer["STORE_PICKUP"] == "true"):
                            $savedXML .= '<pickup>true</pickup>';
                        endif;
                        if ($arOffer["STORE_PICKUP"] == "false"):
                            $savedXML .= '<pickup>false</pickup>';
                        endif;
                        if ($arParams["LOCAL_DELIVERY_COST"] or is_numeric($arOffer["LOCAL_DELIVERY_COST_OFFER"])):
                            $savedXML .= '<delivery>true</delivery>';
                        endif;
                        if (is_numeric($arOffer["LOCAL_DELIVERY_COST_OFFER"])):
                            $savedXML .= '<local_delivery_cost>' . $arOffer["LOCAL_DELIVERY_COST_OFFER"] . '</local_delivery_cost>';
                        endif;
                        if (!empty($arOffer["PREFIX_PROP"])):
                            $savedXML .= '<typePrefix>' . $arOffer["PREFIX_PROP"] . '</typePrefix>';
                        endif;
                        $savedXML .= '<name>' . $arOffer["MODEL"] . '</name>';
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["AUTHOR"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<author>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["AUTHOR"]]["DISPLAY_VALUE"] . '</author>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["PUBLISHER"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<publisher>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["PUBLISHER"]]["DISPLAY_VALUE"] . '</publisher>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<year>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"] . '</year>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["ISBN"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<ISBN>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["ISBN"]]["DISPLAY_VALUE"] . '</ISBN>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["PERFORMED_BY"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<performed_by>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["PERFORMED_BY"]]["DISPLAY_VALUE"] . '</performed_by>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["STORAGE"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<storage>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["STORAGE"]]["DISPLAY_VALUE"] . '</storage>';
                        endif;
                        if ($arOffer["DISPLAY_PROPERTIES"][$arParams["FORMAT"]]["DISPLAY_VALUE"]):
                            $savedXML .= '<format>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["FORMAT"]]["DISPLAY_VALUE"] . '</format>';
                        endif;
                        if (!empty($arOffer["DESCRIPTION"]) or ! empty($arOffer["DOP_PROPS"])):
                            if (!empty($arOffer["DOP_PROPS"])):
                                $savedXML .= '<description>' . $arOffer["DOP_PROPS"] . ". " . $arOffer["DESCRIPTION"] . '</description>';
                            else:
                                $savedXML .= '<description>' . $arOffer["DESCRIPTION"] . '</description>';
                            endif;
                        endif;
                        if ($arOffer["EXPIRY"]):
                            $savedXML .= '<expiry>'.$arOffer["EXPIRY"].'</expiry>';
                        endif;
                        if ($arOffer["WEIGHT"]): 
                            $savedXML .= '<weight>'.$arOffer["WEIGHT"].'</weight>';
                        endif;
                        if ($arOffer["DIMENSIONS"]):
                            $savedXML .= '<dimensions>'.$arOffer["DIMENSIONS"].'</dimensions>';
                        endif;
                        $savedXML .= '</offer>';
                    endforeach;
                    $savedXML .= '</offers>
      </shop>
    </yml_catalog>';
                    file_put_contents("saved_file.xml", $savedXML, FILE_APPEND | LOCK_EX);
                    echo GetMessage("FILE_SAVED_TO", array("#URL#" => $wf_page . "saved_file.xml"));
                    $_SESSION["FINISH"] = "Yes";
                }
            }
        }
    }
    else
    {
        $savedXML = '<?xml version="1.0" encoding="' . LANG_CHARSET . '"?>';
        $savedXML .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';
        $savedXML .= '<yml_catalog date="' . $arResult["DATE"] . '">';
        $savedXML .= '<shop>';
        $savedXML .= '<name>' . $arResult["SITE"] . '</name>';
        $savedXML .= '<company>' . $arResult["COMPANY"] . '</company>';
        $savedXML .= '<url>';
        if ($arParams ["HTTPS_CHECK"] == "Y"):
            $savedXML .= "https://" . $_SERVER["SERVER_NAME"];
        else:
            $savedXML .= "http://" . $_SERVER["SERVER_NAME"];
        endif;
        $savedXML .= '</url>';
        $savedXML .= '<currencies>';
        if (!empty($arResult["CURRENCIES"])):
            foreach ($arResult["CURRENCIES"] as $k => $cur):
                if (!empty($cur) && $cur != 'RUR'):
                    $savedXML .= '<currency id="' . $cur . '"' . (($cur == 'RUB') ? ' rate="1"' : ' rate="' . $arResult["WF_AMOUNTS"][$cur] . '"') . '/>';
                endif;
            endforeach;
        else:
            $savedXML .= '<currency id="' . $arParams["CURRENCY"] . '" rate="1"/>';
        endif;
        $savedXML .= '</currencies>';

        $savedXML .= '<categories>';
        foreach ($arResult["CATEGORIES"] as $arCategory):
            $savedXML .= '<category id="' . $arCategory["ID"] . '"';
            if ($arCategory["PARENT"])
                $savedXML .= ' parentId="' . $arCategory['PARENT'] . '"';
            $savedXML .= '>' . $arCategory["NAME"] . '</category>';
        endforeach;
        $savedXML .= '</categories>';

        if ($arParams["LOCAL_DELIVERY_COST"]):
            $savedXML .= '<local_delivery_cost>' . $arParams["LOCAL_DELIVERY_COST"] . '</local_delivery_cost>';
        endif;
        $savedXML .= '<offers>';
        foreach ($arResult["OFFER"] as $arOffer):
            $savedXML .= '<offer type="audiobook" id="' . $arOffer["ID"] . '" available="' . $arOffer["AVAIBLE"] . '">';
            $savedXML .= '<url>' . $arOffer["URL"] . '</url>';
            $savedXML .= '<price>' . $arOffer["PRICE"] . '</price>';
            if (!empty($arOffer["OLD_PRICE"])):
                $savedXML .= '<oldprice>' . $arOffer["OLD_PRICE"] . '</oldprice>';
            endif;
            $savedXML .= '<currencyId>';
            if (!empty($arOffer["CURRENCY"])):
                $savedXML .= $arOffer["CURRENCY"];
            else:
                $savedXML .= $arParams["CURRENCY"];
            endif;
            $savedXML .= '</currencyId>';
            $savedXML .= '<categoryId>' . $arOffer["CATEGORY"] . '</categoryId>';
            if ($arOffer["PICTURE"]):
                $savedXML .= '<picture>' . $arOffer["PICTURE"] . '</picture>';
            endif;
            if ($arOffer["STORE_OFFER"] == "true"):
                $savedXML .= '<store>true</store>';
            endif;
            if ($arOffer["STORE_OFFER"] == "false"):
                $savedXML .= '<store>false</store>';
            endif;
            if ($arOffer["STORE_PICKUP"] == "true"):
                $savedXML .= '<pickup>true</pickup>';
            endif;
            if ($arOffer["STORE_PICKUP"] == "false"):
                $savedXML .= '<pickup>false</pickup>';
            endif;
            if ($arParams["LOCAL_DELIVERY_COST"] or is_numeric($arOffer["LOCAL_DELIVERY_COST_OFFER"])):
                $savedXML .= '<delivery>true</delivery>';
            endif;
            if (is_numeric($arOffer["LOCAL_DELIVERY_COST_OFFER"])):
                $savedXML .= '<local_delivery_cost>' . $arOffer["LOCAL_DELIVERY_COST_OFFER"] . '</local_delivery_cost>';
            endif;
            if (!empty($arOffer["PREFIX_PROP"])):
                            $savedXML .= '<typePrefix>' . $arOffer["PREFIX_PROP"] . '</typePrefix>';
                        endif;
            $savedXML .= '<name>' . $arOffer["MODEL"] . '</name>';
            if ($arOffer["DISPLAY_PROPERTIES"][$arParams["AUTHOR"]]["DISPLAY_VALUE"]):
                $savedXML .= '<author>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["AUTHOR"]]["DISPLAY_VALUE"] . '</author>';
            endif;
            if ($arOffer["DISPLAY_PROPERTIES"][$arParams["PUBLISHER"]]["DISPLAY_VALUE"]):
                $savedXML .= '<publisher>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["PUBLISHER"]]["DISPLAY_VALUE"] . '</publisher>';
            endif;
            if ($arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"]):
                $savedXML .= '<year>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"] . '</year>';
            endif;
            if ($arOffer["DISPLAY_PROPERTIES"][$arParams["ISBN"]]["DISPLAY_VALUE"]):
                $savedXML .= '<ISBN>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["ISBN"]]["DISPLAY_VALUE"] . '</ISBN>';
            endif;
            if ($arOffer["DISPLAY_PROPERTIES"][$arParams["PERFORMED_BY"]]["DISPLAY_VALUE"]):
                $savedXML .= '<performed_by>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["PERFORMED_BY"]]["DISPLAY_VALUE"] . '</performed_by>';
            endif;
            if ($arOffer["DISPLAY_PROPERTIES"][$arParams["STORAGE"]]["DISPLAY_VALUE"]):
                $savedXML .= '<storage>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["STORAGE"]]["DISPLAY_VALUE"] . '</storage>';
            endif;
            if ($arOffer["DISPLAY_PROPERTIES"][$arParams["FORMAT"]]["DISPLAY_VALUE"]):
                $savedXML .= '<format>' . $arOffer["DISPLAY_PROPERTIES"][$arParams["FORMAT"]]["DISPLAY_VALUE"] . '</format>';
            endif;
            if (!empty($arOffer["DESCRIPTION"]) or ! empty($arOffer["DOP_PROPS"])):
                if (!empty($arOffer["DOP_PROPS"])):
                    $savedXML .= '<description>' . $arOffer["DOP_PROPS"] . ". " . $arOffer["DESCRIPTION"] . '</description>';
                else:
                    $savedXML .= '<description>' . $arOffer["DESCRIPTION"] . '</description>';
                endif;
            endif;
            if ($arOffer["EXPIRY"]):
                $savedXML .= '<expiry>'.$arOffer["EXPIRY"].'</expiry>';
            endif;
            if ($arOffer["WEIGHT"]): 
                $savedXML .= '<weight>'.$arOffer["WEIGHT"].'</weight>';
            endif;
            if ($arOffer["DIMENSIONS"]):
                $savedXML .= '<dimensions>'.$arOffer["DIMENSIONS"].'</dimensions>';
            endif;
            $savedXML .= '</offer>';
        endforeach;
        $savedXML .= '</offers>';
        $savedXML .= '</shop>';
        $savedXML .= '</yml_catalog>';
        file_put_contents("saved_file.xml", $savedXML, LOCK_EX);
        echo GetMessage("FILE_SAVED_TO", array("#URL#" => $APPLICATION->GetCurDir() . "saved_file.xml"));
    }
endif;