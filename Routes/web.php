<?php

Route::prefix('epanel')->as('epanel.')->middleware(['auth', 'check.permission:Inbox'])->group(function() 
{
    Route::resources([
        'inbox' => 'InboxController'
    ]);
});