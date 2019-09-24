<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//class MY_Loader extends CI_Loader {};

/*
creating admin panel in codeigniter
https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc/
http://developers.ph/codeigniter/hmvc-pattern-codeigniter-php-framework/
http://www.tutorials.kode-blog.com/codeigniter-admin-panel
http://webduos.com/create-an-admin-panel-with-codeigniter/#.VqhtFFKrE20
https://philsturgeon.uk/codeigniter/2009/07/08/Create-an-Admin-panel-with-CodeIgniter/
http://www.darwinbiler.com/ready-to-use-codeigniter-modular-extensions-hmvc/
*/


/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";
class MY_Loader extends MX_Loader {}