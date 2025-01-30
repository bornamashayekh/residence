<?php
namespace App\Controllers;

use App\Controllers\Controller;

class UserController extends Controller
{
    // protected $roles;
    public function __construct()
    {
        parent::__construct();

    }   
    public function index() {
        $users = $this->queryBuilder->table('users')->getAll()->execute();
        return $this->sendresponse(message: "لیست کاربران با موفقیت دریافت شد", data: $users);
    
    }
    public function get($id,$request)
    {
        // dd($request);

        $user = $this->queryBuilder->table('users')->where(column: 'users.id', value: $id)
        ->get()->execute();
        dd($user);
        if(isset($user->features)) $user->features = explode(",", $user->features);
        if ($user) {
            return $this->sendresponse(message: "کاربر مورد نظر با موفقیت دریافت شد", data: $user);
        } else {
            return $this->sendresponse(message: "کاربر مورد نظر یافت نشد", error: true, status: HTTP_BadREQUEST);
        }
    }

    public function store($request)
    {
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

        return $this->sendResponse(data: $newUser, message: "حساب کاربری جدید با موفقیت ایجاد شد!");
    }
    public function update($id, $request)
    {
        $this->validate([
           
            'display_name||min:4|max:40|string',
            'status||enum:pending,accept,reject',

        ], $request);
        $new_user = $this->queryBuilder->table('users')->update([

       
            'display_name' => $request->display_name ?? null,
            'profile' => $request->profile ?? null,

            "updated_at" => time(),
        ])->where(value: $id)->execute();
        return $this->sendResponse(data: $new_user, message: "کاربر مورد نظر با موفقیت ویرایش شد");

    }
    public function destroy($id)
    {
        $deleted_user = $this->queryBuilder->table('users')->update([
            'deleted_at' => time(),
        ])->where(value: $id)->execute();
        if ($deleted_user) {
            return $this->sendResponse(data: $deleted_user, message: "کاربر مورد نظر با موفقیت حذف شد");
        } else {
            return $this->sendResponse(error: true, status: HTTP_BadREQUEST, message: " کاربر مورد نظر یافت نشد");
        }

    }
}