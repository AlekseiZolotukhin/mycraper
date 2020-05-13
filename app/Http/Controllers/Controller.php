<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @return int
     */
    protected function getUserByToken(): Int
    {
        $request = request();
        $token = $request->bearerToken();

        if (empty($token)) {
          return -1;
        }

        $user = User::where('api_token', $token)->get('id');
        if ($user) {
            return $user->toArray()[0]['id'];
        }
         return 0;
    }

    /**
     * @param null $model
     * @return \Illuminate\Http\JsonResponse|Int
     */
    protected function tokenCheck($model = null)
    {
        $user_id = $this->getUserByToken();

        if ($user_id === -1) {
            return response()->json(['success' => false, 'error' => 'token required']);
        }

        if ($model !== null && (int)$user_id !== (int)$model->user_id) {
            response()->json(['success' => false, 'error' => 'mismatch user'])->send();
            die();
        }

        return $user_id;
    }
}
