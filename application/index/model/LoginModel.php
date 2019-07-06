<?php

namespace app\index\model;

use think\Model;

class LoginModel extends Model
{
    protected $connection = 'mysql';
    protected $table = "ckick_user";
}