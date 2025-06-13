<?php

namespace Webkul\MUMBOS\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\MUMBOS\Contracts\Share as ShareContract;

class Share extends Model implements ShareContract
{
    protected $fillable = [];
}