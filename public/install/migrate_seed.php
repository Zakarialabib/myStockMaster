<?php

declare(strict_types=1);
exec('php artisan migrate');
exec('php artisan db:seed --class=SpecificSeeder');

// Redirect to the next step
header('Location: install.php?step=5');
exit;
