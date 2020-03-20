<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_pdf {
    
    function m_pdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }
 
    function load($mode='en-GB-x', $format='A4', $font_size='', $font='', $margin_left=10, $margin_right=10, $margin_top=10, $margin_bottom=10, $margin_header=10, $margin_footer=6, $orientation=3)
    {
        include_once APPPATH.'/third_party/mpdf60/mpdf.php';
		
			/**
			 * Create a new PDF document
			 *
			 * @param string $mode
			 * @param string $format
			 * @param int $font_size
			 * @param string $font
			 * @param int $margin_left
			 * @param int $margin_right
			 * @param int $margin_top (Margin between content and header, not to be mixed with margin_header - which is document margin)
			 * @param int $margin_bottom (Margin between content and footer, not to be mixed with margin_footer - which is document margin)
			 * @param int $margin_header
			 * @param int $margin_footer
			 * @param string $orientation (P, L)
			 */
		
         
     	 return new mPDF($mode, $format, $font_size, $font, $margin_left, $margin_right, $margin_top, $margin_bottom, $margin_header, $margin_footer, $orientation);
    }
}