<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use TCPDF;

class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;

        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);

        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, 20);

        // set the starting point for the page content
        $this->setPageMark();
    }
}

class AppartmentPDF extends Model
{
    use HasFactory;
   
    public function download($building)
    {
        $view = view('admin.pages.apartment.pdf',[ 'building' => $building ]);
        $html = $view->render();

    	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetMargins(20, 0 , 20);

        // set some language dependent data:
        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'rtl';
        $lg['a_meta_language'] = 'fa';
        $lg['w_page'] = 'page';

        // set some language-dependent strings (optional)
        $pdf->setLanguageArray($lg);

        // set font
        $pdf->SetFont('aealarabiya', '', 14);
        
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // add a page
        $pdf->AddPage();

        // output the HTML content
        $pdf->writeHTML($html, true, 0, true, 0);

        //Close and output PDF document
        $pdf->Output('Apartment.pdf', 'I');
    }
}
