<?php

namespace App\Repositories\User;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRepository implements UserRepositoryInterface
{
    private $user;

    private function user()
    {
        return $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function getUser()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        } catch (\Exception $e) {
            return response()->json(['error' => [$e->getMessage()]], 400);

        }
        return response()->json(compact('user'));
    }


    /**
     * @return mixed
     */
    public function updateUser(Request $request)
    {
        $user = (new User)->findOrFail(JWTAuth::user()->id);
        $user->name = $request->name;
        $user->facebook_page = $request->facebook_page;
        $user->phone = $request->phone;
        $user->save();
        return response()->json($user);
    }


    /**
     * @return mixed
     */
    public function updatePassword(Request $request)
    {
        $user = $this->user();
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(json_encode(['password' => ['Old password not matched !']]), 400);
        }
        $user->fill([
            'password' => Hash::make($request->password)
        ])->save();

        return response()->json($user);
    }

}
