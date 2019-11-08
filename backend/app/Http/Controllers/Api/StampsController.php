<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

use App\Stamp;
use App\User;

class StampsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $user_id = User::with('client')->find(Auth::id());

      $stamps = Stamp::where("user_id", $user_id->client->id)->get();

      if(!is_null($stamps)) {
        foreach ($stamps as $s) {
          $url = Storage::url($s->uploaded_url);
          $s->uploaded_url = asset($url); 
        }
        $response = $this->successfulMessage(200, 'Successfully', true, $stamps->count(), $stamps);
      }
      else {
        $response = $this->notFoundMessage();
      }

      return response(json_encode($response));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'stamp_type' => 'required|string',
        'stamp_text' => 'required|string',
        'font_face' => 'required|string',
        'font_size' => 'required|string'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'errors' => $validator->errors()->all()
        ], 422);
      }

      // Define Location and Filename
      $destinationPath = storage_path().'/app/public/signstamps/';
      // $ext = '.png'; 
      $targetFile = $destinationPath;
      $originalFile = $request->uploaded_url;
      list($newWidth, $newHeight) = getimagesize($originalFile);

      // Uploading to Storage
      $resized = $this->resize($newWidth,  $targetFile, $originalFile);

      /** Create Stamp */  
      $user_id = User::with('client')->find(Auth::id());

      $stamp = new Stamp();
      $stamp->user_id = $user_id->client->id;
      $stamp->stamp_type = $request->stamp_type;
      $stamp->title = $request->stamp_title;
      $stamp->text = $request->stamp_text;
      $stamp->font_face = $request->font_face;
      $stamp->font_size = $request->font_size;
      $stamp->language = $request->language;
      $stamp->uploaded_url = 'signstamps/' . $resized;
      $stamp->save();
      
      $url = Storage::url($stamp->uploaded_url);
      $stamp->uploaded_url = asset($url);

      $response = $this->successfulMessage(201, 'Successfully created', true, $stamp->count(), $stamp);

      return response(json_encode($response));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Stamp  $stamp
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
      $stamp = Stamp::find($request->id)->first();

      if(!is_null($stamp)) {
        $response = $this->successfulMessage(200, 'Successfully', true, $stamp->count(), $stamp);
      }
      else {
        $response = $this->notFoundMessage();
      }

      return response($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stamp  $stamp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'stamp_type' => 'required|string',
        'stamp_text' => 'required|string',
        'font_face' => 'required|string',
        'font_size' => 'required|string'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'errors' => $validator->errors()->all()
        ], 422);
      }

      $stamp = Stamp::find($request->id)->first();
      $stamp->stamp_type = $request->stamp_type;
      $stamp->title = $request->stamp_title;
      $stamp->text = $request->stamp_text;
      $stamp->font_face = $request->font_face;
      $stamp->font_size = $request->font_size;
      $stamp->save();

      $response = $this->successfulMessage(201, 'Successfully updated', true, $stamp->count(), $stamp);

      return response($response);
    }

    /**
     * 
     * Upload file to storage
     * 
     * @param Request $request
     * 
     * 
     */    
    public function upload(Request $request) 
    {
      $imageBase = $request->image;
      if (false === $imageBase) {
        // throw new Zend_Filter_Exception('Can\'t load image: ' . $imageBase);
        if ($validator->fails()) {
          return response()->json([
            'errors' => 'Can\'t load image: '
          ], 422);
        }
      }

      // Define Location and Filename
      $destinationPath = storage_path().'/app/public/signstamps/';
      // $ext = '.png';      
      $newWidth = 150;
      $targetFile = $destinationPath;
      $originalFile = $request->image;

      // Uploading to Storage
      $resized = $this->resize($newWidth,  $targetFile, $originalFile);

      /** Create Stamp */      
      $user_id = User::with('client')->find(Auth::id());

      $stamp = new Stamp();
      $stamp->user_id = $user_id->client->id;
      $stamp->stamp_type = 'Upload';
      $stamp->text = 'Generated';
      $stamp->font_face = 'Generated';
      $stamp->font_size = 'Generated';
      $stamp->language = 'English';
      $stamp->uploaded_url = 'signstamps/' . $resized;
      $stamp->save();
      
      $url = Storage::url($stamp->uploaded_url);
      $stamp->uploaded_url = asset($url);

      $response = $this->successfulMessage(201, 'Successfully updated', true, $stamp->count(), $stamp);

      return response(json_encode($response));
    }

    /**
     * Set request to default
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function setDefault(Request $request)
    {
      // Remove user's other default signature
      $user_id = User::with('client')->find(Auth::id());

      $stamps = Stamp::where([
        ['user_id', '=', $user_id->client->id],
        ['default', '=', '1'],
      ])->get();

      if(is_null($stamps)) {
        return response(json_encode($this->notFoundMessage()));
      }      
      foreach ($stamps as $s) {
        $s->default = null;
        $s->save();
      }

      $stamp = Stamp::find($request->id)->first();
      if(is_null($stamp)) {
        $response = $this->notFoundMessage();
      }
      else {
        $stamp->default = true;
        $stamp->save();
      }

      $stampall = Stamp::where('user_id', '=', $user_id->client->id)->get();
      foreach ($stampall as $s) {
        $url = Storage::url($s->uploaded_url);
        $s->uploaded_url = asset($url); 
      }

      $response = $this->successfulMessage(200, 'Successfully updated', true, 0, $stampall);

      return response(json_encode($response));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stamp  $stamp
     * @return \Illuminate\Http\Response
     */
    public function softDelete(Request $request)
    {
      $stamp = Stamp::destroy($request->id);
      if ($stamp) {
        $response = $this->successfulMessage(200, 'Successfully deleted', true, 0, $stamp);
      } else {
        $response = $this->notFoundMessage();
      }

      return response($response);
    }

    /**
     * Permanently force destroy soft delete.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function permanentDeleteSoft(Request $request) 
    {
      $stamp = Stamp::onlyTrashed()->find($request->id);

      if (!is_null($stamp)) {
        $stamp->forceDelete();
        $response = $this->successfulMessage(200, 'Successfully permanently deleted', true, 0, $stamp);
      }
      else {
        return response($response);
      }

      return response($response);
    }

    private function notFoundMessage()
    {
      return [
        'code' => 404,
        'message' => 'Stamp seal not found',
        'success' => false,
      ];
    }

    private function successfulMessage($code, $message, $status, $count, $payload)
    {
      return [
        'code' => $code,
        'message' => $message,
        'success' => $status,
        'count' => $count,
        'data' => $payload,
      ];
    }
    
    /** Reference Imagepng */
    protected function resize($newWidth, $targetFile, $originalFile) 
    {
      $info = getimagesize($originalFile);
      $mime = $info['mime'];
  
      switch ($mime) {
        case 'image/jpeg':
          $image_create_func = 'imagecreatefromjpeg';
          $image_save_func = 'imagejpeg';
          $new_image_ext = 'jpg';
          break;

        case 'image/png':
          $image_create_func = 'imagecreatefrompng';
          $image_save_func = 'imagepng';
          $new_image_ext = 'png';
          break;

        case 'image/gif':
          $image_create_func = 'imagecreatefromgif';
          $image_save_func = 'imagegif';
          $new_image_ext = 'gif';
          break;

        default: 
          throw new Exception('Unknown image type.');
      }
  
      $img = $image_create_func($originalFile);
      list($width, $height) = getimagesize($originalFile);
  
      $newHeight = ($height / $width) * $newWidth;
      $tmp = imagecreatetruecolor($newWidth, $newHeight);

      $transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
      imagefill($tmp, 0, 0, $transparent);
      imagesavealpha($tmp, true); //transparent bg

      imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
  
      // Create folder if not exist
      $destinationPath = storage_path().'/app/public/signstamps/';
      if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
      }

      $new_filename = date('YmdHis') . '_stamp_signstamp';
      $targetFile = $targetFile . $new_filename;
      if (file_exists($targetFile)) {
        unlink($targetFile);
      }

      $image_save_func($tmp, "$targetFile.$new_image_ext");
      imagedestroy($tmp);

      return "$new_filename.$new_image_ext";
    }


    /** Upload Function for Image Base64 (OLD) */
    private function uploadImageBase($image) 
    {
      // Normalize Base64
      $b64A = substr($image, strpos($image, 'base64,'));
      $b64B = str_replace('base64,', '', $b64A);
      $b64C = strtr($b64B, '-_', '+/');
      $b64D = rtrim($b64C, '=');

      // Decode Base64
      $b64E = base64_decode($b64D, true);
      
      // Create Image
      $im = imageCreateFromString($b64E);
      if (!$im) {
        die('Base64 value is not a valid image');
      }

      $upload_name = date('YmdHis') . '_stamp_signstamp.png';
      $destinationPath = storage_path().'/app/public/signstamps/';
      if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
      }
      $path = $destinationPath . $upload_name;

      imagesavealpha($im, true);
      imagepng($im, $path, 0);
      imagedestroy($im);
      
      if (file_exists($path))
        return $upload_name;
      else
        return null;
    }
    /** Get Extension File Base64 */
    private function getExtentionBase64($data){
      $pos  = strpos($data, ';');
      $type = explode(':', substr($data, 0, $pos))[1];
      $ext = explode('/',$type)[1];
      return $ext;
    }
}
