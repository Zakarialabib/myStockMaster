<?php

declare(strict_types=1);
exec('php composer.json install');

// Redirect to the next step
header('Location: install.php?step=3');
exit;
