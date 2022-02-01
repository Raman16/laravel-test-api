<?php

namespace App\Http\Controllers;

use App\Models\WebService;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebServiceController extends Controller
{


    public const DRIVE_SCOPES =  [
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file'
    ];

    //DEFPENDENCY INJECTIONS Client $client
    public function connect($name, Client $client)
    {

        if ($name == 'google-drive') {
            // $client =  new Client();
            // $config = config('services.google');

            // $client->setClientId($config['key']);
            // $client->setClientSecret($config['secret']);
            // $client->setRedirectUri($config['redirect_url']);
            $client->setScopes(self::DRIVE_SCOPES);

            // dd($config);
            $url = $client->createAuthUrl();

            return response(['url' => $url]);
        }
    }
    //DEFPENDENCY INJECTIONS Client $client
    public function callback(Request $request, Client $client)
    {
        //Here Client $client we defined is not coming directly from
        //Google/Client, it is coming from AppServiceProvider service
        //container.

        // $client =  new Client();
        //coming from laravel service container
        // $client =  app(Client::class);//service container
        // $config = config('services.google');
        // $client->setClientId($config['key']);
        // $client->setClientSecret($config['secret']);
        // $client->setRedirectUri($config['redirect_url']);
        $access_token = $client->fetchAccessTokenWithAuthCode($request->code);

        $service = WebService::create([
            'user_id' => auth()->id(),
            'token' => $access_token,
            'name' => 'google-drive'
        ]);

        return $service;
    }

    public function store(Request $request, WebService $web_service,Client $client)
    {

        //Below $web_service->token['access_token']
        //stores as json but retrives as an array since we
        //$cast = ['token' => 'json'] in WebService model
        $access_token = $web_service->token['access_token'];

        $client->setAccessToken($access_token);
        $service = new Drive($client);
        $file = new DriveFile();


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

        return response('Uploaded',Response::HTTP_CREATED);
    }
}
