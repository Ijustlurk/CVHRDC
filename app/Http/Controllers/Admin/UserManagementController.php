<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->whereDoesntHave('roles', function($query) {
            $query->where('name', 'superadmin');
        })->get();
        
        $deletedUsers = User::onlyTrashed()->get();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'deletedUsers', 'roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:roles,id']
        ], [
            'roles.required' => 'Please select at least one role for the user.',
            'roles.*.exists' => 'One or more selected roles are invalid.',
            'password.confirmed' => 'Password confirmation does not match.',
            'email.unique' => 'This email address is already taken.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed. Please check the form and try again.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if roles array is empty after conversion
            if (empty($request->roles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select at least one role for the user.',
                    'errors' => ['roles' => ['Please select at least one role for the user.']]
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->roles()->sync($request->roles);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('User creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the user. Please try again.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        // Prevent updating superadmin users by non-superadmins
        if ($user->hasRole('superadmin') && !auth()->user()->hasRole('superadmin')) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this user.'
            ], 403);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:roles,id']
        ];

        $messages = [
            'roles.required' => 'Please select at least one role for the user.',
            'roles.*.exists' => 'One or more selected roles are invalid.',
            'email.unique' => 'This email address is already taken.'
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Password::defaults()];
            $messages['password.confirmed'] = 'Password confirmation does not match.';

            // Ensure the new password is not the same as the current password
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed. Please check the form and try again.',
                    'errors' => ['password' => ['New password cannot be the same as the current password.']]
                ], 422);
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed. Please check the form and try again.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if roles array is empty after conversion
            if (empty($request->roles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select at least one role for the user.',
                    'errors' => ['roles' => ['Please select at least one role for the user.']]
                ], 422);
            }

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                // The User model automatically hashes the password via $casts
                $updateData['password'] = $request->password;
            }

            $user->update($updateData);
            $user->roles()->sync($request->roles);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('User update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the user. Please try again.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        try {
            // Prevent deletion of users with superadmin role
            if ($user->hasRole('superadmin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete superadmin users.'
                ], 403);
            }

            // Prevent self-deletion
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account.'
                ], 403);
            }

            $userName = $user->name;
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => "User '{$userName}' has been deleted successfully."
            ]);
        } catch (\Exception $e) {
            \Log::error('User deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the user. Please try again.'
            ], 500);
        }
    }

    public function restore($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Deleted user not found.'
            ], 404);
        }

        try {
            $userName = $user->name;
            $user->restore();

            return response()->json([
                'success' => true,
                'message' => "User '{$userName}' has been restored successfully."
            ]);
        } catch (\Exception $e) {
            \Log::error('User restoration failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while restoring the user. Please try again.'
            ], 500);
        }
    }
}