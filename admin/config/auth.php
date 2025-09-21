<?php
return [
    'admin_username' => 'admin',
    'admin_password_hash' => password_hash('admin123', PASSWORD_DEFAULT), // Change this!
    'session_name' => 'onboard_admin_session',
    'session_lifetime' => 3600, // 1 hour
    'max_login_attempts' => 5,
    'lockout_duration' => 900, // 15 minutes
];
