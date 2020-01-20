<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
include_once APPPATH . '/third_party/mpdf/mpdf.php';

class Pdf {

    public $collegeinfo;
 function __construct()
 {
     $this->collegeinfo = get_collage_info();
 }

    public function __get($var)
    {
        return get_instance()->$var;
    }


    function startPDF($param="A4",$font=10){
       return new mPDF('',$param,$font,'',10,10,22,20,5,2);
    }


    function PDFHeader(){

        $header = '
<table width="100%;" style="border-bottom: 2px solid #000;">
<tr>
<th width="15%">
<img style="height: 45px; width: 70px;" src="./images/logo.jpg">
</th>
<td width="90%" ><h3>'.$this->collegeinfo->Name.' </h3><div style="font-size:12px;">'.
            $this->collegeinfo->PostalAddress.' '.
            $this->collegeinfo->City.' . Email : '.$this->collegeinfo->Email.' , Website : '.$this->collegeinfo->Site.'</div></td>

</tr>
</table>';

        return $header;
    }

    function TRANSCRIPTHeader(){

        $header = '
<table width="100%;" style="border-bottom: 2px solid #000;">
<tr>
<th width="15%">
<img style="height: 45px; width: 70px;" src="./images/logo.jpg">
</th>
<td width="90%" ><h3>'.$this->collegeinfo->Name.' </h3><div style="font-size:12px;">'.
            $this->collegeinfo->PostalAddress.' '.
            $this->collegeinfo->City.' . Email : '.$this->collegeinfo->Email.' , Website : '.$this->collegeinfo->Site.'</div></td>

</tr>
</table>';

        return $header;
    }

    function PDFFooter(){
        $footer = '<table width="100%;" style="border-top: 2px solid #000;">
<tr>
<td width="33%" style="font-style: italic; font-size: 6pt;">'.$this->collegeinfo->Name.'<br/>Printed on :'.date('d/m/Y , H:i:s').'</td>
<td width="33%" style="text-align:center; font-style: italic; font-size: 8pt;">Page {PAGENO}/{nb}</td>
<td width="33%" style="text-align:right; font-style: italic; font-size: 6pt;">ZALONGWA SOFTWARE V2</td>
</tr>
</table>';

        return $footer;
    }


}
