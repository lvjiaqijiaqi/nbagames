<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class AuthorizationsController extends Controller
{

	protected function respondWithToken($token)
	{
	    return $this->response->array([
	        'access_token' => $token,
	        'token_type' => 'Bearer',
	        'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
	    ]);
	}

	public function update()
	{
	    $token = Auth::guard('api')->refresh();
	    return $this->respondWithToken($token);
	}

	public function destroy()
	{
	    Auth::guard('api')->logout();
	    return $this->response->noContent();
	}
	
}
