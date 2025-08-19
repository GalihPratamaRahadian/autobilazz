<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'price',
        'description'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public static function createService($dataService)
    {
        $name = $dataService['name'];
        $type = $dataService['type'];
        $price = $dataService['price'];
        $description = $dataService['description'];

        $service = self::create([
            'name' => $name,
            'type' => $type,
            'price' => $price,
            'description' => $description
        ]);

        return $service;
    }

    public function updateService($dataService)
    {
        $name = $dataService['name'];
        $type = $dataService['type'];
        $price = $dataService['price'];
        $description = $dataService['description'];

        $this->update([
            'name' => $name,
            'type' => $type,
            'price' => $price,
            'description' => $description
        ]);

        return $this;
    }

    public function deleteService()
    {
        return $this->delete();
    }

    public static function dt()
    {
        $query = self::whereNotNull('created_at')->orderByDesc('updated_at');

        return app(DataTables::class)->eloquent($query)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item mr-1 text-primary edit" data-bs-toggle="modal" data-bs-target="#modalEdit" href="javascript:void(0);" data-edit-href="' . route('admin.service.update', $data->id) . '" data-get-href="' . route('admin.service.get', $data->id) . '"><i class="fa-solid fas fa-pencil"></i> Edit</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"class="dropdown-item text-danger delete" data-delete-message="Yakin ingin menghapus?" data-delete-href="' . route('admin.service.destroy', $data->id) . '"><i class="fa-solid fa-trash-can"></i> Hapus</a>
                        </li>
                    </ul>
                </div>';

                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

}
