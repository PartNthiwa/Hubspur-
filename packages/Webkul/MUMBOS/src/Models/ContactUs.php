<?php

namespace Webkul\MUMBOS\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\MUMBOS\Contracts\ContactUs as ContactUsContract;

class ContactUs extends Model implements ContactUsContract
{
     protected $fillable = ['name', 'email', 'message'];
}