function countOccur(pHaystack, pNeedle)
{
	pHaystack += "";
	pNeedle += "";
	if (pNeedle.length == 0) return pHaystack.length + 1;
	
	var aPos = -1;
	var aCnt = 0;
	while (true) {
		aPos = pHaystack.indexOf(pNeedle, ++aPos);
		if (aPos >= 0)
			aCnt++;
		else
			break;
	}
	
	return aCnt;
}

function isValidEmail(pEmail)
{
	var aFilter = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	pEmail = $.trim(pEmail);
	return aFilter.test(pEmail);
}


$.fn.pasteNumeric = function() {
	return this.each(function(){
		$(this).bind("paste", function(pEvent){
			setTimeout(function() {
				var aField = $(pEvent.target);
				var anInVal = aField.val();
				var anOutVal = anInVal.replace(/[^0-9\.]/g, "");
				while (countOccur(anOutVal, '.') > 1)
					anOutVal = anOutVal.replace(/\./, "");
				if (anOutVal.endsWith('.'))
					anOutVal = anOutVal.substring(0, anOutVal.length - 2);
				if (anInVal != anOutVal)
					aField.val(anOutVal);
			});
		})
	});
}

