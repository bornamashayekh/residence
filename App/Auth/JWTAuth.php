<?php
namespace App\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

trait JWTAuth {
    private $secretKey = 'kvpFWQDecn';

    public function generateToken($username, $id , $role,$mobile,$status)
    {
        $payload = [
            'username' => $username,
            'mobile' => $mobile,
            'role' => $role,
            'id' => $id,
            'status' => $status,
            'exp' => time() + 604800 // Token expiration time  (1 Week)
        ];

        // Generate JWT token
        $jwt = JWT::encode($payload, $this->secretKey, 'HS256');
        return $jwt;
    }

    public function verifyToken($token)
    {
        try {
            // Decode JWT token
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));

            // Return decoded payload
            return $decoded;
        } catch (\Exception $e) {
            // If token is invalid or expired, return false
            return false;
        }
    }

}