<?php

namespace App\Controllers;

use App\Models\RoleModel;
use App\Models\PermissionModel;
use App\Models\RolePermissionModel;

class Roles extends BaseController
{
    protected $roleModel;
    protected $permissionModel;
    protected $rolePermissionModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->permissionModel = new PermissionModel();
        $this->rolePermissionModel = new RolePermissionModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Role',
            'roles' => $this->roleModel->findAll(),
        ];
        return view('roles/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Role Baru',
            'permissions' => $this->permissionModel->findAll(),
        ];
        return view('roles/create', $data);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $roleData = [
            'role_name' => $this->request->getPost('role_name'),
            'scope'     => $this->request->getPost('scope'),
        ];

        $this->roleModel->insert($roleData);
        $roleId = $this->roleModel->insertID();

        $permissions = $this->request->getPost('permissions');
        if (!empty($permissions)) {
            $pivotData = [];
            foreach ($permissions as $permId) {
                $pivotData[] = [
                    'role_id'       => $roleId,
                    'permission_id' => $permId,
                ];
            }
            $this->rolePermissionModel->insertBatch($pivotData);
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Gagal menyimpan role.');
        }

        return redirect()->to(base_url('roles'))->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $role = $this->roleModel->find($id);
        if (!$role) {
            return redirect()->to(base_url('roles'))->with('error', 'Role tidak ditemukan.');
        }

        // Get current permissions
        $currentPerms = $this->rolePermissionModel->where('role_id', $id)->findAll();
        $activePermIds = array_column($currentPerms, 'permission_id');

        $data = [
            'title'         => 'Edit Role: ' . $role['role_name'],
            'role'          => $role,
            'permissions'   => $this->permissionModel->findAll(),
            'activePerms'   => $activePermIds,
        ];
        return view('roles/edit', $data);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $roleData = [
            'role_name' => $this->request->getPost('role_name'),
            'scope'     => $this->request->getPost('scope'),
        ];

        $this->roleModel->update($id, $roleData);

        // Reset and update permissions
        $this->rolePermissionModel->where('role_id', $id)->delete();

        $permissions = $this->request->getPost('permissions');
        if (!empty($permissions)) {
            $pivotData = [];
            foreach ($permissions as $permId) {
                $pivotData[] = [
                    'role_id'       => $id,
                    'permission_id' => $permId,
                ];
            }
            $this->rolePermissionModel->insertBatch($pivotData);
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Gagal memperbarui role.');
        }

        return redirect()->to(base_url('roles'))->with('success', 'Role berhasil diperbarui.');
    }

    public function delete($id)
    {
        // Admin cannot be deleted
        if ($id == 1) {
            return redirect()->to(base_url('roles'))->with('error', 'Role Admin tidak dapat dihapus.');
        }

        $this->roleModel->delete($id);
        return redirect()->to(base_url('roles'))->with('success', 'Role berhasil dihapus.');
    }
}
