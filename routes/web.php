<?php

use Google\Client;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/drive',function(){
    $client =  new Client();
    $client->setClientId('603418588007-kmma6un18m0dcpec166arm0j4d90u2q0.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri('http://127.0.0.1:8000/google-drive/callback');
    $client->setScopes(
        [
            'https://www.googleapis.com/auth/drive',
            'https://www.googleapis.com/auth/drive.file'
        ]
    );
    $url = $client->createAuthUrl();
    return $url;
});

Route::get('/google-drive/callback',function(){

    $code = request('code');

    $client =  new Client();
    $client->setClientId('603418588007-kmma6un18m0dcpec166arm0j4d90u2q0.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri('http://127.0.0.1:8000/google-drive/callback');
    $access_token = $client->fetchAccessTokenWithAuthCode($code);
    return $access_token;

});



Route::get('upload',function(){

    $client = new Client();
    $access_token = 'ya29.A0ARrdaM8nB-2yNL1C6lzeneTCkfo8jh3Bibwn1Ghn6m1_pXcxjpvbFa474mszH25oUinjFDn_WVTgcEeWM_ksL8SzCmVMYff5xRdbGuJ0gY2ONyXv_UGo70YqPP3bONsUNxLtIEa9g-zPuNBl9zDfPBUdicq8';
    $client->setAccessToken($access_token);
    $service = new Google\Service\Drive($client);
    $file = new Google\Service\Drive\DriveFile();


    DEFINE("TESTFILE", 'testfile-small.txt');
    if (!file_exists(TESTFILE)) {
        $fh = fopen(TESTFILE, 'w');
        fseek($fh, 1024 * 1024);
        fwrite($fh, "!", 1);
        fclose($fh);
    }

    $file->setName("Hello World!");
    $service->files->create(
        $file,
        array(
            'data' => file_get_contents(TESTFILE),
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'multipart'
        )
    );

});