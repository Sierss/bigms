<?
use Bitrix\Sale\Location;
use Bitrix\Sale\Location\Admin\TypeHelper;


/*AddEventHandler("sale", "OnOrderSave", "OnOrderSaveHandler");
function OnOrderSaveHandler($ID, $arFields, $arOrder, $isNew){*/


AddEventHandler("sale", "OnBeforeOrderUpdate", "OnBeforeOrderUpdateHandler");
function OnBeforeOrderUpdateHandler($ID, $arFields){

	if($arFields["STATUS_ID"] == "F"){
		/*if(!$isNew)
			return;*/

		CModule::IncludeModule("sale");
		CModule::IncludeModule("iblock");

		$first_elem = array_shift($arFields["BASKET_ITEMS"]);
		$res = CIBlockElement::GetByID($first_elem["PRODUCT_ID"]); 
		if($ar_res = $res->GetNext()){  
			$im = isset($ar_res["PREVIEW_PICTURE"]) ? $ar_res["PREVIEW_PICTURE"] :$ar_res["DETAIL_PICTURE"];
			$file = CFile::ResizeImageGet($im, array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_PROPORTIONAL, true);                
			$im = $file['src'];
		} 
		
		if(count($arFields["BASKET_ITEMS"]) == 0)
			$count = count($arFields["BASKET_ITEMS"]) + 1;
		else
			$count = count($arFields["BASKET_ITEMS"]);
		
		function plural_form($number, $after) {
			$cases = array (2, 0, 1, 1, 1, 2);
			return $after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
		}
		$text_tov = plural_form(count($arFields["BASKET_ITEMS"]), array('товар', 'товара', 'товаров' ));
		
		
		$orderList .= '<tr>
			  <td colspan="3" style="background: #fff; vertical-align: center; padding: 6px 20px; border-right: 1px solid #f2f7fd; text-align: center;">
				<img src="'.$_SERVER["HTTP_ORIGIN"].$im.'" alt="'.$first_elem["NAME"].'" />
			  </td>
			  <td colspan="7" style="background: #fbfdfe; vertical-align: top; padding: 40px 20px 20px 40px;">
				<a href="'.$_SERVER["HTTP_ORIGIN"].$first_elem["DETAIL_PAGE_URL"].'" style="font-family: Arial, sans serif; font-size: 16px; color: #006db8; display: block; line-height: 22px;">'.$first_elem["NAME"].'</a>
				<p style="font-family: Arial, sans serif; font-size: 16px; font-weight: bold;">'.($first_elem["PRICE"] * $first_elem["QUANTITY"]).' руб.</p>
			  </td>
			</tr>
			<tr>
			  <td colspan="10">
				<br>';
		if(count($arFields["BASKET_ITEMS"]) > 0){
			   $orderList .=  '<p href="#" style="font-family: Arial, sans serif; font-size: 14px; color: #636568; text-decoration: none;">И еще '.count($arFields["BASKET_ITEMS"]).' '.$text_tov.'</p>';
		}

		$orderList .= '<br>
				<br>
				<p style="font-family: Arial, sans serif; font-size: 16px; line-height:27px;">Расскажите, остались ли вы довольны уровнем сервиса?
				Если сервис не был на высоком уровне – разберемся, чтобы не повторять
				в будущем ошибок, а если все прошло хорошо – менеджер получит
				небольшой бонус.
				<br><br>
				Оцените впечатления от покупки по 10-ти бальной шкале, кликнув
				по соответствующей звездочке: </p>
				<br>

			  </td>
			</tr>';
		
		$starList .= '<td><a href="http://www.bigms.ru/reports/'.$arFields["ID"].'/1/"><img src="http://www.bigms.ru/upload/star/1.png" /></a></td>
			  <td><a href="http://www.bigms.ru/reports/'.$arFields["ID"].'/2/"><img src="http://www.bigms.ru/upload/star/2.png" /></a></td>
			  <td><a href="http://www.bigms.ru/reports/'.$arFields["ID"].'/3/"><img src="http://www.bigms.ru/upload/star/3.png" /></a></td>
			  <td><a href="http://www.bigms.ru/reports/'.$arFields["ID"].'/4/"><img src="http://www.bigms.ru/upload/star/4.png" /></a></td>
			  <td><a href="http://www.bigms.ru/reports/'.$arFields["ID"].'/5/"><img src="http://www.bigms.ru/upload/star/5.png" /></a></td>
			  <td><a href="http://www.bigms.ru/reports/'.$arFields["ID"].'/6/"><img src="http://www.bigms.ru/upload/star/6.png" /></a></td>
			  <td><a href="https://market.yandex.ru/shop/281223/reviews/add?hid&retpath=https%3A%2F%2Fmarket.yandex.ru%2Fshop%2F281223%2Freviews&track=rev_mc_write"><img src="http://www.bigms.ru/upload/star/7.png" /></a></td>
			  <td><a href="https://market.yandex.ru/shop/281223/reviews/add?hid&retpath=https%3A%2F%2Fmarket.yandex.ru%2Fshop%2F281223%2Freviews&track=rev_mc_write"><img src="http://www.bigms.ru/upload/star/8.png" /></a></td>
			  <td><a href="https://market.yandex.ru/shop/281223/reviews/add?hid&retpath=https%3A%2F%2Fmarket.yandex.ru%2Fshop%2F281223%2Freviews&track=rev_mc_write"><img src="http://www.bigms.ru/upload/star/9.png" /></a></td>
			  <td><a href="https://market.yandex.ru/shop/281223/reviews/add?hid&retpath=https%3A%2F%2Fmarket.yandex.ru%2Fshop%2F281223%2Freviews&track=rev_mc_write"><img src="http://www.bigms.ru/upload/star/10.png" /></a></td>';
		
		$arEventFields = array(
			"NAME"  => (isset($arFields["ORDER_PROP"][1]) ? $arFields["ORDER_PROP"][1] : $arFields["PROFILE_NAME"]),
			"EMAIL"  => $arFields["USER_EMAIL"],
			"ORDER_LIST" => $orderList,
			"STAR_LIST" => $starList

		);
		CEvent::Send("SEND_REQ_ORDER", "s1", $arEventFields);
	
	}
}


function custom_mail($to, $subject, $message, $addh = "", $addp = "")
{
    require_once __DIR__ . '/mail/class.phpmailer.php';

    try {

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        // $mail->IsHTML(true);

    // telling the class to use SMTP
        $mail->IsSMTP();

    // SMTP server
        $mail->Host = "smtp.yandex.ru";

    // set the SMTP port for the GMAIL
        //$mail->Port = 25;
		$mail->Port = 465;

        $mail->SMTPAuth   = true;
		
		$mail->SMTPSecure   = 'ssl';

    // SMTP account username
		$mail->Username = "bigms.ru";

    // SMTP account password
        $mail->Password = "MBorj83bn";

    // $mail->SMTPDebug = 2;

	$mail->SetFrom('bigms.ru@yandex.ru', 'bigms.ru');
        $mail->AddAddress($to);
        $mail->Body = $message;
        $mail->Subject = $subject;
		
		//$mail->AddBCC('nevkaa@yandex.ru');


    //$addh = $mail->HeaderLine('To', $mail->EncodeHeader($mail->SecureHeader($to))).$addh;
    //$addh = $mail->HeaderLine('Subject',
    //$mail->EncodeHeader($mail->SecureHeader($subject))).$addh;
    //$mail->Header = $addh."\n";
    //$mail->AddCustomHeader($addh);


        $arr = explode("\n", $addh);

        if (is_array($arr)){
            foreach ($arr as $key => $value) {
                $arrr = explode(":", $value);
                $addh = $mail->HeaderLine($arrr[0], $arrr[1]);

                if($arrr[0] == 'Content-Type') $mail->ContentType = $arrr[1];
            }
        }


    //Debug
        
        //$file = fopen(__DIR__.'/log.txt', 'w+');
        //$string = 'To: '.print_r($to, true).PHP_EOL;
        //$string .= 'Subject: '.print_r($subject, true).PHP_EOL;
        //$string .= 'Message: '.print_r($message, true).PHP_EOL;
        //$string .= 'Additional headers: '.print_r($addh, true).PHP_EOL;
        //$string .= 'Additional props: '.print_r($addp, true).PHP_EOL;
        //
        //fwrite($file, $string);
        //fclose($file);
        

        $status = $mail->Send();

    } catch (phpmailerException $e) {
        echo $e->errorMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    return $status;
}


// Для обмена с 1С
global $CML2_CURRENCY;
$CML2_CURRENCY['643'] = 'RUB';
$CML2_CURRENCY['руб'] = 'RUB'; 
$CML2_CURRENCY['руб.'] = 'RUB'; 
$CML2_CURRENCY['Рубль'] = 'RUB'; 


// Скрытое поле от Спам-ботов ВЕБ-ФОРМЫ
AddEventHandler('form', 'onBeforeResultAdd', 'my_onBeforeResultAdd');
function my_onBeforeResultAdd($WEB_FORM_ID, $arFields, $arrVALUES)
{
	global $APPLICATION;
	$arFormID = array(1, 2, 3);
	
	if (in_array($WEB_FORM_ID, $arFormID)) {
		
		// Если заполнено скрытое поле
		if(!empty($arrVALUES['form_text__1'])){
			return $APPLICATION->ThrowException('Заполнено скрытое поле (спам-бот)');
		}
	}
}

AddEventHandler('main', 'OnEpilog', '_Check404Error',1);
function _Check404Error()
{
   if (defined("ERROR_404") && ERROR_404=="Y")
   {
      global $APPLICATION;
      $APPLICATION->RestartBuffer();
	   include $_SERVER['DOCUMENT_ROOT']."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php";
      require ($_SERVER["DOCUMENT_ROOT"]."/404.php");
	   include $_SERVER['DOCUMENT_ROOT']."/bitrix/templates/".SITE_TEMPLATE_ID."/footer.php"; 
   }
}


//Импорт товаров из 1С (обработка свойств)
//AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("EXT1C", "ATTRIBUTES2PROP"));
//AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("EXT1C", "ATTRIBUTES2PROP"));
/* AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("EXT1C", "ATTRIBUTES2PROP"));
AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("EXT1C", "ATTRIBUTES2PROP"));
class EXT1C
{
    function ATTRIBUTES2PROP(&$arFields)
    {
		if ((@$_REQUEST['type']=='catalog') && (@$_REQUEST['mode']=='import'))//выгрузка из 1С?
        {
			//echo "Работает";
			//echo '<pre>';
			//print_r($arFields);
			//echo '</pre>';
			//die();

			$el = new CIBlockElement;
			$arLoadProductArray = Array(
				"IBLOCK_SECTION_ID" => $arFields["IBLOCK_SECTION"][0]
			);
			$PRODUCT_ID = $arFields["ID"];
			$res = $el->Update($PRODUCT_ID, $arLoadProductArray);
        }
    }
} */

/* function getNumEnding($number, $endingArray)
{
    $number = $number % 100;
    if ($number>=11 && $number<=19)
    {
        $ending=$endingArray[2];
    } else  {
        $i = $number % 10;
        switch ($i) {
            case (1): $ending = $endingArray[0]; break;
            case (2): case (3): case (4): $ending = $endingArray[1]; break;
            default: $ending=$endingArray[2]; }
    }
    return $ending;
} */








?>