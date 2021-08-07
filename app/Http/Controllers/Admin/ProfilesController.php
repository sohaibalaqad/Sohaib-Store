<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Scopes\ActiveStatusScope;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function show(Profile $profile)
    {
        return $profile->ratings;
    }
}
