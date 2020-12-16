<?php


namespace App\Http\Controllers;

use App\Repositories\Image\ImageRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\User\UserRepositoryInterface;

class UserController
{
    private $user;
    private $product;
    private $image;

    /**
     * UserController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param ProductRepositoryInterface $product
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        ProductRepositoryInterface $product,
        ImageRepositoryInterface $image
    )
    {
        $this->user = $userRepository;
        $this->product = $product;
        $this->image = $image;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = (new User)->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user', 'token'), 201);
    }

    /**
     * @return mixed
     */
    public function getAuthenticatedUser()
    {
        return $this->user->getUser();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|min:11',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        return $this->user->updateUser($request);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function updateUserPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
            'old_password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        return $this->user->updatePassword($request);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fileUploader(Request $request)
    {
        return $this->image->upload($request);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        return $this->product->addProduct($request);
    }

    /**
     * @return mixed
     */
    public function myProducts()
    {
        return $this->product->getMyProducts();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function deleteProduct(Request $request)
    {
        return $this->product->deleteProduct($request);
    }


    /**
     * @param int $product_id
     * @return mixed
     */
    public function getProductById($product_id = 0)
    {
        return $this->product->productById($product_id);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'product_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        return $this->image->update($request);
    }
}
