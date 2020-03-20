
function getCookie(name) {
var re = new RegExp(name+"=[^;]+", "i"); //construct RE to search for target name/value pair
if (document.cookie.match(re)) //if cookie found
return document.cookie.match(re)[0].split("=")[1] //return its value
return null
}

function setCookie(name, value, days) {

    var thirtyDays = 1000 * 60 * 60 * 24 * days;
    var expireDate = new Date((new Date()).valueOf() + thirtyDays);

document.cookie = name+"="+value+"; expires="+expireDate.toGMTString()+"; path=/";
}


jQuery(document).ready(function(){

jQuery('.dark').click(function(){

    setCookie('contrast',1);

jQuery('head').append('<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/change.css">');
});
jQuery('.light').click(function(){

    jQuery("[href*='change.css']").remove();
    setCookie('contrast',0);
});

if(getCookie('contrast') == "1") {

}
if(getCookie('contrast')){
    if(getCookie('contrast') == '0') {

        jQuery("[href*='change.css']").remove();

    }else if(getCookie('contrast') == "1"){

        jQuery('head').append('<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/change.css">');
    }
}

});
