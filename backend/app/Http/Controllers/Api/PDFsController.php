<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use setasign\Fpdi;
use setasign\FpdfTpl;
use Imagick;
use Request as rq;

use App\Document;
use App\DocumentAction;
use App\Annotation;
use NcJoes\OfficeConverter\OfficeConverter;
use Illuminate\Support\Facades\Storage;


class PDFsController extends Controller
{
    public function export(Request $request)
    {
        $document_id = $request->document_id;
        $recipient_id = $request->recipient_id;

        // $document = Document::find($id);
        // if (!$document) {
        //     // return response(null, 404);
        // }
        // return $document;
        // $annotations = Annotation::where('doc_id', 1)->orderBy('z_order', 'asc')->get();
        $list = Document::with(['annotations'=>function($query) use ($recipient_id){
                    $query->where('actor_id','=',$recipient_id);
                }])->where("document_id",'=', $document_id)->get();

        // return $list;

        $annotations = [];
        $document = null;
        foreach ($list as $document) {  
            $document = $document;
            foreach ($document['annotations'] as $key => $value) {
                array_push($annotations,$value);
            }    
            break;
        }

        // return $annotations;
        // print_r($annotations);
        $pdf = new Fpdi\TcpdfFpdi();
        $pdf->setPrintHeader(false);
        // $pdf = new \setasign\Fpdi\Fpdi();
        $pageCount = $pdf->setSourceFile(public_path().'/storage/'.$document->document_file);
        // return $pageCount;
        foreach (range(1, $pageCount) as $i) {
            $pdf->AddPage();

            $page = $i;
            $tplId = $pdf->importPage($page);
            $size = $pdf->getTemplateSize($tplId);
            // return $size;
            $pdf->useTemplate($tplId, 0, 0, $size['width'], $size['height'], true);
            
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->setJPEGQuality(75);
            // dd($pdf);

            foreach ($annotations as $annotation) {
                if (intval($annotation->page_num) == $page) {
                    $font_weight = $annotation->font_weight;
                    if ($font_weight == 'N') {
                        $font_weight = '';
                    }
                    $pdf->SetFont($annotation->font_family, $font_weight, 12);
                    $hexColor = $annotation->font_color;
                    list($r, $g, $b) = sscanf($hexColor, '%02x%02x%02x');
                    $pdf->SetTextColor($r, $g, $b);
                    $annotation->pos_x = ($annotation->pos_x * $size['width']) / $annotation->width;
                    $annotation->pos_y = ($annotation->pos_y * $size['height']) / $annotation->height;
                    // print_r($annotation->pos_x);
                    // print_r("<br/>");
                    // print_r($annotation->pos_y);
                    // print_r("<br/>");
                    // $pdf->SetXY(intval($annotation->pos_x), intval($annotation->pos_y));
                    $pdf->SetXY(intval($annotation->pos_x), intval($annotation->pos_y));
                    switch ($annotation->type) {
                    case 'text':
                        $pdf->Write($page, "THIS IS TEXT");
                        break;
                    case 'checkbox':
                        $pdf->CheckBox($annotation->annotation_id, 5, true, array(), array(), 'OK');
                        break;
                    case 'radiobutton':
                        $pdf->RadioButton('radio', 5, array(), array(), 'OK');
                        break;
                    case 'image':
                        $pdf->Image(public_path().'/i001.jpg', '', '', intval($annotation->size_w), intval($annotation->size_h), '', '',
                            'T', false, 300, '', false, false, 1, false, false, false
                        );
                        break;
                    }
                }
            }
        }
        // return "ok";
        $pathToFile = public_path().'/export_documents/'.$document_id.'.pdf';
        $pdf->Output($pathToFile, 'F');
        return response()->file($pathToFile);
    }

    public function convertPDFToImage(Request $request){
        $document_file = $request->document_file;
        $page = $request->page -1;
        $document_file = $document_file."[".$page."]";
        $imagick = new Imagick();
        $resolution = 144;
        if(isset($request->resolution)){
            switch ($request->resolution) {
                case 1:
                    $resolution = 360;
                    break;
                case 2:
                    $resolution = 150;
                    break;            
                default:
                    $resolution = 50;
                    break;
            }
        }
        $imagick->setResolution($resolution,$resolution);   
        $imagick->readImage('storage/'.$document_file);
        $imagick->setImageFormat('png');
        // $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
        // $imagick->setImageCompressionQuality(1);
        header('Content-Type: image/png');
        echo $imagick->getImage();
        $imagick->destroy();
        // return $this->convertUnoconv(storage_path().'/app/public/'.$document_file,'png');
    }

    public function createObjImage($document_file,$document_id){
        $arrImage = [];
        $rootPath = rq::root();
        try {
            $pdf = new \Spatie\PdfToImage\Pdf('storage/'.$document_file);
            $pdf->setCompressionQuality(100);    
            $name = basename($document_file,'.pdf');
            // $pdf = new Imagick('storage/'.$document_file);
            foreach (range(1, $pdf->getNumberOfPages()) as $pageNumber) {
                // $url = $rootPath.'/api/pdf/convert-to-image?document_file='.$document_file.'&page='.$pageNumber.'&resolution=';
                if (!file_exists("images_documents/".$document_id)) {
                    mkdir("images_documents/".$document_id, 0777, true);
                }
                $url = $rootPath."/images_documents/".$document_id."/".$name."_".$pageNumber.".png";
                if(!file_exists(public_path()."/images_documents/".$document_id."/".$name."_".$pageNumber.".png")){
                    $pdf->setPage($pageNumber)->setOutputFormat('png')->saveImage("images_documents/".$document_id."/".$name."_".$pageNumber.".png");
                }
                array_push($arrImage, $url);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return $arrImage;
    }

    // public function checkActionDocument($id, $page){
    //     $document_action = DocumentAction::where([['id','=',$id],['page','=',$page]])->first();
    //     if(isset($document_action)){
    //         return $document_action->delete == 1 ? -1 : $document_action->rotate;
    //     }else{
    //         return 0;
    //     }
    // }

    public function convertUnoconv($originFilePath, $toFormat = 'pdf'){
        try {
            $command = 'unoconv --format %s %s';
            // $command = 'unoconv --format %s --output %s %s';
            $command = sprintf($command, $toFormat, $originFilePath);
            system($command, $output);
            return $output;
        } catch (\Throwable $th) {
            print_r($th);
        }
    }

    public function convertFileToPDFAPI($document_file, $file_name){
        try {
            $this->convertUnoconv(storage_path().'/app/public/'.$document_file);
            // $converter = new OfficeConverter('storage/'.$document_file);
            // $converter->convertTo($file_name.'.pdf');
            $document_file = 'uploads/files/'. $file_name.'.pdf';
        } catch (\Throwable $th) {
            print_r($th);
        }
        return $document_file;
    }
}
