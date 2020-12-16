<?php


namespace App\Repositories\User;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{

    /**
     * @return mixed
     */
    public function getUser();

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateUser(Request $request);


    /**
     * @param Request $request
     * @return mixed
     */
    public function updatePassword(Request $request);

}
