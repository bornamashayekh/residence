<?php
namespace App\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;
trait JWTAuth {
    private $dotenv;
    public function __construct() {
        $this->dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
        $this->dotenv->load();
        
      
    }
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
        $jwt = JWT::encode($payload, $_ENV['SECRET_KEY'], 'HS256');
        return $jwt;
    }

    public function verifyToken($token)
    {
        try {
            // Decode JWT token
            $decoded = JWT::decode($token, new Key( $_ENV['SECRET_KEY'], 'HS256'));
            // dd($decoded);
            // Return decoded payload
            return $decoded;
        } catch (\Exception $e) {
            // If token is invalid or expired, return false
            // dd($decoded);
            return false;
        }
    }

}