<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_user_can_login_with_email_and_password() {

       $user =$this->createUser();//generates 'password' password

       $response =  $this->postJson(route('user.login'),
                                [
                                    'email'=>$user->email,
                                    'password'=>'password'
                                ])
                          ->assertOk()
                          ->json();
        $this->assertArrayHasKey('token',$response);        
    }

    public function test_if_user_email_is_not_available_then_it_returns_error(){

          //Email Does not exist then unauthorized
          $this->postJson(route('user.login'),
                                [
                                    'email'=>'bandariraman@gmail1.com',
                                    'password'=>'password'
                                ])
                          ->assertUnauthorized();
    }
    public function test_if_user_entered_password_is_incorrect_then_returns_error(){
          
        $user =$this->createUser();//generates 'password' password
        $this->postJson(route('user.login'),
                                [
                                    'email'=>$user->email,
                                    'password'=>'password1111'
                                ])
                          ->assertUnauthorized();
    }
}
