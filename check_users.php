<?php
$user1 = App\Models\User::where('email', 'padmasari.vanya@example.net')->first();
dump('padmasari.vanya@example.net:', $user1 ? $user1->roles->pluck('name') : 'Not found');
if ($user1) {
    dump('Head of Family relation:', App\Models\HeadOfFamily::where('user_id', $user1->id)->exists());
}

$user2 = App\Models\User::where('email', 'bill@gmail.com')->first();
dump('bill@gmail.com:', $user2 ? $user2->roles->pluck('name') : 'Not found');
if ($user2) {
    dump('Head of Family relation:', App\Models\HeadOfFamily::where('user_id', $user2->id)->exists());
}
