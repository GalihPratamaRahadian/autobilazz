<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Yajra\DataTables\DataTables;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const ROLE_ADMIN = 'admin';

    public static function createUser(array $request)
    {
        $user = self::create($request);

        return $user;
    }

    public function updateUser(array $request)
    {
        $this->update($request);

        return $this;
    }

    public function deleteUser()
    {
        return $this->delete();
    }

    // function
    public function roleHtml()
    {
        if ($this->role == self::ROLE_ADMIN) {
            return '<span class="badge text-bg-success">Admin</span>';
        }

    }
    public function isAdmin()
    {
        return $this->role === "admin";
    }

    public static function dt()
    {
        $query = self::where('created_at', '!=', NULL);
        return app(DataTables::class)->eloquent($query)
            ->addColumn('action', function ($data) {
                $action = '
                    <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item mr-1 text-primary edit" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalEdit" data-edit-href="' . route('admin.user.update', $data->id) . '" data-get-href="' . route('admin.user.get', $data->id) . '"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        </li>
                        <li>
                            <a class="dropdown-item mr-1 delete text-danger" href="javascript:void(0);" data-delete-message="Yakin ingin menghapus?" data-delete-href="' . route('admin.user.destroy', $data->id) . '"><i class="fa-solid fa-trash-can"></i> Hapus</a>
                        </li>
                    </ul>
                    </div>
                ';

                return $action;
            })

            ->editColumn('role', function ($data) {
                return $data->roleHtml();
            })

            ->rawColumns(['action', 'role'])
            ->make(true);
    }
}
