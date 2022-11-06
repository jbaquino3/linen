<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('{any}', function(Request $request) {
    if($request->employeeid) {
        $user = User::find($request->employeeid);

        // Create user if does not exist
        if(!$user) {
            $homis = \DB::table("hospital.dbo.user_acc")
                ->where("employeeid", $request->employeeid)
                ->first();

            if(!$homis) {
                return "HOMIS Account not found.";
            }

            $hpersonal = \DB::table("hospital.dbo.hpersonal")
                ->where("employeeid", $request->employeeid)
                ->first();

            $user = User::create([
                'employeeid' => $request->employeeid,
                'name' => ucwords($hpersonal->lastname . ", " . $hpersonal->firstname),
                'email' => $homis->user_name,
                'password' => \Hash::make(Carbon::now()),
                'role' => "USER"
            ]);

            if(!$user) {
                return "Failed to create user account. Please try again.";
            }
        }

        $user->tokens()->delete();

        return view('welcome', [
            "token" => $user->createToken("Single Page Application")->plainTextToken
        ]);
    }

    return view('welcome');
})->where('any', '.*');