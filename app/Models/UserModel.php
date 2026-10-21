<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel - Handles authentication and user accounts
 */
class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'password',
        'nama',
        'email',
        'role_id',
        'guru_id',
        'foto',
        'last_login',
    ];

    protected $useTimestamps = false;

    /**
     * Find user by username or email (Secure parameterized query)
     */
    public function get_by_username(string $username): ?object
    {
        return $this->groupStart()
            ->where('username', $username)
            ->orWhere('email', $username)
            ->groupEnd()
            ->first();
    }

    /**
     * Find user by ID
     */
    public function get_by_id(int $id): ?object
    {
        return $this->find($id);
    }

    /**
     * Get role name by role ID
     */
    public function role_name(int $roleId): ?string
    {
        $role = $this->db->table('roles')
            ->where('id', $roleId)
            ->limit(1)
            ->get()
            ->getRow();

        return $role ? $role->name : null;
    }

    /**
     * Get all users with role name joined
     */
    public function get_all(): array
    {
        return $this->select('users.*, roles.name as role_name')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->orderBy('users.id', 'DESC')
            ->findAll();
    }

    /**
     * Insert user with automatic bcrypt password hashing
     */
    public function create_user(array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->insert($data);
    }

    /**
     * Update user with optional password hashing
     */
    public function update_user(int $id, array $data): bool
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        return $this->update($id, $data);
    }

    /**
     * Update last login timestamp
     */
    public function update_last_login(int $userId): bool
    {
        return $this->update($userId, [
            'last_login' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get all available roles
     */
    public function get_roles(): array
    {
        return $this->db->table('roles')->get()->getResult();
    }
}
