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

<title><?php if(!empty($data[$controller]['title'])) { echo $data[$controller]['title'].' | '; } ?> 
<?php
if($ln=='hi')
{
?>
राष्ट्रीय सूचना विज्ञान केंद्र, भारत सरकार की वेबकास्ट सेवाएं
<?php
}
else
{
?>
Webcast Services of National Informatics Centre, Government of India
<?php
}
?>
</title>
<!-- Linking CSS -->

<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style_dev.css">
<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/error.css">

<link rel="apple-touch-icon" href="<?php echo base_url(); ?>assets/images/favicon/apple-touch-icon.png">
<link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon/favicon.png" />
<!-- Linking JavaScript -->

<script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/additional-methods.js"></script>

<script src="<?php echo base_url(); ?>assets/js/jquery.form.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sha256.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>



<script type="text/javascript">

function burstCache() {
	
	if (!navigator.onLine) {
		document.body.innerHTML = 'Not Available';
	}
}

</script>



</head>




<body onLoad="burstCache();">

  <?php echo $body; ?>
  

</body>
</html>
