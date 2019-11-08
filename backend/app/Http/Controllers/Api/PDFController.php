<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PDFController extends Controller
{
    public function addText(Request $request)
    {
        $pdf = new \setasign\Fpdi\Fpdi();

        $pdf->AddPage();

        $pdf->setSourceFile('../1.pdf');
        $tplIdx = $pdf->importPage(1);
        $size = $pdf->getTemplateSize($tplIdx);
        $pdf->useTemplate($tplIdx, 0, 0, $size['width'], $size['height'], true);

        $pdf->SetFont('Arial', '', '12');
        $pdf->SetTextColor(255, 0, 0);
        $pdf->SetXY(75, 70);

        $pdf->Write(0, 'Kurt Warson reviewed on 6th May.');

        $pdf->SetFont('Arial', '', '12');
        $pdf->SetTextColor(0, 0, 255);
        $pdf->SetXY(75, 132);

        $pdf->Write(0, 'Administrator reviewed on 7th May.');

        $pdf->Output('updated.pdf', 'D');

        return response(null, 200);
    }

}
