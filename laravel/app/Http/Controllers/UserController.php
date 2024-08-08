<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Auction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show()
    {
        if(!Auth::check()){
            return redirect("/mainPage"); //definir a home page
        }

        $user = User::find(Auth::user()->user_id);

        $this->authorize('view', $user);

        return view('pages.profile', ['user' => $user]); //definir a pagina de perfil
    }

    public function showEdit()
    {
        if(!Auth::check()){
            return redirect("/mainPage");
        }

        $user = User::find(Auth::user()->user_id);

        $this->authorize('view', $user);

        return view('pages.editProfile', ['user' => $user]);
    }

    public function edit(Request $request)
    {
        $this->authorize('edit', User::class);

        $user = Auth::user();
        
        $request->validate([
            'email' => 'required|email|max:255|'.Rule::unique('users')->ignore($user->user_id, 'user_id'),
            'username' => 'required|string|max:255|'.Rule::unique('users')->ignore($user->user_id, 'user_id'),
            'name' => 'required|string|max:255',
        ]);
        
        if(!Hash::check($request->password, $user->password)){
            return back()->withErrors([
                'password' => 'Wrong password for account',
            ]);
        }
        if($request->new_password == $request->confirm_new_password && $request->new_password != null) {
            $request->validate([
                'new_password' => 'string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d])/', //pelo menos 8 caractéres, uma letra minúscula, uma letra maiúscula, um número e um símbolo
            ]);
            $user->password = Hash::make($request->new_password);
        }

        if($request->file('profile_pic')){
            if( !in_array(pathinfo($_FILES["image"]["name"],PATHINFO_EXTENSION),['jpg','png'])) {
                return redirect('user/edit')->with('error', 'Error: Not the right extension');
            }
            $request->validate([
                'profile_pic' => 'mimes:png, jpg',
            ]);
            UserController::update($user->user_id, $request);
        }

        $user->username = $request->username;
        $user->email = $request->email;
        $user->name = $request->name;
        
        $user->save();
        return redirect('users/'.$user->user_id);
    }

    public function delete(Request $request)
    {
        $this->authorize('delete', User::class);

        $user = Auth::user();
        
        $user->is_deleted = true;
        $user->save();

        $auctions = Auction::where('user_id', $user->user_id)->get();
        foreach($auctions as $auction){
            $auction->state = 'Closed';
            $auction->save();
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/mainPage');
    }
}