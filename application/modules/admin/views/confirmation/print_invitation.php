<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$validH=$this->validation;

$csrfToken = array(
	'name' => $this->security->get_csrf_token_name(),
	'hash' => $this->security->get_csrf_hash()
);
?>

<?php
if($link_invalid==1) 
{
	echo $this->validation->xssSafe($link_error);
}
else
{
	echo $invitation_html;
}
?>