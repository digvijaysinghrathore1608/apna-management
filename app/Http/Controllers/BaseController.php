<?php

namespace App\Http\Controllers;

use App\Traits\HandlesExceptions;
use App\Traits\UsesUniqueId;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    use HandlesExceptions, UsesUniqueId;
    
}
