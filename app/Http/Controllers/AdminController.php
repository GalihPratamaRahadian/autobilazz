<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Order;
use App\MyClass\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function indexAdmin()
    {
        $total_pelanggan = Order::where('name', '!=', null)->count();
        return view('admin.dashboard', compact('total_pelanggan'));
    }

    public function userIndex(Request $request)
    {
        if ($request->ajax()) {
            return User::dt();
        }

        return view('admin.user.index');
    }

    public function userStore(Request $request, User $user)
    {

        DB::beginTransaction();

        try {
            $user->createUser([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            DB::commit();
            return response()->json([
                'message' => 'User berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function userUpdate(Request $request, User $user)
    {
        DB::beginTransaction();

        try {
            $user->updateUser($request->except(['password', 'confirm_password']));

            if (!empty($request->password)) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'User berhasil diupdate',
            ]);
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function userDestroy(User $user)
    {

        DB::beginTransaction();

        try {
            $user->deleteUser();
            DB::commit();

            return response()->json([
                'message' => 'User berhasil dihapus',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function userGet(User $user)
    {

        try {
            return Response::success([
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return Response::error($e);
        }
    }

}
