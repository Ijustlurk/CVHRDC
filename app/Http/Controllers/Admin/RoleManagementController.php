<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleManagementController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:roles', 'regex:/^[a-z0-9_]+$/'],
            'description' => ['nullable', 'string', 'max:1000']
        ], [
            'name.required' => 'Role name is required.',
            'name.unique' => 'This role name already exists.',
            'name.regex' => 'Role name should only contain lowercase letters, numbers, and underscores.',
            'description.max' => 'Description cannot exceed 1000 characters.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed. Please check the form and try again.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role = Role::create([
                'name' => strtolower(trim($request->name)),
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'role' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'description' => $role->description
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Role creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the role. Please try again.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found.'
            ], 404);
        }

        // Prevent updating protected roles
        if (in_array($role->name, ['admin', 'superadmin', 'user'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot modify protected system roles.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $id, 'regex:/^[a-z0-9_]+$/'],
            'description' => ['nullable', 'string', 'max:1000']
        ], [
            'name.required' => 'Role name is required.',
            'name.unique' => 'This role name already exists.',
            'name.regex' => 'Role name should only contain lowercase letters, numbers, and underscores.',
            'description.max' => 'Description cannot exceed 1000 characters.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed. Please check the form and try again.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role->update([
                'name' => strtolower(trim($request->name)),
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully',
                'role' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'description' => $role->description
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Role update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the role. Please try again.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found.'
            ], 404);
        }

        try {
            // Prevent deleting protected roles
            if (in_array($role->name, ['admin', 'superadmin', 'user'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete protected system roles.'
                ], 403);
            }

            // Check if role has users
            $userCount = $role->users()->count();
            if ($userCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete role '{$role->name}' because it is assigned to {$userCount} user(s). Please reassign or remove these users first."
                ], 422);
            }

            $roleName = $role->name;
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => "Role '{$roleName}' has been deleted successfully."
            ]);
        } catch (\Exception $e) {
            \Log::error('Role deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the role. Please try again.'
            ], 500);
        }
    }
}
