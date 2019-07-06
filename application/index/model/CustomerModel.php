<?php

namespace app\index\model;

use think\Model;

class CustomerModel extends Model
{
    protected $connection = "db_config1";
    protected $table = "Customer";
}