<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    // Create a new profile
    public static function createProfile($data)
    {
        return self::create($data);
    }

    // Read a profile by ID
    public static function getProfileById($id)
    {
        return self::find($id);
    }

    // Update a profile by ID
    public static function updateProfile($id, $data)
    {
        $profile = self::find($id);
        if ($profile) {
            $profile->update($data);
            return $profile;
        }
        return null;
    }

    // Delete a profile by ID
    public static function deleteProfile($id)
    {
        $profile = self::find($id);
        if ($profile) {
            $profile->delete();
            return true;
        }
        return false;
    }
}
