<?php

function translate_key($input){

    $translate_arrays = [
        "name" => "نام",
        "phone_number" => "شماره تلفن",
        "username" => "نام کاربری",
        "password" => "رمز عبور",
        "mobile" => "شماره همراه",
        "role" => "نقش کاربر",
        "status" => "وضعیت",
        "display_name" => "نام نمایشی",
        "title" => "شرجی",
    ];

    $isFind = false;

    foreach ($translate_arrays as $key => $value)
        if($input == $key) {
            $isFind = true;
            return $value;
        }


    if(!$isFind) return $input;
}