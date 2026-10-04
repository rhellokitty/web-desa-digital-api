<?php
App\Models\HeadOfFamily::with('user')->get()->each(function ($hof) {
    if ($hof->user && !$hof->user->hasRole('head-of-family')) {
        $hof->user->assignRole('head-of-family');
        echo "Assigned role to " . $hof->user->email . "\n";
    }
});
echo "Roles synced!\n";
