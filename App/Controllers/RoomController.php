<?php
namespace App\Controllers;

use App\Controllers\Controller;

class RoomController extends Controller
{
    protected $roles;
    public function __construct()
    {
        parent::__construct();

    }
    public function index()
    {

        $this->roles = ['host', 'admin'];

        $this->Access->checkAccess($this->roles);

        $rooms = $this->queryBuilder->table('rooms')->getAll()->execute();
        return $this->sendresponse(message: "لیست اتاق ها با موفقیت دریافت شد", data: $rooms);
    }
    public function store($request)
    {
        $this->validate([
            "host_id||number",
            "title||required|min:5|string",
            "room_detail||string",
            "capacity||number",
            "addition_capacity||number",

        ], $request);
        if ($request->userDetail->role == "host") {
            $request->host_id = $request->userDetail->id;
        }
        $getHost = $this->queryBuilder->table("users")->where($request->host_id, 'id')->where("host", "role")->get()->execute();
        if ($getHost) {
            return $this->sendResponse(data: $getHost, message: "میزبان مورد نظر یافت نشد", error: true, status: HTTP_NotFOUND);
        }

        $new_room = $this->queryBuilder->table('rooms')->insert([
            "host_id" => $request->host_id,
            "title" => $request->title,
            "room_detail" => $request->room_detail,
            "capacity" => $request->capacity,
            "addition_capacity" => $request->addition_capacity,
            "created_at" => time(),
            "updated_at" => time(),
        ])->execute();
        return $this->sendResponse(data: $new_room, message: "اتاق مورد نظر با موفقیت اضافه شد");
    }
}
