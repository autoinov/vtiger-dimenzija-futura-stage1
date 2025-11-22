{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}

<footer class="app-footer">
	<p>
	Instalacija - {$VTIGER_VERSION}&nbsp;&nbsp;© 2004 - {date('Y')}&nbsp;&nbsp;
		<a href="//autoinovacije.hr/web-dizajn-izrada-web-stranica/" target="_blank">Izrada web stranica <strong>Autoinovacije d.o.o.</strong></a>&nbsp;&nbsp;
	</p>
</footer>
</div>
<div id='overlayPage'>
	<!-- arrow is added to point arrow to the clicked element (Ex:- TaskManagement), 
	any one can use this by adding "show" class to it -->
	<div class='arrow'></div>
	<div class='data'>
	</div>
</div>
<div id='helpPageOverlay'></div>
<div id="js_strings" class="hide noprint">{Zend_Json::encode($LANGUAGE_STRINGS)}</div>
<div id="maxListFieldsSelectionSize" class="hide noprint">{$MAX_LISTFIELDS_SELECTION_SIZE}</div>
<div class="modal myModal fade"></div>
{include file='JSResources.tpl'|@vtemplate_path}

{literal}
<script>
app.event.on('post.load', function () {
  // DETAIL VIEW: pretvaranje brojeva u <a href="tel:">
  document.querySelectorAll('span.value[data-field-type="phone"]').forEach(function (span) {
    if (!span || span.querySelector('a')) return;
    const number = span.textContent.trim();
    const clean = number.replace(/[^0-9+]/g, '');
    if (clean.length > 4) {
      span.innerHTML = '<a href="tel:' + clean + '">' + number + '</a>';
    }
  });

  // LIST VIEW: isto za listu
  document.querySelectorAll('td.listViewEntryValue[data-field-type="phone"]').forEach(function (cell) {
    const valueSpan = cell.querySelector('span.value');
    if (!valueSpan || valueSpan.querySelector('a')) return;
    const number = valueSpan.textContent.trim();
    const clean = number.replace(/[^0-9+]/g, '');
    if (clean.length > 4) {
      valueSpan.innerHTML = '<a href="tel:' + clean + '">' + number + '</a>';
    }
  });
});
</script>
{/literal}





</body>

</html>
