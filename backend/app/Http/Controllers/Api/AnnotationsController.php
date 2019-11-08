<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\PDFsController;
use Illuminate\Support\Facades\Storage;

use App\Annotation;
use App\Document;
use Auth, Hash;

class AnnotationsController extends Controller
{
    public function index(Request $request)
    {
        $annotations = Annotation::get();

        $res['data'] = $annotations;

        return $res;
    }

    public function getAnnotation(Request $request){
        $annotation = Annotation::find($request->id)->first();
        return $annotation;
    }

    public function deleteAnnotation($id)
    {
        $annotation = Annotation::find($id);
        if ($annotation == null) {
            return response(null, 404);
        }

        $annotation->delete();

        return response(null, 200);
    }

    public function addTextAnnotation(Request $request)
    {
        $in = $request->all();

        $rules = array(
            // 'annotation_id' => 'required',
            // 'creator_id' => 'required',
            'actor_id' => 'required',
            'doc_id' => 'required',
            // 'type' => 'required',
            'page_num' => 'required',
            'pos_x' => 'required',
            'pos_y' => 'required',
            'size_w' => 'required',
            'size_h' => 'required',
            'alpha' => 'required',
            'text' => 'required',
            'font_size' => 'required',
            'font_weight' => 'required',
            'font_color' => 'required',
        );

        $validator = Validator::make($in, $rules);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $annotation = new Annotation();

        $annotation->creator_id     = 1; // get from session data
        $annotation->annotation_id  = 'abcdeFGHIIJ12345';
        $annotation->actor_id       = $in['actor_id'];
        $annotation->doc_id         = $in['doc_id'];
        $annotation->type           = 'text';
        $annotation->page_num       = $in['page_num'];
        $annotation->pos_x          = $in['pos_x'];
        $annotation->pos_y          = $in['pos_y'];
        $annotation->size_w         = $in['size_w'];
        $annotation->size_h         = $in['size_h'];
        if (key_exists('z_order', $in)) {
            $annotation->z_order    = 0;
        }
        $annotation->alpha          = $in['alpha'];
        $annotation->text           = $in['text'];
        $annotation->font_family    = 'freeserif';
        $annotation->font_size      = $in['font_size'];
        $annotation->font_weight    = $in['font_weight'];
        $annotation->font_color     = $in['font_color'];

        $annotation->save();
        
        return response(null, 201);
    }

    public function updateTextAnnotation($id, Request $request)
    {
        $in = $request->all();

        $rules = array(
            // 'annotation_id' => 'required',
            // 'creator_id' => 'required',
            'actor_id' => 'required',
            'doc_id' => 'required',
            // 'type' => 'required',
            'page_num' => 'required',
            'pos_x' => 'required',
            'pos_y' => 'required',
            'size_w' => 'required',
            'size_h' => 'required',
            'z_order' => 'required',
            'alpha' => 'required',
            'text' => 'required',
            'font_size' => 'required',
            'font_weight' => 'required',
            'font_color' => 'required',
        );

        $validator = Validator::make($in, $rules);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $annotation = Annotation::find($id);
        if ($annotation == null) {
            return response(null, 404);
        }

        if ($annotation['type'] != 'text') {
            return response(['errors' => ['Invalid annotation type']], 422);
        }

        $annotation->creator_id     = 1; // get from session data
        $annotation->annotation_id  = 'abcdeFGHIIJ12345';
        $annotation->actor_id       = $in['actor_id'];
        $annotation->doc_id         = $in['doc_id'];
        $annotation->type           = 'text';
        $annotation->page_num       = $in['page_num'];
        $annotation->pos_x          = $in['pos_x'];
        $annotation->pos_y          = $in['pos_y'];
        $annotation->size_w         = $in['size_w'];
        $annotation->size_h         = $in['size_h'];
        $annotation->z_order        = $in['z_order'];
        $annotation->alpha          = $in['alpha'];
        $annotation->text           = $in['text'];
        $annotation->font_family    = 'freeserif';
        $annotation->font_size      = $in['font_size'];
        $annotation->font_weight    = $in['font_weight'];
        $annotation->font_color     = $in['font_color'];

        $annotation->save();
        
        return response(null, 200);
    }

    public function addImageAnnotation(Request $request)
    {
        $in = $request->all();

        $rules = array(
            // 'annotation_id' => 'required',
            // 'creator_id' => 'required',
            'actor_id' => 'required',
            'doc_id' => 'required',
            'page_num' => 'required',
            'pos_x' => 'required',
            'pos_y' => 'required',
            'size_w' => 'required',
            'size_h' => 'required',
            'alpha' => 'required',
            // 'text' => 'required',
            'image_url' => 'required'
        );

        $validator = Validator::make($in, $rules);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $annotation = new Annotation();

        $annotation->creator_id     = 1; // get from session data
        $annotation->annotation_id  = 'abcdeFGHIIJ67890';
        $annotation->actor_id       = $in['actor_id'];
        $annotation->doc_id         = $in['doc_id'];
        $annotation->type           = 'image';
        $annotation->page_num       = $in['page_num'];
        $annotation->pos_x          = $in['pos_x'];
        $annotation->pos_y          = $in['pos_y'];
        $annotation->size_w         = $in['size_w'];
        $annotation->size_h         = $in['size_h'];
        $annotation->z_order        = 0;
        $annotation->alpha          = $in['alpha'];
        // $annotation->image_url      = $in['image_url'];
        $annotation->image_url      = 'image-annotation001.png';

        $annotation->save();
        
        return response(null, 201);
    }

    public function updateImageAnnotation($id, Request $request)
    {
        $in = $request->all();

        $rules = array(
            // 'annotation_id' => 'required',
            // 'creator_id' => 'required',
            'actor_id' => 'required',
            'doc_id' => 'required',
            // 'type' => 'required',
            'page_num' => 'required',
            'pos_x' => 'required',
            'pos_y' => 'required',
            'size_w' => 'required',
            'size_h' => 'required',
            'z_order' => 'required',
            'alpha' => 'required',
        );

        $validator = Validator::make($in, $rules);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $annotation = Annotation::find($id);
        if ($annotation == null) {
            return response(null, 404);
        }

        
        if ($annotation['type'] != 'image') {
            return response(['errors' => ['Invalid annotation type']], 422);
        }

        $annotation->creator_id     = 1; // get from session data
        $annotation->annotation_id  = 'abcdeFGHIIJ12345';
        $annotation->actor_id       = $in['actor_id'];
        $annotation->doc_id         = $in['doc_id'];
        // $annotation->type           = 'image';
        $annotation->page_num       = $in['page_num'];
        $annotation->pos_x          = $in['pos_x'];
        $annotation->pos_y          = $in['pos_y'];
        $annotation->size_w         = $in['size_w'];
        $annotation->size_h         = $in['size_h'];
        $annotation->z_order        = $in['z_order'];
        $annotation->alpha          = $in['alpha'];
        $annotation->image_url      = $in['image_url'];

        $annotation->save();
        
        return response(null, 200);
    }

    public function addCheckBoxAnnotation(Request $request)
    {
        $in = $request->all();

        $rules = array(
            // 'annotation_id' => 'required',
            // 'creator_id' => 'required',
            'page_num' => 'required',
            'pos_x' => 'required',
            'pos_y' => 'required',
            'size_w' => 'required',
            'size_h' => 'required',
            'alpha' => 'required',
        );

        $validator = Validator::make($in, $rules);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $annotation = new Annotation();

        $annotation->creator_id     = 1; // get from session data
        $annotation->annotation_id  = 'abcdeFGHIIJ67890';
        $annotation->actor_id       = $in['actor_id'];
        $annotation->doc_id         = $in['doc_id'];
        $annotation->type           = 'checkbox';
        $annotation->page_num       = $in['page_num'];
        $annotation->pos_x          = $in['pos_x'];
        $annotation->pos_y          = $in['pos_y'];
        $annotation->size_w         = $in['size_w'];
        $annotation->size_h         = $in['size_h'];
        $annotation->z_order        = 0;
        $annotation->alpha          = $in['alpha'];

        $annotation->save();
        
        return response(null, 201);
    }

    public function updateCheckBoxAnnotation($id, Request $request)
    {
        $in = $request->all();

        $rules = array(
            // 'annotation_id' => 'required',
            // 'creator_id' => 'required',
            'actor_id' => 'required',
            'doc_id' => 'required',
            // 'type' => 'required',
            'page_num' => 'required',
            'pos_x' => 'required',
            'pos_y' => 'required',
            'size_w' => 'required',
            'size_h' => 'required',
            'z_order' => 'required',
            'alpha' => 'required',
        );

        $validator = Validator::make($in, $rules);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $annotation = Annotation::find($id);
        if ($annotation == null) {
            return response(null, 404);
        }

        
        if ($annotation['type'] != 'checkbox') {
            return response(['errors' => ['Invalid annotation type']], 422);
        }

        $annotation->creator_id     = 1; // get from session data
        $annotation->annotation_id  = 'abcdeFGHIIJ12345';
        $annotation->actor_id       = $in['actor_id'];
        $annotation->doc_id         = $in['doc_id'];
        // $annotation->type           = 'image';
        $annotation->page_num       = $in['page_num'];
        $annotation->pos_x          = $in['pos_x'];
        $annotation->pos_y          = $in['pos_y'];
        $annotation->size_w         = $in['size_w'];
        $annotation->size_h         = $in['size_h'];
        $annotation->z_order        = $in['z_order'];
        $annotation->alpha          = $in['alpha'];

        $annotation->save();
        
        return response(null, 200);
    }

    public function addRadioButtonAnnotation(Request $request)
    {
        $in = $request->all();

        $rules = array(
            // 'annotation_id' => 'required',
            // 'creator_id' => 'required',
            'page_num' => 'required',
            'pos_x' => 'required',
            'pos_y' => 'required',
            'size_w' => 'required',
            'size_h' => 'required',
            'value' => 'required',
            'alpha' => 'required',
        );

        $validator = Validator::make($in, $rules);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $annotation = new Annotation();

        $annotation->creator_id     = 1; // get from session data
        $annotation->annotation_id  = 'abcdeFGHIIJ67890';
        $annotation->actor_id       = $in['actor_id'];
        $annotation->doc_id         = $in['doc_id'];
        $annotation->type           = 'radiobutton';
        $annotation->page_num       = $in['page_num'];
        $annotation->pos_x          = $in['pos_x'];
        $annotation->pos_y          = $in['pos_y'];
        $annotation->size_w         = $in['size_w'];
        $annotation->size_h         = $in['size_h'];
        $annotation->value         = $in['value'];
        $annotation->z_order        = 0;
        $annotation->alpha          = $in['alpha'];

        $annotation->save();
        
        return response(null, 201);
    }

    public function updateRadioButtonAnnotation($id, Request $request)
    {
        $in = $request->all();

        $rules = array(
            // 'annotation_id' => 'required',
            // 'creator_id' => 'required',
            'actor_id' => 'required',
            'doc_id' => 'required',
            // 'type' => 'required',
            'page_num' => 'required',
            'pos_x' => 'required',
            'pos_y' => 'required',
            'size_w' => 'required',
            'size_h' => 'required',
            'value' => 'required',
            'z_order' => 'required',
            'alpha' => 'required',
        );

        $validator = Validator::make($in, $rules);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $annotation = Annotation::find($id);
        if ($annotation == null) {
            return response(null, 404);
        }

        
        if ($annotation['type'] != 'radiobutton') {
            return response(['errors' => ['Invalid annotation type']], 422);
        }

        $annotation->creator_id     = 1; // get from session data
        $annotation->annotation_id  = 'abcdeFGHIIJ12345';
        $annotation->actor_id       = $in['actor_id'];
        $annotation->doc_id         = $in['doc_id'];
        // $annotation->type           = 'image';
        $annotation->page_num       = $in['page_num'];
        $annotation->pos_x          = $in['pos_x'];
        $annotation->pos_y          = $in['pos_y'];
        $annotation->size_w         = $in['size_w'];
        $annotation->size_h         = $in['size_h'];
        $annotation->value          = $in['value'];
        $annotation->z_order        = $in['z_order'];
        $annotation->alpha          = $in['alpha'];

        $annotation->save();
        
        return response(null, 200);
    }

    public function addAnnotation(Request $request){
        $data = $request->all();
        $data['creator_id'] = $request->user()->id;
        $annotation = new Annotation();
        if(isset($data['id'])){
            $annotation = Annotation::find($data['id']);
            if(isset($annotation)) {
                unset($annotation['id']);
                foreach ($data as $key => $value) {
                    $annotation[$key] = $value;
                }
            } else {
                return response([
                    'status' => true,
                ], 200);
            }
        }else {
            foreach ($data as $key => $value) {
                $annotation[$key] = $value;
            }
        }
        $annotation->save();
        return response([
            'status' => true,
            'annotation_id' => $annotation['id']
        ], 200);
    }

    public function getAnnotationByDocumentId(Request $request){
        $document_id = $request->document_id;
        $list = Document::with('annotations')->where("document_id", $document_id)->get();
        $annotations = [];
        foreach ($list as $document) {  
            foreach ($document['annotations'] as $key => $value) {
                if($value['display'] === 'block') {
                    array_push($annotations,$value);
                }
            }    
        }
        return response()->json([
            'status' => true,
            'annotations' => $annotations
        ],200);
    }

}
