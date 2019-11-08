<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function() {
    Route::post('login', 'Api\AuthController@login');
    Route::post('signup','Api\AuthController@signup');
    Route::post('signup/token', 'Api\AuthController@sendActivationToken');
    Route::get('signup/activate/{token}', 'Api\AuthController@signupActivate');
    Route::middleware('auth:api')->group(function() {
        Route::get('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
    });

    Route::post('linetoken', 'Api\AuthController@lineToken');
    Route::post('kprofile', 'Api\AuthController@kakaoProfile');
});

Route::get('auth/lineauth', 'Api\AuthController@lineAuth');

Route::prefix('password')->group(function() {
    Route::post('create', 'Api\PasswordResetController@create');
    Route::get('find/{token}', 'Api\PasswordResetController@find');
    Route::post('reset', 'Api\PasswordResetController@reset');
});

Route::prefix('document')->middleware('auth:api')->group(function() {
    Route::get('/', 'Api\DocumentsController@getDocuments');
    Route::post('/', 'Api\DocumentsController@uploadDocument');
    Route::get('/{id}', 'Api\DocumentsController@getDocument');
    Route::put('/{id}', 'Api\DocumentsController@documentSetting');
    Route::delete('/{id}', 'Api\DocumentsController@deleteDocument');
    Route::post('del_detail', 'Api\DocumentsController@deleteDocumentDetail');

    Route::get('/{doc_id}/request', 'Api\DocumentsController@getSignRequests');
    Route::post('/{doc_id}/request', 'Api\DocumentsController@addSignRequest');
    Route::get('/get-by/document-id', 'Api\DocumentsController@getDocumentByID');
    Route::get('/get-by/client-id', 'Api\DocumentsController@getDocumentByClientID');
    Route::post('/add-delete-page','Api\DocumentsController@adDeletePage');
    Route::post('/add-rotate-page','Api\DocumentsController@addRotatePage');
});

// Route::put('/drequest/{doc_id}', 'Api\DocumentsController@editSignRequest')->middleware('auth:api');
// Route::delete('/drequest/{doc_id}', 'Api\DocumentsController@deleteSignRequest')->middleware('auth:api');

Route::prefix('annotation')->group(function() {
    Route::get('/', 'Api\AnnotationsController@index');
    Route::delete('/{id}', 'Api\AnnotationsController@deleteAnnotation');
    Route::prefix('text')->group(function() {
        Route::post('/', 'Api\AnnotationsController@addTextAnnotation');
        Route::put('/{id}', 'Api\AnnotationsController@updateTextAnnotation');
    });
    Route::prefix('image')->group(function() {
        Route::post('/', 'Api\AnnotationsController@addImageAnnotation');
        Route::put('/{id}', 'Api\AnnotationsController@updateImageAnnotation');
    });
    Route::prefix('checkbox')->group(function() {
        Route::post('/', 'Api\AnnotationsController@addCheckBoxAnnotation');
        Route::put('/{id}', 'Api\AnnotationsController@updateCheckBoxAnnotation');
    });
    Route::prefix('radiobutton')->group(function() {
        Route::post('/', 'Api\AnnotationsController@addRadioButtonAnnotation');
        Route::put('/{id}', 'Api\AnnotationsController@updateRadioButtonAnnotation');
    });
    Route::post('/add-new', 'Api\AnnotationsController@addAnnotation')->middleware('auth:api');
    Route::put('/add-new', 'Api\AnnotationsController@addAnnotation')->middleware('auth:api');
    Route::get('/{id}', 'Api\AnnotationsController@getAnnotation');
    Route::get('/get-by/document-id', 'Api\AnnotationsController@getAnnotationByDocumentId');
    Route::post('add-comment', 'Api\AnnotationsController@adCommentDocuments')->middleware('auth:api');
});

Route::prefix('pdf')->group(function() {
    Route::get('/export', 'Api\PDFsController@export');
    Route::get('convert-to-image','Api\PDFsController@convertPDFToImage');
    Route::get('/convert-pdf','Api\PDFsController@convertFileToPDFAPI');
});

Route::prefix('fpdf')->group(function() {
    Route::get('text', 'Api\PDFController@addText');
});

//company_sizes
Route::prefix('company-sizes')->group(function(){
    Route::get('/all','Api\CompanySizesController@getAll');
});

//departments
Route::prefix('departments')->group(function(){
    Route::get('/all', 'Api\DepartmentsController@getAll');
});

//industries
Route::prefix('industries')->group(function(){
    Route::get('/all', 'Api\IndustriesController@getAll');
});

//clients
Route::prefix('clients')->middleware('auth:api')->group(function() {
    Route::post('/new', 'Api\ClientsController@newClient');
    Route::get('/get-client', 'Api\ClientsController@getClient');
    Route::get('/get-all-email-client', 'Api\ClientsController@getAllEmailClient');
    Route::put('/update-client', 'Api\ClientsController@updateProfile');
    Route::put('/update-avatar', 'Api\ClientsController@changeAvatar');
    Route::put('/update-password', 'Api\ClientsController@changePassword');
});

//recipients
Route::prefix('recipients')->middleware('auth:api')->group(function() {
    Route::post('/add', 'Api\RecipientController@addRecipient');
    Route::put('/add', 'Api\RecipientController@addRecipient');
    Route::delete('/delete/{id}', 'Api\RecipientController@deleteRecipient');
    Route::get('/by-document-id', 'Api\RecipientController@getRecipientByDocumentId');
    Route::post('/sent-email-recipient', 'Api\RecipientController@sentEmailReview');
    Route::post('/get-kakao-friends', 'Api\RecipientController@kakaoFriends');
});

//email_styles
Route::prefix('email-styles')->middleware('auth:api')->group(function() {
    Route::post('/add', 'Api\EmailStylesController@createOrUpdateEmailSyle');
    Route::put('/add', 'Api\EmailStylesController@createOrUpdateEmailSyle');
    Route::get('/get-by-client-id', 'Api\EmailStylesController@getEmailStyle');
});

//doc_folders
Route::prefix('doc-folders')->middleware('auth:api')->group(function() {
    Route::post('/add', 'Api\DocFolderController@addDocFolder');
    Route::get('/all', 'Api\DocFolderController@getAll');
    Route::put('/{id}/update', 'Api\DocFolderController@updateName');
    Route::delete('/{id}/delete', 'Api\DocFolderController@delete');
});

/** Signatures & Stamps Routes */
Route::prefix('signs-stamps')->middleware('auth:api')->group(function() {
  /** Stamps */
  Route::get('/stamps', 'Api\StampsController@index');
  Route::post('/stamps', 'Api\StampsController@store');
  Route::get('/stamps/{id}', 'Api\StampsController@show');
  Route::put('/stamps/{id}', 'Api\StampsController@update');
  Route::delete('/stamps/{id}/delete', 'Api\StampsController@softDelete');
  Route::delete('/stamps/{id}/permanent', 'Api\StampsController@permanentDeleteSoft');

  Route::post('/stamps-upload', 'Api\StampsController@upload');
  Route::get('/stamps-default/{id}', 'Api\StampsController@setDefault');

  /** Signatures */
  Route::get('/signs', 'Api\SignaturesController@index');
  Route::post('/signs', 'Api\SignaturesController@store');
  Route::get('/signs/{id}', 'Api\SignaturesController@show');
  Route::put('/signs/{id}', 'Api\SignaturesController@update');
  Route::delete('/signs/{id}/delete', 'Api\SignaturesController@softDelete');
  Route::delete('/signs/{id}/permanent', 'Api\SignaturesController@permanentDeleteSoft');
  
  Route::post('/signs-upload', 'Api\SignaturesController@upload');
  Route::get('/signs-default/{id}', 'Api\SignaturesController@setDefault');
});

