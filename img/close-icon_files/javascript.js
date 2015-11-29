


// a little bit ajax is needed for the rating function
function ajax(url, vars, callbackFunction)
{
  var request = window.XMLHttpRequest ?
      new XMLHttpRequest() : new ActiveXObject("MSXML2.XMLHTTP.3.0");
  request.open("POST", url, true);
  request.setRequestHeader("Content-Type",
                           "application/x-www-form-urlencoded"); 
 
  request.onreadystatechange = function()
  {
    if (request.readyState == 4 && request.status == 200)
    {
      if (request.responseText)
      {
          callbackFunction(request.responseText);
      }
    }
  };
  request.send(vars);
}

function rateresult(result)
{
	// Dummy URL needed for standard javascript functions
	var result2 = 'http://www.dummy.com/index.php?'+result;
	var html_ratingbar = getURLParameters('html_ratingbar',result2);
	var html_ratingvalues = getURLParameters('html_ratingvalues',result2);
	var html_ratingtext = getURLParameters('html_ratingtext',result2);
	// html_rating_thanks = html_rating_thanks.replace(/\+/g,' ');
	var html_ratingbar = decodeURIComponent(html_ratingbar);
	var html_ratingvalues = decodeURIComponent(html_ratingvalues);
	var html_ratingtext = decodeURIComponent(html_ratingtext);

	document.getElementById('ratingbar').innerHTML = html_ratingbar;
	if (html_ratingvalues)
		document.getElementById('ratingvalues').innerHTML = html_ratingvalues;
	document.getElementById('ratingtext').innerHTML = html_ratingtext;
	
	// alert(html_rating_thanks);	
}

// Rate Icon Sets, etc.
var rating_running = 0;
function rate(type,id,rating)
{
	if (type=='iconset' && (rating_running==0))
	{
		// For the double clickers
		rating_running = 1;
		document.getElementById('ratingbar').innerHTML='<img src=/images/rating/bar_progress.gif width=108px height=10px>';
		vars = 'set_id='+id+'&rating='+rating;
		ajax('/inc/applic/ajax_rating.php',vars,rateresult);
		// alert(rating);
	}
}

function getURLParameters(name,url)
{
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec( url );
	if( results == null )
		return "";
	else
		return results[1];
}

function track(u) {if(document.images){(new Image()).src="http://www.iconarchive.com/clicklog/_track.php?id="+u;}return true;}
function trackadblock(adblock) {if(document.images){(new Image()).src="http://www.iconarchive.com/inc/trackadblock.php?adblock="+adblock;}return true;}