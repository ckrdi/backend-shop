<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::latest()->when(\request()->q, function ($users) {
            $users->where('name', 'like', '%' . \request()->q . '%');
        })->paginate(10);

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            (new CreateNewUser)->create($request->all());
        } catch (\Exception $exception) {
            return redirect()->route('admin.user.index')->with([
                'error' => 'Data gagal tersimpan',
                'message' => $exception->getMessage()
            ]);
        }

        return redirect()
            ->route('admin.user.index')
            ->with([ 'success' => 'Data berhasil tersimpan.' ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        try {
            (new UpdateUserProfileInformation())->update($user, $request->all());
        } catch (\Exception $exception) {
            return redirect()->route('admin.user.index')->with([
                'error' => 'Data gagal tersimpan',
                'message' => $exception->getMessage()
            ]);
        }

        return redirect()
            ->route('admin.user.index')
            ->with([ 'success' => 'Data berhasil tersimpan.' ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'failed',
                'message' => $exception->getMessage()
            ]);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }
}
