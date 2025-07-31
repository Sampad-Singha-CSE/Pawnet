<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Services\User\Interfaces\UserProfileServiceInterface;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    use AuthorizesRequests;
    protected UserProfileServiceInterface $userProfileService;

    public function __construct(UserProfileServiceInterface $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }
    /**
     * Display the user profile.
     *
     * @return JsonResponse
     */
    public function show()
    {
        $user = Auth::user();

        return response()->json([
            'user' => $user
        ]);
    }
    //create a new profile
    /**
     * Create a new user profile.
     *
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        //validate request data
        $request->validate([
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string|max:1000',
            'mobile' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);
        $this->authorize('createUserProfile', $user);
        try {
            $this->userProfileService->createOrUpdateGeneralInfo($user, $request->all());
            return response()->json(['message' => 'User profile created successfully']);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to create user profile: ',
                'message' =>$e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the user profile.
     *
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        //validate request data
        $request->validate([
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string|max:1000',
            'mobile' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);
        $this->authorize('updateUserProfile', $user);
        try {
            $this->userProfileService->createOrUpdateGeneralInfo($user, $request->all());
            // Logic to update the user profile
            return response()->json(['message' => 'User profile updated successfully']);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to update user profile: ',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
