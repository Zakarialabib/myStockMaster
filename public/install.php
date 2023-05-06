<?php

declare(strict_types=1);
$step = isset($_GET['step']) ? intval($_GET['step']) : 1;

switch ($step) {
    case 1:
        require_once 'install/check_requirements.php';

        break;
    case 2:
        require_once 'install/install_dependencies.php';

        break;
    case 3:
        require_once 'install/configure_env_db.php';

        break;
    case 4:
        require_once 'install/migrate_seed.php';

        break;
    case 5:
        require_once 'install/complete.php';

        break;
    default:
        exit('Invalid installation step.');

        break;
}
