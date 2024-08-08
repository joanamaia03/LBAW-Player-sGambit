<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ban;
use App\Models\Auction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if(!Auth::check()){
            return redirect("/mainPage");
        }

        $user = Auth::user();

        if($user->is_admin){
            $allUsers = User::where('is_deleted','false')->where('user_id','!=',$user->user_id)->orderBy('user_id','asc')->paginate(10);

            return view('pages.adminPage', ['users' => $allUsers]); //definir o q aparecer nas paginas de admin
        } else {
            return abort(403, 'Unauthorized action');
        }
    }

    public function showView(int $id)
    {
        if(!Auth::check()){
            return redirect("/mainPage"); //definir a home page
        }
        if(Auth::user()->is_admin){
            $admin = User::find(Auth::user()->user_id);
            $user = User::find($id);

            $this->authorize('view', $admin);

            return view('pages.viewProfile', ['user' => $user]); //definir a pagina de perfil
        } else {
            return abort(403, 'Unauthorized action');
        }
    }

    public function ban(Request $request)
    {
        if(Auth::user()->is_admin){
            $ban = Ban::firstWhere('user_id', $request->id);
            if($ban == null){
                $ban = new Ban;
                $ban->reason = 'yep';
                $ban->date = now();
                $ban->user_id = $request->id;
                $ban->save();
            }
            $auctions = Auction::where('user_id', $request->id)->get();
            foreach($auctions as $auction){
                $auction->state = 'Closed';
                $auction->save();
            }
            return redirect()->back();
        } else {
            return abort(403, 'Unauthorized action');
        }
    }

    public function unban(Request $request)
    {
        if(Auth::user()->is_admin){
            $ban = Ban::firstwhere('user_id', $request->id);
            if($ban != null){
                $ban->delete();
            }
            return redirect()->back();
        } else {
            return abort(403, 'Unauthorized action');
        }
    }

    public function upgrade(Request $request)
    {
        if(Auth::user()->is_admin){
            $user = User::find($request->id);
            if(Auth::user()->is_admin){
                if(!$user->is_admin){
                    $user->is_admin = True;
                    $user->save();
                }
            }
            return redirect()->back();
        } else {
            return abort(403, 'Unauthorized action');
        }
    }

    public function downgrade(Request $request)
    {
        if(Auth::user()->is_admin){
            $user = User::find($request->id);
            if(Auth::user()->is_admin){
                if($user->is_admin){
                    $user->is_admin = False;
                    $user->save();
                }
            }
            return redirect()->back();
        } else {
            return abort(403, 'Unauthorized action');
        }
    }

    public function showAdminEdit(int $id)
        {
            if(!Auth::check()){
                return redirect("/mainPage");
            }

            $admin = User::find(Auth::user()->user_id);
            $user = User::find($id);

            if(!$admin->is_admin) {
                return abort(403, 'Unauthorized action');
            }

            return view('pages.adminEdit', ['user' => $user]);
        }

    public function adminEdit(Request $request, int $id)
        {
            if(Auth::user()->is_admin){
                $user = User::find($id);
                
                $request->validate([
                    'username' => 'required|string|max:255|'.Rule::unique('users')->ignore($user->user_id, 'user_id'),
                    'name' => 'required|string|max:255',
                ]);

                $user->username = $request->username;
                $user->name = $request->name;
                
                $user->save();
                return redirect('/admin/users/'.$user->user_id);
            } else {
                return abort(403, 'Unauthorized action');
            }
        }
}
