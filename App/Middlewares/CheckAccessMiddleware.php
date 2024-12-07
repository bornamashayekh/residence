<?php
namespace App\Middlewares;
use App\Traits\ResponseTrait;
class CheckAccessMiddleware {
    use ResponseTrait;
    public function checkAccess( $accessed_role){
        $data = getPostDataInput();
 
        $accessed = in_array($data->userDetail->role , $accessed_role);
        if(!$accessed){
            $this->sendResponse(message: "دسترسی ندارید",error:true, status: "HTTP_Forbbiden");
            
            return exit();
        }
        return true;
    }
}