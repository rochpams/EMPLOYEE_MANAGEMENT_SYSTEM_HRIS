<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$user = User::where('email', 'hr@gmail.com')->first();
if($user) {
    $user->role = 'hr';
    $user->save();
    echo "✓ Updated {$user->name} ({$user->email}) to role: hr\n";
} else {
    echo "✗ User not found\n";
}
