<?php
header("Cache-Control: no-cache, no-store");

$controller=$this->router->fetch_class();
$method=$this->router->fetch_method();

//echo $method;


?>
<!DOCTYPE HTML>
<html class="no-js" lang="">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<title><?php if(!empty($title)) { echo $title.' | '; } ?>
<?php if($lang == 'hi'){?> राष्ट्रीय सूचना विज्ञान केंद्र, भारत सरकार की वेबकास्ट सेवाएं
<?php } else{?>Webcast Services of National Informatics Centre, Government of India<?php }?>
</title>
<!-- Linking CSS -->

<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/owl.carousel.css">
<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/base.css">


<link rel="apple-touch-icon" href="<?php echo base_url(); ?>assets/images/favicon/apple-touch-icon.png">
<link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon/favicon.png" />
<!-- Linking JavaScript -->

<script type="text/javascript">

function burstCache() {

	if (!navigator.onLine) {
		document.body.innerHTML = 'Not Available';
	}
}

</script>



</head>




<body onLoad="burstCache();" id="fontSize" class="<?php echo $lang; ?>">

<header>
    <div class="container">
        <div  id="accessible" class="header-right push-right">
            <div class="select-option">
                <select id="sortby" name="sortby" class="form-control" onchange="if('' != this.value) { window.open(this.value); }">
                    <option value="">Other Webcast Sites</option>
                    <option value="https://pmindiawebcast.nic.in/">pmindiawebcast.nic.in</option>
                    <option value="https://republicday.nic.in/">republicday.nic.in</option>
                    <option value="https://independenceday.nic.in/">independenceday.nic.in</option>
                    <option value="https://yogaday.nic.in/">yogaday.nic.in</option>
                    <option value="https://budgetlive.nic.in/">budgetlive.nic.in</option>
                    <option value="https://pmonradio.nic.in/">pmonradio.nic.in</option>
                    <option value="http://rathjatra.nic.in/">rathjatra.nic.in</option>
                </select>
            </div>
            <ul id="header-nav" class="accessibility-nav">
                <li class="ico-site-search"><a title="Site Search" id="toggleSearch" href="javascript:void(0);"><em class="fa fa-search"></em><span class="hide">Search</span></a>
                    <ul class="search-drop">
                        <li>
                            <form method="get" action="<?php echo base_url(); ?>search?lang=<?php echo $lang; ?>">
                                <input required type="text" name="s" placeholder="Search" value="<?php echo (isset($search_string)) ? $search_string : ''; ?>">
                                <input type="submit" value="Go" class="bttn-search" title="Search">
                            </form>
                        </li>
                    </ul>
                </li>
                <li><a title="Accessibility Dropdown" id="toggleAccessibility" href="javascript:void(0);"><em class="fa fa-wheelchair"></em><span class="hide">Accessibility Links</span></a>
                    <ul>
                        <li> <a href="javascript:void(0);" title="Increase font size" aria-label="Increase font size" onClick="set_font_size('increase')">A<sup>+</sup></a> </li>
                        <li> <a href="javascript:void(0);" title="Reset font size" aria-label="Reset font size" onClick="set_font_size('')">A<sup>&nbsp;</sup></a> </li>
                        <li> <a href="javascript:void(0);" title="Decrease font size" aria-label="Decrease font size" onClick="set_font_size('decrease')">A<sup>-</sup></a> </li>
                        <li> <a title="High Contrast" aria-label="High Contrast" class="high-contrast dark" href="javascript:void(0);">A</a> </li>
                        <li> <a title="Normal Contrast" aria-label="Normal Contrast" class="high-contrast light" href="javascript:void(0);">A</a> </li>
                    </ul>
                </li>
                <li class="hindi language">
                    <?php if(!isset($lang) || $lang == 'en'){?>
                        <a href='?lang=hi' title='हिंदी संस्करण के लिए लिंक'>हिन्दी</a>
                    <?php }else{ ?>
                        <a href='?lang=en' title='Link to English Version'>English</a>
                    <?php } ?>
                </li>
            </ul>
        </div>
        <a href="<?php echo base_url(); ?>?lang=<?php echo $lang; ?>" class="main-logo push-left">
            <img src="<?php echo base_url(); ?>assets/images/emblem.png" alt="State Emblem of India">
            <div class="logo-text">
                <h1>webcast</h1>
                <span>Government Video Portal</span>
            </div>
        </a>
    </div>
</header>

  <?php echo $body; ?>
<footer>
    <div class="container">

        <ul>
            <li><a href="<?php echo base_url(); ?>help">Help</a></li>
            <li><a target="_blank" href="https://servicedesk.nic.in/">Contact</a></li>
            <li><a href="<?php echo base_url(); ?>services">Webcast Services</a></li>
        </ul>
        <div class="footer-logos">
            <a href="https://nic-cert.nic.in/index.jsp" target="_blank"><img src="<?php echo base_url(); ?>assets/images/logo-nic-cert.jpg" alt="NIC CERT"></a>
            <a href="http://ceda.gov.in/" target="_blank"><img src="<?php echo base_url(); ?>assets/images/logo-ceda.jpg" alt="CEDA"></a>
            <a href="https://egovmobileapps.nic.in/" target="_blank"><img src="<?php echo base_url(); ?>assets/images/logo-egovmobileapps.jpg" alt="E-Gov Mobile Apps"></a>
        </div>
        <div class="footer-logo-cntr">
            <img src="<?php echo base_url(); ?>assets/images/nic_logo.png" alt="">
            <span class="footer-nictext">
                <?php if($lang == 'en'){ ?>
                Designed, Developed and Hosted by <br>
                <a href="https://meity.gov.in" target="_blank">Ministry of Electronics and Information Technology</a>
                <?php }else{ ?>
                इस वेबसाईट का निर्माण, विकास, होस्टिंग एवं रख-रखाव द्वारा <br>
                <a href="https://meity.gov.in/hi/"> इलेक्ट्रॉनिक्स और सूचना प्रौद्योगिकी मंत्रालय</a>
                <?php } ?>
            </span>
        </div>
    </div>
</footer>
</body>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-3.2.1.min.js"></script>
<script type='text/javascript' src='<?php echo base_url(); ?>assets/js/owl.carousel.js'></script>
<script type='text/javascript' src='<?php echo base_url(); ?>assets/js/font-size.js'></script>
<script type='text/javascript' src='<?php echo base_url(); ?>assets/js/swithcer.js'></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom.js"></script>
</html>
