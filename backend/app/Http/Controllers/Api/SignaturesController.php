<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Signature;
use App\User;

class SignaturesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {      
      $user_id = User::with('client')->find(Auth::id());

      $signatures = Signature::where("user_id", $user_id->client->id)->get();

      if(!is_null($signatures)) {
        foreach ($signatures as $s) {
          $url = Storage::url($s->uploaded_url);
          $s->uploaded_url = asset($url); 
          $url = Storage::url($s->initial_uploaded_url);
          $s->initial_uploaded_url = asset($url); 
        }
        $response = $this->successfulMessage(200, 'Successfully', true, $signatures->count(), $signatures);
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
        'signature_type' => 'required|string',
        'signature_text' => 'required|string',
        'font_face' => 'required|string',
        'font_size' => 'required|string'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'errors' => $validator->errors()->all()
        ], 422);
      }

      // Defina Location and Filename
      $destinationPath = storage_path().'/app/public/signstamps/';
      $newWidth = 150;
      $targetFile = $destinationPath;
      $signBase = $request->uploaded_url;
      $initialBase = $request->initial_uploaded_url;

      // Uploading to Storage
      $signResized = $this->resize(($newWidth + 50),  $targetFile, $signBase, 'sign');
      $initialResized = $this->resize($newWidth,  $targetFile, $initialBase, 'initial');

      /** Create Signature */  
      $user_id = User::with('client')->find(Auth::id());

      $sign = new Signature();
      $sign->user_id = $user_id->client->id;
      $sign->signature_type = $request->signature_type;
      $sign->initial = $request->initial;
      $sign->text = $request->signature_text;
      $sign->font_face = $request->font_face;
      $sign->font_size = $request->font_size;
      $sign->language = $request->language;
      $sign->uploaded_url = 'signstamps/' . $signResized;
      $sign->initial_uploaded_url = 'signstamps/' . $initialResized;
      $sign->save();

      $url = Storage::url($sign->uploaded_url);
      $sign->uploaded_url = asset($url);
      $url = Storage::url($sign->initial_uploaded_url);
      $sign->initial_uploaded_url = asset($url);
      
      $response = $this->successfulMessage(201, 'Successfully created', true, $sign->count(), $sign);

      return response(json_encode($response));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Signature  $signature
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
      $sign = Signature::find($request->id);

      if(!is_null($sign)) {
        $response = $this->successfulMessage(200, 'Successfully', true, $sign->count(), $sign);
      }
      else {
        $response = $this->notFoundMessage();
      }

      return response(json_encode($response));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Signature  $signature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'signature_type' => 'required|string',
        'signature_text' => 'required|string',
        'font_face' => 'required|string',
        'font_size' => 'required|string'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'errors' => $validator->errors()->all()
        ], 422);
      }

      $sign = Signature::find($request->id)->first();
      $sign->signature_type = $request->signature_type;
      $sign->initial = $request->signature_initial;
      $sign->text = $request->signature_text;
      $sign->font_face = $request->font_face;
      $sign->font_size = $request->font_size;
      $sign->save();

      $response = $this->successfulMessage(201, 'Successfully updated', true, $sign->count(), $sign);

      return response(json_encode($response));
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
      $signBase = $request->sign_image;
      $initialBase = $request->initial_image;
      if (false === $signBase || false === $initialBase) {
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

      // Uploading to Storage
      $signResized = $this->resize(($newWidth + 50),  $targetFile, $signBase, 'sign');
      $initialResized = $this->resize($newWidth,  $targetFile, $initialBase, 'initial');

      /** Create Signature */
      $user_id = User::with('client')->find(Auth::id());

      $sign = new Signature();
      $sign->user_id = $user_id->client->id;
      $sign->signature_type = 'Upload';
      $sign->text = 'Generated';
      $sign->font_face = 'Generated';
      $sign->font_size = 'Generated';
      $sign->language = 'English';
      $sign->uploaded_url = 'signstamps/' . $signResized;
      $sign->initial_uploaded_url = 'signstamps/' . $initialResized;
      $sign->save();

      $url = Storage::url($sign->uploaded_url);
      $sign->uploaded_url = asset($url);
      $url = Storage::url($sign->initial_uploaded_url);
      $sign->initial_uploaded_url = asset($url);

      $response = $this->successfulMessage(201, 'Successfully updated', true, $sign->count(), $sign);

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

      $signatures = Signature::where([
          ['user_id', '=', $user_id->client->id],
          ['default', '=', '1'],
        ])->get();

      if(is_null($signatures)) {
        return response(json_encode($this->notFoundMessage()));
      }
      foreach ($signatures as $s) {
        $s->default = null;
        $s->save();
      }

      $signature = Signature::find($request->id);
      if(is_null($signature)) {
        return response(json_encode($this->notFoundMessage()));
      }
      else {
        $signature->default = true;
        $signature->save(); 
      }

      $signatureall = Signature::where('user_id', '=', $user_id->client->id)->get();
      foreach ($signatureall as $s) {
        $url = Storage::url($s->uploaded_url);
        $s->uploaded_url = asset($url); 
        $url = Storage::url($s->initial_uploaded_url);
        $s->initial_uploaded_url = asset($url); 
      }
      $response = $this->successfulMessage(200, 'Successfully updated', true, 0, $signatureall);

      return response(json_encode($response));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function softDelete(Request $request)
    {
      $sign = Signature::destroy($request->id);
      if ($sign) {
        $response = $this->successfulMessage(200, 'Successfully deleted', true, 0, $sign);
      } else {
        $response = $this->notFoundMessage();
      }

      return response(json_encode($response));
    }

    /**
     * Permanently force destroy soft delete.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function permanentDeleteSoft(Request $request) 
    {
      $sign = Signature::onlyTrashed()->find($request->id);

      if (!is_null($sign)) {
        $sign->forceDelete();
        $response = $this->successfulMessage(200, 'Successfully permanently deleted', true, 0, $sign);
      }
      else {
        return response(json_encode($response));
      }

      return response(json_encode($response));
    }

    /**
     * Utils & Helper
     */
    private function notFoundMessage()
    {
      return [
        'code' => 404,
        'message' => 'Signature not found',
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
    protected function resize($newWidth, $targetFile, $originalFile, $type) 
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

      $new_filename = date('YmdHis') . '_' . $type . '_signstamp';
      $targetFile = $targetFile . $new_filename;
      if (file_exists($targetFile)) {
        unlink($targetFile);
      }

      $image_save_func($tmp, "$targetFile.$new_image_ext");
      imagedestroy($tmp);

      return "$new_filename.$new_image_ext";
    }

    /** Upload Function for Image Base64 (OLD) */
    private function uploadImageBase($image, $type) 
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

      $upload_name = date('YmdHis') . '_' . $type . '_signstamp.png';
      $destinationPath = storage_path().'/app/public/signstamps/';
      if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
      }
      $path = $destinationPath . $upload_name;

      header('Content-Type: image/png');
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

    /** References */
    /**
     * Validate image.
     *
     * @param string $image string representing image, for example, result of base64_decode()
     *
     * @throws APIException if image size is 1MB or greater.
     * @throws APIException if file format is unsupported, GD can not create image from given string
     */
    protected function checkImage($image)
    {
      // check size
      if (strlen($image) > ZBX_MAX_IMAGE_SIZE) {
        self::exception(ZBX_API_ERROR_PARAMETERS, _('Image size must be less than 1MB.'));
      }
      // check file format
      if (@imageCreateFromString($image) === false) {
        self::exception(ZBX_API_ERROR_PARAMETERS, _('File format is unsupported.'));
      }
    }
    /**
     * Load the image and resize it using the assigned strategy.
     * @param  string   $filename Filename of the input file.
     * @return resource GD image resource.
     */
    protected function _resizeOld($filename)
    {
        $image = imageCreateFromString(file_get_contents($filename));
        if (false === $image) {
          throw new Zend_Filter_Exception('Can\'t load image: ' . $filename);
        }
        $resized = $this->getStrategy()->resize($image, $this->getWidth(), $this->getHeight());
        return $resized;
    }
}
