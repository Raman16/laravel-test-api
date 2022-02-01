<?php

namespace Tests\Feature;

use App\Models\WebService;
use Google\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void{
        parent::setUp();
        $this->user = $this->authUser();
    }
    public function test_user_can_connect_to_a_service_and_token_is_stored()
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setScopes');
            $mock->shouldReceive('createAuthUrl')->andReturn('http://127.0.0.1');
                 
        });
        $response = $this->getJson(route('web-service.connect','google-drive'))
                         ->assertOk()
                         ->json();
        $this->assertEquals('http://127.0.0.1', $response['url']);
        $this->assertNotNull($response['url']);
    }

    public function test_service_callback_will_store_token(){


        $this->mock(Client::class, function (MockInterface $mock) {
            // $mock->shouldReceive('setClientId')->once();we movied to
            // $mock->shouldReceive('setClientSecret')->once();Dependency 
            // $mock->shouldReceive('setRedirectUri')->once();Injection hence no need
            $mock->shouldReceive('fetchAccessTokenWithAuthCode')
                 ->andReturn('fake-token');
                 
        });
      
        $res = $this->postJson(route('web-service.callback'), [
            'code' => 'dummyCode'
        ])->assertCreated();

        // $webService = WebService::first();
        // $this->assertNotNull($this->user->services->first()->token);

        $this->assertDatabaseHas('web_services', [
            'user_id' => $this->user->id,
            // 'token' => json_encode(['access_token' => 'fake-token'])
        ]);
        
    }


    public function test_data_of_a_week_can_be_stored_on_google_drive()
    {
     
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setAccessToken');
            $mock->shouldReceive('getLogger->info');
            $mock->shouldReceive('shouldDefer');
            $mock->shouldReceive('execute');
        });

        $web_service = $this->createWebService();
        $this->postJson(route('web-service.store', $web_service->id))->assertCreated();
    }
}
