<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Facades\App\Helpers\Json;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $orderlist = [
            [
                'name' => 'Name A -> Z',
                /* value = 0*/
                'order' => 'asc',
                'kolom' => 'name'
            ],
            [
                'name' => 'Name Z -> A',
                /* value = 1*/
                'order' => 'desc',
                'kolom' => 'name'
            ],
            [
                'name' => 'Email A -> Z',
                /* value = 2*/
                'order' => 'asc',
                'kolom' => 'email'
            ],
            [
                'name' => 'Email Z -> A',
                /* value = 3*/
                'order' => 'desc',
                'kolom' => 'email'
            ],
            [
                'name' => 'Not active users',
                /* value = 4*/
                'order' => 'desc',
                'kolom' => 'active'
            ],
            [
                'name' => 'Admin users',
                /* value = 5*/
                'order' => 'asc',
                'kolom' => 'admin'
            ],

        ];

        $name = '%' . $request->input('name') . '%';

        if ($request -> sort == null) {
            $users = User::orderBy('name')
                ->where(function ($query) use ($name) {
                    $query->where('name', 'like', $name)
                        ->orWhere('email', 'like', $name);
                })
                ->paginate(10)
                ->appends(['name' => $request->input('name'), 'sort' => $request->input('sort')]);
            $result = compact('users', 'orderlist');
            Json::dump($result);
            return view('admin.users.index', $result);
        }else {
            $sort_field = $orderlist[$request->sort]['kolom'];
            $sort_order = $orderlist[$request->sort]['order'];
            $users = User::orderBy($sort_field, $sort_order)
                ->where(function ($query) use ($name) {
                    $query->where('name', 'like', $name)
                        ->orWhere('email', 'like', $name);
                })
                ->paginate(10)
                ->appends(['name' => $request->input('name'), 'sort' => $request->input('sort')]);
            $result = compact('users', 'orderlist');
            Json::dump($result);
            return view('admin.users.index', $result);
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|min:3|unique:users,name',
            'email' => 'required|min:3|unique:users,email'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->save();
        return response()->json([
            'type' => 'success',
            'text' => "The user <b>$user->name</b> has been added"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return redirect('admin/users');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $result = compact('user');
        Json::dump($result);
        return view('admin.users.edit', $result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request,[
            'name' => 'required|min:3|unique:users,name,'. $user->id,
            'email' => 'required|min:3|unique:users,email,'. $user->id
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->active == null)
            $user->active = 0;
        else
            $user->active = 1;
        if ($request->admin == null)
            $user->admin = 0;
        else
            $user->admin = 1;
        $user->save();
        session()->flash('success', 'The user has been updated');
        return redirect('admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'type' => 'success',
            'text' => "The user <b>$user->name</b> has been deleted"
        ]);
    }

}
