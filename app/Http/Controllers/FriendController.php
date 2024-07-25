<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function sendRequest($friendId)
    {
        $user = Auth::user();
        $friend = User::find($friendId);

        if (!$friend) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->friends()->attach($friendId, ['status' => 'pending']);

        return response()->json(['message' => 'Friend request sent'], 200);
    }

    // Accepter une demande d'ami
    public function acceptRequest($userId)
    {
        $user = Auth::user();
        $friend = $user->friendRequests()->where('user_id', $userId)->first();

        if (!$friend) {
            return response()->json(['message' => 'Friend request not found'], 404);
        }

        $user->friendRequests()->updateExistingPivot($userId, ['status' => 'accepted']);

        return response()->json(['message' => 'Friend request accepted'], 200);
    }

        // Lister les amis
    public function listFriends()
    {
        $user = Auth::user();
        $friends = $user->friends()->wherePivot('status', 'accepted')->get();

        return response()->json($friends);
    }

    // Lister les demandes d'amis en attente
    public function listFriendRequests()
    {
        $user = Auth::user();
        $pendingRequests = $user->friendRequests()->get();

        return response()->json($pendingRequests);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
