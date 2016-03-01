<?php

namespace App\Http\Controllers;

use App\Customer;
use App\User;
use App\Http\Controllers\Menu\TraitMainMenu;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class InscriptionController extends Controller
{


    use TraitMainMenu;

    public function __construct()
    {
        $this->getMenu();
    }

    public function getStepOne()
    {

        $user = null;
        if(session()->has('user')) $user = session()->get('user');

        return view('inscription.step_one', compact('user'));


    }

    public function postStepOne(Request $request)
    {

        $id='';
        $userExist = session()->has('user');

        if($userExist){
            $user = session()->get('user');
            $id = ",$user->id";
        }

        $this->validate($request, [
            'email'=>'required|email|unique:users,email'.$id,
            'name' => 'required|max:3'
        ]);

        if($userExist){
            $user = User::findOrFail($user->id);
            $user->update($request->all());
        }else
            $user = User::create($request->all());

        session()->put('user', $user);

        return redirect('inscription/step-two');

    }


    public function getStepTwo()
    {

       $user = session()->get('user');

       return view('inscription.step_two', compact('user'));
    }

}
