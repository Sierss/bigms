function OnDeliveryOptionsYMarket(arParams){
    if (null != window.deliveryOptionsFiller){
		window.deliveryOptionsFiller = null;
	}
	window.deliveryOptionsFiller = new wfJCInputFiller(arParams);
}
/**
 * Main class
 * @param {object} arParams
 * @returns {JCInputFiller}
 */
function wfJCInputFiller(arParams){
    this.options = arParams.data.split("|");
    this.max = this.options[0];
    this.current = 0;
    this.arParams = arParams;
    this.obButton = document.createElement('INPUT');
    this.obButton.type = "button";
    this.obButton.value = this.options[1];
	this.arParams.oCont.appendChild(this.obButton);
    this.obButton.onclick = BX.delegate(this.btnClick, this);
	this.saveData = BX.delegate(this.__saveData, this);
    if(this.arParams.oInput.value.length > 0){
        var anchorList = this.arParams.oInput.value.split("|"),
            anchorListLength = anchorList.length;
        for(var i = 0; i < anchorListLength;i++){
            var props = anchorList[i].split(","),
                obAnchor = document.createElement('a');
            obAnchor.href = "#";
            obAnchor.style = "display:block;margin-bottom:10px;";
            obAnchor.setAttribute("datacost",props[0]);
            obAnchor.setAttribute("datadays",props[1]);
            obAnchor.setAttribute("dataordbefore",props[2]);
            obAnchor.onclick = BX.delegate(this.anchorClick, this);
            obAnchor.innerHTML = this.options[4];
            this.arParams.oCont.insertBefore(obAnchor,this.obButton);
            this.current++;
            if(this.current >= this.max) this.obButton.style = "display:none;";
        }
    }
}
/**
 * Click on anchor
 * @param {object} e event object
 * @returns {undefined}
 */
wfJCInputFiller.prototype.anchorClick = function(e){
    var that = this,
        anchor = e.target,
        jcCost = anchor.getAttribute("datacost"),
        jcDays = anchor.getAttribute("datadays"),
        jcOrdBefore = anchor.getAttribute("dataordbefore"),
        btn_save = {
            title: BX.message('JS_CORE_WINDOW_SAVE'),
            id: 'savebtn',
            name: 'savebtn',
            className: BX.browser.IsIE() && BX.browser.IsDoctype() && !BX.browser.IsIE10() ? '' : 'adm-btn-save',
            action: function () {
                var jcCost = BX("jc_cost").value,
                    jcDays = BX("jc_days").value,
                    jcOrdBefore = BX("jc_orderBefore").value,
                    obAnchor = anchor;
                obAnchor.setAttribute("datacost",jcCost);
                obAnchor.setAttribute("datadays",jcDays);
                obAnchor.setAttribute("dataordbefore",jcOrdBefore);
                that.current++;
                if(that.current >= that.max) that.obButton.style = "display:none;";
                that.saveData();
                this.parentWindow.Close();
            }
        },
        btn_delete = {
            title: that.options[3],
            id: 'deletebtn',
            name: 'deletebtn',
            action: function () {
                that.current--;
                if(that.current < that.max) that.obButton.style = "display:block;";
                anchor.parentNode.removeChild(anchor);
                that.saveData();
                this.parentWindow.Close();
            }
        },
        paramsList = that.arParams.getElements(),
        result = paramsList.IBLOCK_ID_IN.childNodes;
        if (!result)
        {
            result = paramsList.IBLOCK_ID_IN[0].children;
        }
        //result = $(paramsList.IBLOCK_ID_IN).find("option"),
        var length = result.length,
        ibs = [];
    for(var i = 0;i< length;i++){
        if(result[i].selected){
            ibs.push(result[i].value);
        }
    }
    var strUrl = '/bitrix/components/webfly/yandex.market/settings_sel/settings.php',
        strUrlPost = 'iblock_id='+ibs.join(",")+"&cost="+jcCost+"&days="+jcDays+"&orderBefore="+jcOrdBefore;
    if(null != window.jsPopupOptionsFiller){
        window.jsPopupOptionsFiller.DIV.parentNode.removeChild(window.jsPopupOptionsFiller.DIV);
    }
    window.jsPopupOptionsFiller = new BX.CDialog({
        'content_url': strUrl,
        'content_post': strUrlPost,
        'width':500,
        'height':200,
        'resizable':false,
        'title': that.options[2],
        'buttons':[BX.CDialog.btnCancel,btn_save,btn_delete]
    });
    window.jsPopupOptionsFiller.Show();
    window.jsPopupOptionsFiller.PARAMS.content_url = '';
    e.preventDefault();
};
/**
 * Click on add
 * @returns {Boolean}
 */
wfJCInputFiller.prototype.btnClick = function(){
    var that = this;
    if(that.current < that.max){
        var btn_save = {
            title: BX.message('JS_CORE_WINDOW_SAVE'),
            id: 'savebtn',
            name: 'savebtn',
            className: BX.browser.IsIE() && BX.browser.IsDoctype() && !BX.browser.IsIE10() ? '' : 'adm-btn-save',
            action: function () {
                var jcCost = BX("jc_cost").value,
                    jcDays = BX("jc_days").value,
                    jcOrdBefore = BX("jc_orderBefore").value,
                    obAnchor = document.createElement('a');
                if(jcCost.length > 0){
                    obAnchor.href = "#";
                    obAnchor.style = "display:block;margin-bottom:10px;";
                    obAnchor.setAttribute("datacost",jcCost);
                    obAnchor.setAttribute("datadays",jcDays);
                    obAnchor.setAttribute("dataordbefore",jcOrdBefore);
                    obAnchor.onclick = BX.delegate(that.anchorClick, that);
                    obAnchor.innerHTML = that.options[4];
                    that.arParams.oCont.insertBefore(obAnchor,that.obButton);
                    that.current++;
                    that.saveData();
                    if(that.current >= that.max) that.obButton.style = "display:none;";
                }
                this.parentWindow.Close();
            }
        };
        var paramsList = that.arParams.getElements(),
            result = paramsList.IBLOCK_ID_IN.childNodes;
        if (!result)
        {
            result = paramsList.IBLOCK_ID_IN[0].children;
        }
            //result = $(paramsList.IBLOCK_ID_IN).find("option"),
            var length = result.length,
            ibs = [];
        for(var i = 0;i< length;i++){
            if(result[i].selected){
                ibs.push(result[i].value);
            }
        }
        var strUrl = '/bitrix/components/webfly/yandex.market/settings_sel/settings.php',
            strUrlPost = 'iblock_id='+ibs.join(",");
        if(null != window.jsPopupOptionsFiller){
            window.jsPopupOptionsFiller.DIV.parentNode.removeChild(window.jsPopupOptionsFiller.DIV);
        }
        window.jsPopupOptionsFiller = new BX.CDialog({
            'content_url': strUrl,
            'content_post': strUrlPost,
            'width':500,
            'height':200,
            'resizable':true,
            'title': that.options[2],
            'buttons':[BX.CDialog.btnCancel,btn_save]
        });
        window.jsPopupOptionsFiller.Show();
        window.jsPopupOptionsFiller.PARAMS.content_url = '';
    }else{
        that.obButton.style = "display:none;";
    }
	return false;
};
/**
 * Saves data
 * @returns {undefined}
 */
wfJCInputFiller.prototype.__saveData = function(){
    var result = [],
        anchors = BX.findChildren(BX(this.arParams.oCont),{"tag" : "a"}),
        anhorsLength = anchors.length;
    for(var i = 0;i < anhorsLength;i++){
        result.push(anchors[i].getAttribute("datacost")+","+anchors[i].getAttribute("datadays")+","+anchors[i].getAttribute("dataordbefore"));
    }
    this.arParams.oInput.value = result.join("|");
};