<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use illuminate\http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Sainsburys\Guzzle\Oauth2\GrantType\RefreshToken;
use Sainsburys\Guzzle\Oauth2\GrantType\PasswordCredentials;
use Sainsburys\Guzzle\Oauth2\Middleware\OAuthMiddleware;

class LoginController extends Controller
{
	public function login(Request $request)
	{
		// Request Key
		$dataLogin = array(
				'grant_type'	=> $request->grant_type,
				'client_id'		=> $request->client_id,
				'client_secret'	=> $request->client_secret,
				'action'		=> $request->action,
				'redirect_url'	=> $request->redirect_url,
				'username'     	=> $request->username,
				'password'      => $request->password,
				'ipAddress'		=> $request->ipAddress
		);

		//Implement Guzzle 
		$url = 'https://charlie.ibid.astra.co.id/backend/service/akun/auth/oauth2/';
		$client = new Client();
		$response = $client->request('POST',$url,['form_params' => $dataLogin]);
		return $response->getBody();
	}
}