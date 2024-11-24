<?php

namespace App\Controllers;

use App\Auth\JWTAuth as JWTAuth;


class AuthController extends Controller
{

    use JWTAuth;



    public function __construct()
    {
        parent::__construct();  
    }

    public function login($request)
    {
        // validate request
        $this->validate([
            'username||min:3|max:25',
            'mobile||length:11|string',
        ], $request);
        $findUser = null;
        // dd($request);   
        if (isset($request->mobile)) {
            // get user
            $findUser = $this->queryBuilder->table('users')
                ->where(column: 'mobile', operator: '=', value: $request->mobile)
            //   ->where('password', '=', $request->password)
                ->get()->execute();
        } else if (isset($request->username)) {
            // get user
            $findUser = $this->queryBuilder->table('users')
                ->where(column: 'username', operator: '=', value: $request->username)
            //   ->where('password', '=', $request->password)
                ->get()->execute();
            }
            
            // Example validation: check if username is 'admin' and password is 'admin123'
            if ($findUser) {
                // Generate JWT token
                $token = $this->generateToken(
                    $findUser->username,
                    $findUser->id,
                    $findUser->role,
                    $findUser->mobile,
                    $findUser->status
                );
                
                // Return token as JSON response
                return $this->sendResponse(data: ['token' => $token], message: "با موفقیت وارد شدید");
            } else {
                // If credentials are not valid, return error response
                
                return $this->sendResponse(message: "نام کاربری یا رمز عبور شما صحیح نیست!", error: true, status: HTTP_Unauthorized);
        }
    }

    public function register($request)
    {
        // dd('test');
        // validate request
        $this->validate([
            'username||required|min:3|max:25',
            'display_name||min:4|max:40|string',
            'mobile||required|length:11|string',
            'role||enum:admin,support,guest,host',
            'status||enum:pending,accept,reject',

        ], $request);

        $this->checkUnique('users', array:[['username', $request->username], ['mobile', $request->mobile]]);
        // dd('test');
        $newUser = $this->queryBuilder->table('users')
            ->insert([
                'username' => $request->username,
                'mobile' => $request->mobile,
                'display_name' => $request->display_name ?? null,
                'profile' => $request->profile ?? null,
                'role' => $request->role ?? 'guest',
                'created_at' => time(),
                'updated_at' => time(),
                'status' => $request->status ?? "pending",
            ])->execute();

        return $this->sendResponse(data: $newUser, message: "حساب کاربری شما با موفقیت ایجاد شد!");
    }

    public function verify($request)
    {
        $verification = $this->verifyToken($request->token);
        if (!$verification) {
            return $this->sendResponse(data: $verification, message: "Unauthorized token body!", error: true, status: HTTP_BadREQUEST);
        } else {
            return $this->sendResponse(data: $verification, message: "you have logged in successfully!", error: false, status: HTTP_OK);
        }

    }
}
