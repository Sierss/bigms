<?
$MESS ['IBLOCK_TYPE_LIST_TIP'] = "Выберите тип инфоблока и нажмите кнопку <i><b>ок</b></i>.";
$MESS ['IBLOCK_ID_IN_TIP'] = "Выберите включаемые инфоблоки и нажмите кнопку <i><b>ок</b></i>.";
$MESS ['IBLOCK_ID_EX_TIP'] = "Фильтр для исключения по ID и(или) мнемоническому коду информационного блока. В качестве параметра может быть как единичное значение (ID или мнемонический код информационного блока), так и массив (array) таких значений. Необязательный. По умолчанию выбираются все элементы из информационных блоков типа type без исключений.";
$MESS ['SECTION_ID_TIP'] = "Поле содержит код, в котором передается ID раздела. По умолчанию поле содержит ={\$_REQUEST[\"SECTION_ID\"]}.";
$MESS ['IBLOCK_AS_CATEGORY_TIP'] = 'Названия инфоблоков выгружаются в списке категорий &lt;categories&gt; в качестве корневых разделов. Активируйте этот параметр, если товары лежат в корне инфоблока. Также рекомендуется использовать для сложной архитектуры каталога.';
$MESS ['ELEMENT_SORT_FIELD_TIP'] = "Поле, по которому будет проходить сортировка элементов текущего раздела.";
$MESS ['ELEMENT_SORT_ORDER_TIP'] = "Направление сортировки - по возрастанию или по убыванию.";
$MESS ['FILTER_NAME_TIP'] = "Название переменной, в которую передается массив параметров из фильтра. Служит для определения выходящих из фильтра элементов. Поле может быть оставлено пустым, тогда используется значение по умолчанию.";
$MESS ['SECTION_URL_TIP'] = "Путь к странице с описанием раздела инфоблока.";
$MESS ['DETAIL_URL_TIP'] = "Путь к странице с детальным описанием элемента инфоблока.";
$MESS ['BASKET_URL_TIP'] = "Путь к странице с корзиной покупателя.";
$MESS ['ACTION_VARIABLE_TIP'] = "В данном поле указывается имя переменной, в которой передается действие: ADD_TO_COMPARE_LIST, ADD2BASKET и т.д. Значение поля по умолчанию <i>action</i>.";
$MESS ['PRODUCT_ID_VARIABLE_TIP'] = "Имя переменной, в которой будет передаваться ID товара.";
$MESS ['SECTION_ID_VARIABLE_TIP'] = "Имя переменной, в которой будет передаваться ID раздела инфоблока.";
$MESS ['DISPLAY_PANEL_TIP'] = "При установленной опции кнопки будут отображены в режиме редактирования сайта на административной панели и в наборе кнопок области редактирования данного компонента.";
$MESS ['DISPLAY_COMPARE_TIP'] = "Если опция отмечена, то будет выведена кнопка <i>Сравнить</i>, с помощью которой элемент добавляется в список сравнения.";
$MESS ['SET_TITLE_TIP'] = "При установленной опции в качестве заголовка страницы будет установлено название раздела.";
$MESS ['PAGE_ELEMENT_COUNT_TIP'] = "Данное число определяет количество элементов на одной странице. Остальные элементы будут выведены с помощью постраничной навигации.";
$MESS ['LINE_ELEMENT_COUNT_TIP'] = "Число определяет количество элементов в одной строке при выводе элементов раздела.";
$MESS ['PROPERTY_CODE_TIP'] = "Среди свойств инфоблока можно выбрать те, которые будут отображены при показе элементов. При выборе пункта (не выбрано)->  и без указания кодов свойств в строках ниже, свойства выведены не будут.";
$MESS ['PRICE_CODE_TIP'] = "Устанавливается, какой из типов цен будут выведен в каталоге. Если ни один из типов не выбран, то цена и кнопки <i>Купить</i> и <i>В корзину</i> не будет показаны.";
$MESS ['USE_PRICE_COUNT_TIP'] = "При отмеченной опции выводятся цены всех типов на товары.";
$MESS ['SHOW_PRICE_COUNT_TIP'] = "Можно установить количество, для которого будет выведена цена, например, 1 или 10, в зависимости от специфики товара.";
$MESS ['CACHE_TYPE_TIP'] = "<i><b>Авто</b></i>: автоматически работает при включенном кешировании в течение заданного времени;<br /><i><b>Кешировать</b></i>: нужно только указать время кеширования;<br /><i>Не кешировать</i>: не кешируется.";
$MESS ['CACHE_TIME_TIP'] = "Поле служит для указания времени кеширования в секундах.";
$MESS ['CACHE_NON_MANAGED_TIP'] = "При изменении данных кеш будет оставаться валидным, даже если в настройках ядра включено управляемое кеширование.<br/>Рекомендуется включить для больших каталогов с регулярной синхронизацией с 1С и настроить автоматическую генерацию выгрузки.";
$MESS ['CACHE_FILTER_TIP'] = "При установленной опции каждый результат, полученный из фильтра, будет кешироваться.";
$MESS ['DISPLAY_TOP_PAGER_TIP'] = "При отмеченной опции навигация по страницам будет выведена наверху страницы.";
$MESS ['DISPLAY_BOTTOM_PAGER_TIP'] = "При отмеченной опции навигация по страницам будет выведена внизу страницы.";
$MESS ['PAGER_TITLE_TIP'] = "В данном поле указывается название категорий, по которым происходит перемещение по элементам.";
$MESS ['PAGER_SHOW_ALWAYS_TIP'] = "Если данный флаг отмечен, то постраничная навигация будет выводиться, даже если все элементы помещаются на одной странице.";
$MESS ['PAGER_TEMPLATE_TIP'] = "В данном поле указывается имя шаблона постраничной навигации. Если поле пусто, то выбирается шаблон по умолчанию (.default). Также в системе задан шаблон <i>orange</i>.";
$MESS ['PAGER_DESC_NUMBERING_TIP'] = "Механизм используют, если при добавлении элемента инфоблока, он всегда попадает наверх списка. Таким образом, меняется лишь последняя страница. Все предыдущие можно надолго закешировать.";
$MESS ['PAGER_DESC_NUMBERING_CACHE_TIME_TIP'] = "Время кеширования первых страниц в секундах при использовании обратной навигации.";
$MESS ['META_KEYWORDS_TIP'] = "Установить ключевые слова страницы из свойства.";
$MESS ['META_DESCRIPTION_TIP'] = "Установить описание страницы из свойства.";
$MESS ['INCLUDE_SUBSECTIONS_TIP'] = "При отмеченной опции будут показаны элементы подразделов раздела.";
$MESS ['AJAX_MODE_TIP'] = "Включение для компонента режима AJAX.";
$MESS ['AJAX_OPTION_SHADOW_TIP'] = "При выполнении перехода будет затенена область, которая должна измениться.";
$MESS ['AJAX_OPTION_JUMP_TIP'] = "Если пользователь совершит AJAX-переход, то по окончании загрузки произойдет прокрутка к началу компонента.";
$MESS ['AJAX_OPTION_STYLE_TIP'] = "При совершении AJAX-переходов будет происходить подгрузка и обработка списка стилей, вызванных компонентом.";
$MESS ['AJAX_OPTION_HISTORY_TIP'] = "Когда пользователь выполняет AJAX-переходы, то при включенной опции можно использовать кнопки браузера \"Назад\" и \"Вперед\".";
$MESS ['PRICE_VAT_INCLUDE_TIP'] = "При отмеченной опции цены будут показаны с учетом НДС.";

$MESS ['CURRENCY_TIP'] = "Базовая валюта, подставляется в элемент currencyId";

$MESS ['CURRENCIES_PROP_TIP'] = "Cвойство где хранятся валюты. Используемые валюты оформляются в ввиде свойства инфоблока типа список. В элементы currencyId подставляются значения IDXML_ID у свойства валют.";
$MESS ['DETAIL_TEXT_PRIORITET_TIP'] = "При включенной опции description будет подставляться из поля Подробнее. Если оно пустое - из поля Анонс";

$MESS ['DISCOUNTS_TIP'] = "Для максимального быстродействия рекомендуется не учитывать скидки.<br/>Если упрощенный алгоритм ставит неверные цены, используйте стандартный алгоритм 1С-Битрикс.";

$MESS ['SAVE_IN_FILE_TIP'] = "<b>Рекомендуем для больших каталогов и слабых серверов!</b> При установленном чекбоксе xml будет сохранятся в файл saved_file.xml (файл создается автоматически в папке, в которой находится файл с вызовом компонента)";

$MESS ['UTM_SOURCE_TIP'] = "Рекламная площадка";
$MESS ['UTM_CAMPAIGN_TIP'] = "Название рекламной кампании. Например: <ul><li>Zakaz-pizzy</li> <li>telefony</li> <li>apollo1</li></ul> Внимание! Значение свойства должно быть заполнено латиницей. Если оставить это поле пустым, в качестве значения будет выбран символьный код родительского раздела товара.";
$MESS ['UTM_MEDIUM_TIP'] = "Тип рекламы. Например: <ul><li>cpc</li> <li>cpm</li> <li>banner</li> <li>email</li></ul>";
$MESS ['UTM_TERM_TIP'] = "Ключевое слово. Внимание! Значение свойства должно быть заполнено латиницей. Если оставить это поле пустым, в качестве значения будет выбран символьный код товара.";

$MESS ['AGENT_CHECK_TIP'] = "При установленном чекбоксе будет создан агент, который будет раз в сутки обновлять файл выгрузки.";

$MESS ['SALES_NOTES_TIP'] = "Элемент используется для отражения информации о минимальной сумме заказа, минимальной партии товара или необходимости предоплаты, а так же для описания акций, скидок и распродаж. Допустимая длина текста в элементе — 50 символов. Необязательный элемент.";
$MESS ['SALES_NOTES_TEXT_TIP'] = "<b>Значение тега будет одинаковым для всех товаров.</b> Элемент используется для отражения информации о минимальной сумме заказа, минимальной партии товара или необходимости предоплаты, а так же для описания акций, скидок и распродаж. Допустимая длина текста в элементе — 50 символов. Необязательный элемент.";
$MESS ["IBLOCK_ORDER_TIP"] = "<b>При отмеченном чекбоксе</b> - отсутствующим товарам будет проставлен статус 'Под заказ' (available='false')<br><b>При снятом чекбоксе</b> - отсутствующие товары выгружаться не будут";

$MESS ['BIG_CATALOG_PROP_TIP'] = "<b>Внимание!</b> Данное свойство предназначено для каталогов, содержащих большое количество товаров, и может быть использовано только при отмеченной опции <b>\"Сохранить выгрузку в файл\"</b>. Рекомендованное значение свойства: 800 - 1200.";
$MESS ['LOCAL_DELIVERY_COST_OFFER_TIP'] = "<b>Элемент является устаревшим!</b> Рекомендуем перейти на использование элемента <b>delivery-options</b> (настройка ниже), так как он предоставляет больше возможностей.";
$MESS ["STORE_OFFER_TIP"] = "Элемент позволяет указать возможность купить соответствующий товар в розничном магазине.</br>Возможные значения:<ol><li>false — возможность покупки в розничном магазине отсутствует;</li><li>true — товар можно купить в розничном магазине.</li></ol> Необязательный элемент.";
$MESS ["STORE_PICKUP_TIP"] = "Элемент позволяет указать возможность зарезервировать выбранный товар и забрать его самостоятельно.</br>Возможные значения:<ol><li>false — возможность «самовывоза» отсутствует;</li><li>true — товар можно забрать самостоятельно.</li></ol> Необязательный элемент.";

$MESS ['NAME_CUT_TIP'] = "Введите максимальное количество символов в названии товара";
$MESS ['PROPDUCT_PROP_TIP'] = "Укажите символьные коды свойств товаров, значения которых будут выводиться перед описанием товара в теге description в формате 'Название свойства: Значение свойства'";
$MESS ['OFFER_PROP_TIP'] = "Укажите символьные коды свойств торговых предложений, значения которых будут выводиться перед описанием торговых предложений в теге description в формате 'Название свойства: Значение свойства'";

$MESS ['LOCAL_DELIVERY_COST_TIP'] = "Элемент является устаревшим! Рекомендуем перейти на использование элемента <b>delivery-options</b>, так как он предоставляет больше возможностей.";
$MESS["AGE_CATEGORY_TIP"] = "Допустимые значения параметра при unit=\"year\": 0, 6, 12, 16, 18. Допустимые значения параметра при unit=\"month\": 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12.";
$MESS ['BID_TIP'] = "Должен иметь целое положительное значения, например 21, что соответствует ставке 0,21 у. е.";
$MESS ['CBID_TIP'] = "Должен иметь целое положительное значения, например 21, что соответствует ставке 0,21 у. е.";
$MESS ['CPA_SHOP_TIP'] = "Элемент cpa, вложенный в элемент shop. Принимает значения 0 или 1.";
$MESS ['CPA_OFFERS_TIP'] = "Элемент cpa, вложенный в элемент offer. Принимает значения 0 или 1.";
$MESS ['NAME_PROP_COMPILE_TIP'] = "Воспользуйтесь данной настройкой, если хотите составить название товара сразу из нескольких свойств.";
$MESS ['PARAMS_TIP'] = "Свойства, выбранные в данной настройке, попадут в выгрузку в формате: <param name='название свойства'>значение свойства</param>";
$MESS ['COND_PARAMS_TIP'] = "Свойства, выбранные в данной настройке, не попадут в выгрузку, но вы сможете их использоваться в result_modifier для самостоятельной модификации шаблона";
$MESS ['EXPIRY_TIP'] = "Элемент предназначен для указания срока годности / срока службы либо для указания даты истечения срока годности / срока службы. Значение элемента должно быть в формате ISO8601";
$MESS ['WEIGHT_TIP'] = "Вес указывается в килограммах с учетом упаковки. Формат элемента: положительное число с точностью 0.001, разделитель целой и дробной части — точка.";
$MESS ['DIMENSIONS_TIP'] = "Элемент предназначен для указания габаритов товара (длина, ширина, высота) в упаковке. Размеры указываются в сантиметрах. Формат элемента: три положительных числа с точностью 0.001, разделитель целой и дробной части — точка. Числа должны быть разделены символом «/» без пробелов.";
?>