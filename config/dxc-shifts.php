<?php

return [
    // Path to store PDF Exports
    'schedule_exports' => public_path('schedule_exports'),

    // Target email to deliver PDF.
    'target_email' => env('TARGET_EMAIL', 'test@test.com'),

    // Sending emails on every schedule change.
    'emails_on' => env('EMAILS_ON', false)
];
