<?php

namespace app\index\model;

use think\Model;

class SOSODetailsModel extends Model
{
//    protected $connection = "db_config1";
    protected $table = "so_sodetails";
    protected $type = [
        'iQuantity' => 'integer',
    ];
}