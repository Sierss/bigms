<?php
use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Bitrix\Sale\Internals\ShipmentTable;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

$saleModulePermissions = $APPLICATION->GetGroupRight("sale");
if ($saleModulePermissions < "U")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

Loader::includeModule('sale');
Loader::includeModule('currency');
IncludeModuleLangFile(__FILE__);
global $DB;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sale/prolog.php");

$tableId = "b_sale_order_shipment";
$curPage = Application::getInstance()->getContext()->getCurrent()->getRequest()->getRequestUri();
$lang    = Application::getInstance()->getContext()->getLanguage();
$siteId  = Application::getInstance()->getContext()->getSite();
$errors = '';
$sAdmin = new CAdminSorting($tableId, "ORDER_ID", "DESC");
$lAdmin = new CAdminList($tableId, $sAdmin);

$filter = array(
	'filter_order_id_from',
	'filter_order_id_to',
	'filter_allow_delivery',
	'filter_deducted',
	'filter_delivery_id',
	'filter_delivery_doc_num',
	'filter_price_delivery_from',
	'filter_price_delivery_to',
	'filter_company_id',
	'filter_date_deducted_from',
	'filter_date_deducted_to',
	'filter_status',
	'filter_account_num',
	'filter_shipment_id_from',
	'filter_shipment_id_to',
	'filter_user_id',
	'filter_user_login',
	'filter_user_email'
);

$lAdmin->InitFilter($filter);

$arFilter = array();

$filter_order_id_from = intval($filter_order_id_from);
$filter_order_id_to = intval($filter_order_id_to);

if (strlen($filter_allow_delivery) > 0 && $filter_allow_delivery != 'NOT_REF')
	$arFilter['ALLOW_DELIVERY'] = $filter_allow_delivery;

if (strlen($filter_deducted) > 0 && $filter_deducted != 'NOT_REF')
	$arFilter['DEDUCTED'] = $filter_deducted;

if (intval($filter_price_delivery_from) > 0)
	$arFilter['>=PRICE_DELIVERY'] = $filter_price_delivery_from;
if (intval($filter_price_delivery_to) > 0)
	$arFilter['<=PRICE_DELIVERY'] = $filter_price_delivery_to;

if (strlen($filter_delivery_doc_num) > 0)
	$arFilter['DELIVERY_DOC_NUM'] = $filter_deducted;

if ($filter_order_id_from > 0)
	$arFilter['>=ORDER_ID'] = $filter_order_id_from;
if ($filter_order_id_to > 0)
	$arFilter['<=ORDER_ID'] = $filter_order_id_to;

if ($filter_shipment_id_from > 0)
	$arFilter['>=ID'] = $filter_shipment_id_from;
if ($filter_shipment_id_to > 0)
	$arFilter['<=ID'] = $filter_shipment_id_to;

if (strlen($filter_company_id) > 0 && $filter_company_id != 'NOT_REF')
	$arFilter['COMPANY_ID'] = intval($filter_company_id);

if (strlen($filter_date_deducted_from) > 0)
	$arFilter[">=DATE_DEDUCTED"] = trim($filter_date_deducted_from);

$serviceList = array();
$filterServiceList = array();

$dbRes = \Bitrix\Sale\Delivery\Services\Table::getList(array('select' => array('ID', 'NAME', 'PARENT_ID', 'CLASS_NAME'), 'order' => array('SORT' => 'ASC')));
while ($service = $dbRes->fetch())
{
	$serviceList[$service['ID']] = $service;
	if ($service['PARENT_ID'] > 0)
		$filterServiceList[$service['PARENT_ID']][] = $service['ID'];
}

if (is_array($filter_delivery_id) && count($filter_delivery_id) > 0 && $filter_delivery_id[0] != 'NOT_REF')
{
	$arFilter['DELIVERY_ID'] = $filter_delivery_id;
	foreach ($filter_delivery_id as $deliveryId)
	{
		if (array_key_exists($deliveryId, $filterServiceList))
			$arFilter['DELIVERY_ID'] = array_merge($arFilter['DELIVERY_ID'], $filterServiceList[$deliveryId]);
	}
}

if (strlen($filter_date_deducted_to) > 0)
{
	if ($arDate = ParseDateTime($filter_date_deducted_to, CSite::GetDateFormat("FULL", $siteId)))
	{
		if (strlen($filter_date_deducted_to) < 11)
		{
			$arDate["HH"] = 23;
			$arDate["MI"] = 59;
			$arDate["SS"] = 59;
		}

		$filter_date_deducted_to = date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL", $siteId)), mktime($arDate["HH"], $arDate["MI"], $arDate["SS"], $arDate["MM"], $arDate["DD"], $arDate["YYYY"]));
		$arFilter["<=DATE_DEDUCTED"] = $filter_date_deducted_to;
	}
	else
	{
		$filter_date_deducted_to = "";
	}
}

if (isset($filter_status) && is_array($filter_status) && count($filter_status) > 0)
{
	for ($i = 0, $cnt = count($filter_status); $i < $cnt; $i++)
	{
		$filter_status[$i] = trim($filter_status[$i]);
		if (strlen($filter_status[$i]) > 0)
			$arFilter["STATUS_ID"][] = $filter_status[$i];
	}
}

if (strlen($filter_account_num) > 0)
	$arFilter['ORDER.ACCOUNT_NUMBER'] = $filter_account_num;

if (strlen($filter_user_login)>0)
	$arFilter["ORDER.USER.LOGIN"] = trim($filter_user_login);
if (strlen($filter_user_email)>0)
	$arFilter["ORDER.USER.EMAIL"] = trim($filter_user_email);
if (IntVal($filter_user_id)>0)
	$arFilter["ORDER.USER_ID"] = IntVal($filter_user_id);

$allowedStatusesView = \Bitrix\Sale\DeliveryStatus::getStatusesUserCanDoOperations($USER->GetID(), array('view'));

if($saleModulePermissions < "W")
	$arFilter["=STATUS_ID"] = $allowedStatusesView;

if($arID = $lAdmin->GroupAction())
{
	$shipments = array();

	$select = array(
		'ID', 'ORDER_ID'
	);
	$filter['=STATUS.Bitrix\Sale\Internals\StatusLangTable:STATUS.LID'] = $lang;
	$filter['=SYSTEM'] = 'N';

	if($_REQUEST['action_target'] != 'selected')
		$filter['ID'] = $_REQUEST['ID'];

	$params = array(
		'select' => $select,
		'filter' => $filter,
		'limit' => 1000
	);

	$result = ShipmentTable::getList($params);

	while ($arResult = $result->fetch())
	{
		if (!isset($shipments[$arResult['ORDER_ID']]))
			$shipments[$arResult['ORDER_ID']] = array();
		$shipments[$arResult['ORDER_ID']][] = $arResult['ID'];
	}
	

	foreach ($shipments as $orderId => $ids)
	{
		$isDeleted = false;
		/** @var \Bitrix\Sale\Order $currentOrder */
		$currentOrder = \Bitrix\Sale\Order::load($orderId);
		if (!$currentOrder)
			continue;

		/** @var \Bitrix\Sale\ShipmentCollection $shipmentCollection */
		$shipmentCollection = $currentOrder->getShipmentCollection();

		foreach ($ids as $id)
		{
			if (strlen($id) <= 0)
				continue;

			/** @var \Bitrix\Sale\Shipment $shipment */
			$shipment = $shipmentCollection->getItemById($id);
			if (!$shipment)
				continue;

			switch ($_REQUEST['action'])
			{
				case "delete":
					@set_time_limit(0);

					$res = $shipment->delete();
					if ($res->isSuccess())
						$isDeleted = true;
					else
						$lAdmin->AddGroupError(implode('\n', $res->getErrorMessages()));
					break;
			}
		}
		if ($isDeleted)
		{
			$res = $currentOrder->save();
			if (!$res->isSuccess())
				$lAdmin->AddGroupError(implode('\n', $res->getErrorMessages()));
		}
	}
}


$headers = array(
	array("id" => "DELIVERY_DOC_DATE", "content" => GetMessage("SALE_ORDER_DELIVERY_DOC_DATE"), "sort"=> "DELIVERY_DOC_DATE", "default" => true),
	array("id" => "ID", "content" => "ID", "sort" => "ID", "default" => true),
	array("id" => "ORDER_ID", "content" => GetMessage("SALE_ORDER_ID"), "sort" => "ORDER_ID", "default" => true),
	array("id" => "ACCOUNT_NUMBER", "content" => GetMessage("SALE_ORDER_ACCOUNT_NUMBER"), "sort" => "ORDER.ACCOUNT_NUMBER", "default" => false),
	array("id" => "ORDER_USER_NAME", "content" => GetMessage("SALE_ORDER_USER_NAME"), "sort" => "ORDER_USER_NAME", "default" => true),
	array("id" => "ALLOW_DELIVERY", "content" => GetMessage("SALE_ORDER_ALLOW_DELIVERY"), "sort" => "ALLOW_DELIVERY", "default" => true),
	array("id" => "STATUS", "content" => GetMessage("SALE_ORDER_STATUS"), "sort" => 'STATUS.ID', "default" => true),
	array("id" => "DEDUCTED", "content" => GetMessage("SALE_ORDER_DEDUCTED"), "sort" => "DEDUCTED", "default" => true),
	array("id" => "DELIVERY_NAME", "content" => GetMessage("SALE_ORDER_DELIVERY_NAME"), "sort"=> "DELIVERY_NAME", "default" => true),
	array("id" => "PRICE_DELIVERY", "content" => GetMessage("SALE_ORDER_PRICE_DELIVERY"), "sort" => "PRICE_DELIVERY", "default" => true),
	array("id" => "COMPANY_BY", "content" => GetMessage("SALE_ORDER_COMPANY_ID"), "sort"=> "COMPANY_BY.NAME", "default" => true),
	array("id" => "DELIVERY_DOC_NUM", "content" => GetMessage("SALE_ORDER_DELIVERY_DOC_NUM"), "sort"=> "DELIVERY_DOC_NUM", "default" => true),
	array("id" => "RESPONSIBLE_BY", "content" => GetMessage("SALE_ORDER_DELIVERY_RESPONSIBLE_ID"), "sort"=> "", "default" => true),
	array("id" => "REASON_UNDO_DEDUCTED", "content" => GetMessage("SALE_ORDER_REASON_UNDO_DEDUCTED"), "default" => false),
	array("id" => "TRACKING_NUMBER", "content" => GetMessage("SALE_ORDER_TRACKING_NUMBER"), "sort"=> "TRACKING_NUMBER", "default" => false),
	array("id" => "XML_ID", "content" => "XML_ID", "sort"=> "XML_ID", "default" => false),
	array("id" => "PARAMETERS", "content" => GetMessage("SALE_ORDER_PARAMETERS"), "default" => false),
	array("id" => "CANCELED", "content" => GetMessage("SALE_ORDER_CANCELED"), "sort"=> "CANCELED", "default" => false),
	array("id" => "REASON_CANCELED", "content" => GetMessage("SALE_ORDER_REASON_CANCELED"), "default" => false),
	array("id" => "MARKED", "content" => GetMessage("SALE_ORDER_MARKED"), "sort"=> "MARKED", "default" => false),
	array("id" => "REASON_MARKED_ID", "content" => GetMessage("SALE_ORDER_REASON_MARKED_ID"), "default" => false),
);

$select = array(
	'*',
	'STATUS_NAME' => 'STATUS.Bitrix\Sale\Internals\StatusLangTable:STATUS.NAME',
	'ORDER.CURRENCY',
	'ORDER.ACCOUNT_NUMBER',
	'COMPANY_BY.NAME',
	'EMP_DEDUCTED_BY_NAME' => 'EMP_DEDUCTED_BY.NAME',
	'EMP_DEDUCTED_BY_LAST_NAME' => 'EMP_DEDUCTED_BY.LAST_NAME',
	'EMP_ALLOW_DELIVERY_BY_NAME' => 'EMP_ALLOW_DELIVERY_BY.NAME',
	'EMP_ALLOW_DELIVERY_BY_LAST_NAME' => 'EMP_ALLOW_DELIVERY_BY.LAST_NAME',
	'EMP_MARKED_BY_BY_NAME' => 'EMP_MARKED_BY.NAME',
	'EMP_MARKED_BY_LAST_NAME' => 'EMP_MARKED_BY.LAST_NAME',
	'ORDER_USER_NAME' => 'ORDER.USER.NAME',
	'ORDER_USER_LAST_NAME' => 'ORDER.USER.LAST_NAME',
	'ORDER_USER_ID' => 'ORDER.USER_ID',
	'RESPONSIBLE_BY_LAST_NAME' => 'RESPONSIBLE_BY.LAST_NAME',
	'RESPONSIBLE_BY_NAME' => 'RESPONSIBLE_BY.NAME'
);
$arFilter['=STATUS.Bitrix\Sale\Internals\StatusLangTable:STATUS.LID'] = $lang;
$arFilter['!=SYSTEM'] = 'Y';

$params = array(
	'select' => $select,
	'filter' => $arFilter,
	'order'  => array($by => $order),
);

$usePageNavigation = true;
$navyParams = array();

$navyParams = CDBResult::GetNavParams(CAdminResult::GetNavSize($tableId));
if ($navyParams['SHOW_ALL'])
{
	$usePageNavigation = false;
}
else
{
	$navyParams['PAGEN'] = (int)$navyParams['PAGEN'];
	$navyParams['SIZEN'] = (int)$navyParams['SIZEN'];
}



if ($usePageNavigation)
{
	$params['limit'] = $navyParams['SIZEN'];
	$params['offset'] = $navyParams['SIZEN']*($navyParams['PAGEN']-1);
}

$totalPages = 0;

if ($usePageNavigation)
{
	$countQuery = new \Bitrix\Main\Entity\Query(ShipmentTable::getEntity());
	$countQuery->addSelect(new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(1)'));
	$countQuery->setFilter($params['filter']);
	$totalCount = $countQuery->setLimit(null)->setOffset(null)->exec()->fetch();
	unset($countQuery);
	$totalCount = (int)$totalCount['CNT'];

	if ($totalCount > 0)
	{
		$totalPages = ceil($totalCount/$navyParams['SIZEN']);

		if ($navyParams['PAGEN'] > $totalPages)
			$navyParams['PAGEN'] = $totalPages;

		$params['limit'] = $navyParams['SIZEN'];
		$params['offset'] = $navyParams['SIZEN']*($navyParams['PAGEN']-1);
	}
	else
	{
		$navyParams['PAGEN'] = 1;
		$params['limit'] = $navyParams['SIZEN'];
		$params['offset'] = 0;
	}
}

$dbResultList = new CAdminResult(ShipmentTable::getList($params), $tableId);

if ($usePageNavigation)
{
	$dbResultList->NavStart($params['limit'], $navyParams['SHOW_ALL'], $navyParams['PAGEN']);
	$dbResultList->NavRecordCount = $totalCount;
	$dbResultList->NavPageCount = $totalPages;
	$dbResultList->NavPageNomer = $navyParams['PAGEN'];
}
else
{
	$dbResultList->NavStart();
}


//$dbResultList = new CAdminResult($shipments, $tableId);
//$dbResultList->NavStart();
$lAdmin->NavText($dbResultList->GetNavPrint(GetMessage("group_admin_nav")));

$lAdmin->AddHeaders($headers);

$allSelectedFields = array(
	"ORDER_ID" => false,
	"PAID" => false,
	"DATE_PAID" => false
);

$visibleHeaders = $lAdmin->GetVisibleHeaderColumns();
$allSelectedFields = array_merge($allSelectedFields, array_fill_keys($visibleHeaders, true));

while ($shipment = $dbResultList->Fetch())
{
	$row =& $lAdmin->AddRow($shipment['ID'], $shipment);
	$row->AddField("ID", "<a href=\"sale_order_shipment_edit.php?order_id=".$shipment['ORDER_ID']."&shipment_id=".$shipment['ID']."&lang=".$lang.GetFilterParams("filter_")."\">".$shipment['ID']."</a>");
	$row->AddField("ORDER_ID", "<a href=\"sale_order_edit.php?ID=".$shipment['ORDER_ID']."&lang=".$lang.GetFilterParams("filter_")."\">".$shipment['ORDER_ID']."</a>");
	$row->AddField("DELIVERY_NAME", "<a href=\"sale_delivery_service_edit.php?ID=".$shipment['DELIVERY_ID']."&lang=".$lang.GetFilterParams("filter_")."\">".htmlspecialcharsbx($shipment['DELIVERY_NAME'])."</a>");
	$row->AddField("ACCOUNT_NUMBER", htmlspecialcharsbx($shipment['SALE_INTERNALS_SHIPMENT_ORDER_ACCOUNT_NUMBER']));
	$row->AddField("ALLOW_DELIVERY", ($shipment["ALLOW_DELIVERY"] == "Y") ? GetMessage("SHIPMENT_ORDER_YES") : GetMessage("SHIPMENT_ORDER_NO"));
	$row->AddField("COMPANY_BY", "<a href=\"sale_company_edit.php?ID=".$shipment['COMPANY_ID']."&lang=".$lang.GetFilterParams("filter_")."\">".htmlspecialcharsbx($shipment['SALE_INTERNALS_SHIPMENT_COMPANY_BY_NAME'])."</a>");
	$row->AddField("ORDER_USER_NAME", "<a href='/bitrix/admin/user_edit.php?ID=".$shipment['ORDER_USER_ID']."&lang=".$lang."'>".htmlspecialcharsbx($shipment['ORDER_USER_NAME'])." ".htmlspecialcharsbx($shipment['ORDER_USER_LAST_NAME'])."</a>");
	$row->AddField("PRICE_DELIVERY", \CCurrencyLang::CurrencyFormat($shipment['PRICE_DELIVERY'], $shipment['SALE_INTERNALS_SHIPMENT_ORDER_CURRENCY']));

	$row->AddField("DEDUCTED", (($shipment["DEDUCTED"] == "Y") ? GetMessage("SHIPMENT_ORDER_YES") : GetMessage("SHIPMENT_ORDER_NO"))."<br><a href=\"user_edit.php?ID=".$shipment['EMP_DEDUCTED_ID']."\">".htmlspecialcharsbx($shipment['SALE_INTERNALS_SHIPMENT_EMP_DEDUCTED_BY_LAST_NAME'])." ".htmlspecialcharsbx($shipment['SALE_INTERNALS_SHIPMENT_EMP_DEDUCTED_BY_NAME'])."</a><br>".htmlspecialcharsbx($shipment['DATE_DEDUCTED']));

	$row->AddField("RESPONSIBLE_BY", "<a href=\"user_edit.php?ID=".$shipment['RESPONSIBLE_ID']."\">".htmlspecialcharsbx($shipment['RESPONSIBLE_BY_NAME'])." ".htmlspecialcharsbx($shipment['RESPONSIBLE_BY_LAST_NAME'])."</a>");

	$row->AddField("ALLOW_DELIVERY", (($shipment["ALLOW_DELIVERY"] == "Y") ? GetMessage("SHIPMENT_ORDER_YES") : GetMessage("SHIPMENT_ORDER_NO"))."<br><a href=\"user_edit.php?ID=".$shipment['EMP_ALLOW_DELIVERY_ID']."\">".htmlspecialcharsbx($shipment['EMP_ALLOW_DELIVERY_BY_LAST_NAME'])." ".htmlspecialcharsbx($shipment['EMP_ALLOW_DELIVERY_BY_NAME'])."</a><br>".htmlspecialcharsbx($shipment['DATE_ALLOW_DELIVERY']));

	$row->AddField("CANCELED", (($shipment["CANCELED"] == "Y") ? GetMessage("SHIPMENT_ORDER_YES") : GetMessage("SHIPMENT_ORDER_NO"))."<br><a href=\"user_edit.php?ID=".$shipment['EMP_CANCELED_ID']."\">".htmlspecialcharsbx($shipment['EMP_CANCELED_BY_LAST_NAME'])." ".htmlspecialcharsbx($shipment['EMP_CANCELED_BY_NAME'])."</a><br>".htmlspecialcharsbx($shipment['DATE_CANCELED']));

	$row->AddField("MARKED", (($shipment["MARKED"] == "Y") ? GetMessage("SHIPMENT_ORDER_YES") : GetMessage("SHIPMENT_ORDER_NO"))."<br><a href=\"user_edit.php?ID=".$shipment['EMP_MARKED_ID']."\">".htmlspecialcharsbx($shipment['EMP_MARKED_BY_LAST_NAME'])." ".htmlspecialcharsbx($shipment['EMP_MARKED_BY_NAME'])."</a><br>".htmlspecialcharsbx($shipment['DATE_MARKED']));

	$row->AddField("STATUS", htmlspecialcharsbx($shipment['STATUS_NAME']));

	$arActions = array();
	$arActions[] = array("ICON"=>"edit", "TEXT"=>GetMessage("EDIT_SHIPMENT_ALT"), "ACTION"=>$lAdmin->ActionRedirect("sale_order_shipment_edit.php?order_id=".$shipment['ORDER_ID']."&shipment_id=".$shipment['ID']."&lang=".$lang.GetFilterParams("filter_").""), "DEFAULT"=>true);

	if(!$bReadOnly)
	{
		$arActions[] = array("SEPARATOR" => true);
		$arActions[] = array("ICON"=>"delete", "TEXT"=>GetMessage("DELETE_SHIPMENT_ALT"), "ACTION"=>"if(confirm('".GetMessageJS('DELETE_SHIPMENT_CONFIRM')."')) ".$lAdmin->ActionDoGroup($shipment['ID'], "delete"));
	}

	$row->AddActions($arActions);
}

$lAdmin->AddGroupActionTable(
	array(
		"delete" => GetMessage("MAIN_ADMIN_LIST_DELETE"),
	)
);

$lAdmin->AddAdminContextMenu();

$lAdmin->AddFooter(
	array(
		array(
			"title" => GetMessage("MAIN_ADMIN_LIST_SELECTED"),
			"value" => $dbResultList->SelectedRowsCount()
		),
		array(
			"counter" => true,
			"title" => GetMessage("MAIN_ADMIN_LIST_CHECKED"),
			"value" => "0"
		),
	)
);

$lAdmin->CheckListMode();

$APPLICATION->SetTitle(GetMessage("SHIPMENT_TITLE"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<form name="find_form" method="GET" action="<?=$curPage?>?">
<?
$filter = array(
	"filter_order_id_from" => GetMessage("PAYMENT_ORDER_ID"),
	"filter_order_paid"     => GetMessage("PAYMENT_ORDER_PAID"),
	"filter_date_paid" => GetMessage("PAYMENT_DATE_PAID"),
	"filter_account_num" => GetMessage("PAYMENT_ACCOUNT_NUM"),
	"filter_user_id" => GetMessage("SALE_SHIPMENT_F_USER_ID"),
	"filter_user_login" => GetMessage("SALE_SHIPMENT_F_USER_LOGIN"),
	"filter_user_email" => GetMessage("SALE_SHIPMENT_F_USER_EMAIL")
);

$oFilter = new CAdminFilter(
	$tableId."_filter",
	$filter
);

$oFilter->Begin();
?>
<tr>
	<td><?=GetMessage("SHIPMENT_ORDER_ID");?>:</td>
	<td>
		<script type="text/javascript">
			function changeFilterOrderIdFrom()
			{
				if (document.find_form.filter_order_id_to.value.length<=0)
					document.find_form.filter_order_id_to.value = document.find_form.filter_order_id_from.value;
			}
		</script>
		<?=GetMessage("SHIPMENT_ORDER_ID_FROM");?>
		<input type="text" name="filter_order_id_from" OnChange="changeFilterOrderIdFrom()" value="<?=(intval($filter_order_id_from)>0)?intval($filter_order_id_from):""?>" size="10">
		<?=GetMessage("SHIPMENT_ORDER_ID_TO");?>
		<input type="text" name="filter_order_id_to" value="<?=(intval($filter_order_id_to)>0)?intval($filter_order_id_to):""?>" size="10">
	</td>
</tr>
<tr>
	<td><?=GetMessage("SHIPMENT_ID");?>:</td>
	<td>
		<script type="text/javascript">
			function changeFilterOrderIdFrom()
			{
				if (document.find_form.filter_shipment_id_to.value.length<=0)
					document.find_form.filter_shipment_id_to.value = document.find_form.filter_shipment_id_from.value;
			}
		</script>
		<?=GetMessage("SHIPMENT_ORDER_ID_FROM");?>
		<input type="text" name="filter_shipment_id_from" OnChange="changeFilterOrderIdFrom()" value="<?=(intval($filter_shipment_id_from) > 0) ? intval($filter_shipment_id_from) : ""?>" size="10">
		<?=GetMessage("SHIPMENT_ORDER_ID_TO");?>
		<input type="text" name="filter_shipment_id_to" value="<?=(intval($filter_shipment_id_to) > 0) ? intval($filter_shipment_id_to) : ""?>" size="10">
	</td>
</tr>
<tr>
	<td><?=GetMessage("SALE_ORDER_ALLOW_DELIVERY");?>:</td>
	<td>
		<select name="filter_allow_delivery">
			<option value="NOT_REF">(<?=GetMessage("SALE_ORDER_ALL");?>)</option>
			<option value="Y"<?if ($filter_allow_delivery=="Y") echo " selected"?>><?=GetMessage("SHIPMENT_ORDER_YES");?></option>
			<option value="N"<?if ($filter_allow_delivery=="N") echo " selected"?>><?=GetMessage("SHIPMENT_ORDER_NO");?></option>
		</select>
	</td>
</tr>
<tr>
	<td><?=GetMessage("SALE_ORDER_DEDUCTED");?>:</td>
	<td>
		<select name="filter_deducted">
			<option value="NOT_REF">(<?=GetMessage("SALE_ORDER_ALL");?>)</option>
			<option value="Y"<?if ($filter_allow_delivery=="Y") echo " selected"?>><?=GetMessage("SHIPMENT_ORDER_YES");?></option>
			<option value="N"<?if ($filter_allow_delivery=="N") echo " selected"?>><?=GetMessage("SHIPMENT_ORDER_NO");?></option>
		</select>
	</td>
</tr>
<tr>
	<td><?=GetMessage("SHIPMENT_DATE_DEDUCTED");?>:</td>
	<td>
			<?=CalendarPeriod("filter_date_deducted_from", htmlspecialcharsbx($filter_date_deducted_from), "filter_date_deducted_to",
				htmlspecialcharsbx($filter_date_deducted_to), "find_form", "Y")?>
	</td>
</tr>
<tr>
	<td><?=GetMessage("SALE_ORDER_DELIVERY_NAME");?>:</td>
	<td>
		<select multiple name="filter_delivery_id[]">
			<option value="NOT_REF">(<?=GetMessage("SALE_ORDER_ALL");?>)</option>
			<?
			\Bitrix\Sale\Delivery\Services\Manager::getHandlersList();

			$result = array();
			foreach ($serviceList as $serviceId => $service)
			{
				if (is_callable($service['CLASS_NAME'].'::canHasChildren') && $service['CLASS_NAME']::canHasChildren())
					continue;

				if ((int)$service['PARENT_ID'] > 0)
					$name = $serviceList[$service['PARENT_ID']]['NAME'].': '.$service['NAME'];
				else
					$name = $service['NAME'];

				$selected = (is_array($filter_delivery_id) && in_array($serviceId, $filter_delivery_id)) ? 'selected' : '';
				$name = htmlspecialcharsbx($name);
				echo '<option title="'.$name.'" value="'.htmlspecialcharsbx($serviceId).'" '.$selected.'">['.htmlspecialcharsbx($serviceId).'] '.$name.'</option>';
			}
			?>
		</select>
	</td>
</tr>
<tr>
	<td><?=GetMessage("SALE_ORDER_DELIVERY_DOC_NUM");?>:</td>
	<td>
		<input type="text" name="filter_delivery_doc_num" value="<?=htmlspecialcharsbx($filter_delivery_doc_num);?>">
	</td>
</tr>
<tr>
	<td><?=GetMessage("SALE_ORDER_PRICE_DELIVERY");?>:</td>
	<td>
		<?echo GetMessage("PRICE_DELIVERY_FROM");?>
		<input type="text" name="filter_price_delivery_from" value="<?=($filter_price_delivery_from!=0) ? htmlspecialcharsbx($filter_price_delivery_from) : '';?>" size="3">

		<?echo GetMessage("PRICE_DELIVERY_TO");?>
		<input type="text" name="filter_price_delivery_to" value="<?=($filter_price_delivery_to!=0) ? htmlspecialcharsbx($filter_price_delivery_to) : '';?>" size="3">
	</td>
</tr>
<tr>
	<td><?=GetMessage("SALE_ORDER_ACCOUNT_NUM");?>:</td>
	<td>
		<input type="text" name="filter_account_num" value="<?=htmlspecialcharsbx($filter_account_num)?>">
	</td>
</tr>
<?
	$params = array(
		'select' => array('ID', 'NAME')
	);
	$res = \Bitrix\Sale\Internals\CompanyTable::getList($params);
	$companies = $res->fetchAll();
?>
<tr>
	<td><?=GetMessage("SALE_ORDER_COMPANY_ID");?>:</td>
	<td>
		<select name="filter_company_id">
			<option value="NOT_REF">(<?=GetMessage("SALE_ORDER_ALL");?>)</option>
			<?
			foreach ($companies as $company)
				echo '<option value="'.$company['ID'].'">'.htmlspecialcharsbx($company['NAME']).'</option>';
			?>
		</select>
	</td>
</tr>
<?
	$params = array(
		"select" => array(
			'ID',
			'STATUS_NAME' => 'Bitrix\Sale\Internals\StatusLangTable:STATUS.NAME'
		),
		"filter" => array(
			'=Bitrix\Sale\Internals\StatusLangTable:STATUS.LID'  => $lang,
			'TYPE' => 'D'
		)
	);
	$result = \Bitrix\Sale\Internals\StatusTable::getList($params);
?>
<tr>
	<td valign="top"><?echo GetMessage("SALE_ORDER_SHIPMENT_STATUS")?>:<br /><img src="/bitrix/images/sale/mouse.gif" width="44" height="21" border="0" alt=""></td>
	<td valign="top">
		<select name="filter_status[]" multiple size="3">
			<?
			while ($statusList = $result->fetch())
			{
				?><option value="<?= $statusList["ID"] ?>"<?if (is_array($filter_status) && in_array($statusList["ID"], $filter_status)) echo " selected"?>>[<?=$statusList["ID"];?>] <?= htmlspecialcharsEx($statusList["STATUS_NAME"]) ?></option><?
			}
			?>
		</select>
	</td>
</tr>
<tr>
	<td><?echo \Bitrix\Main\Localization\Loc::getMessage("SALE_SHIPMENT_F_USER_ID");?>:</td>
	<td>
		<?echo FindUserID("filter_user_id", $filter_user_id, "", "find_form");?>
	</td>
</tr>
<tr>
	<td><?echo \Bitrix\Main\Localization\Loc::getMessage("SALE_SHIPMENT_F_USER_LOGIN");?>:</td>
	<td>
		<input type="text" name="filter_user_login" value="<?echo htmlspecialcharsEx($filter_user_login)?>" size="40">
	</td>
</tr>
<tr>
	<td><?echo \Bitrix\Main\Localization\Loc::getMessage("SALE_SHIPMENT_F_USER_EMAIL");?>:</td>
	<td>
		<input type="text" name="filter_user_email" value="<?echo htmlspecialcharsEx($filter_user_email)?>" size="40">
	</td>
</tr>
<?

$oFilter->Buttons(
	array(
		"table_id" => $tableId,
		"url" => $curPage,
		"form" => "find_form"
	)
);

$oFilter->End();
?>
</form>
<?
$lAdmin->DisplayList();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");