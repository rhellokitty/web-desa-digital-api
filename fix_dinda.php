<?php
require __DIR__.'/vendor/autoload.php';
\ = require_once __DIR__.'/bootstrap/app.php';
\ = \->make(Illuminate\Contracts\Console\Kernel::class);
\->bootstrap();

\ = \App\Models\User::where('name', 'like', '%Dinda%')->first();
if (\) {
    \App\Models\HeadOfFamily::create([
        'user_id' => \->id,
        'family_card_number' => '3201012345678901',
        'identity_number' => '3201011234567890',
        'occupation' => 'PNS',
        'salary' => 5000000,
        'date_of_birth' => '1995-05-05',
        'gender' => 'female',
        'religion' => 'Islam',
        'education' => 'S1'
    ]);
    echo 'Created HeadOfFamily for Dinda';
} else {
    echo 'Dinda not found';
}

