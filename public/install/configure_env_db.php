<?php

declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appName = $_POST['app_name'];
    $appEnv = $_POST['app_env'];
    $dbConnection = $_POST['db_connection'];
    $dbHost = $_POST['db_host'];
    $dbPort = $_POST['db_port'];
    $dbDatabase = $_POST['db_database'];
    $dbUsername = $_POST['db_username'];
    $dbPassword = $_POST['db_password'];

    // Write the environment file
    $envFile = file_get_contents('.env.example');
    $envFile = str_replace('APP_NAME=Laravel', "APP_NAME={$appName}", $envFile);
    $envFile = str_replace('APP_ENV=local', "APP_ENV={$appEnv}", $envFile);
    $envFile = str_replace('DB_CONNECTION=mysql', "DB_CONNECTION={$dbConnection}", $envFile);
    $envFile = str_replace('DB_HOST=127.0.0.1', "DB_HOST={$dbHost}", $envFile);
    $envFile = str_replace('DB_PORT=3306', "DB_PORT={$dbPort}", $envFile);
    $envFile = str_replace('DB_DATABASE=mystock', "DB_DATABASE={$dbDatabase}", $envFile);
    $envFile = str_replace('DB_USERNAME=root', "DB_USERNAME={$dbUsername}", $envFile);
    $envFile = str_replace('DB_PASSWORD=root', "DB_PASSWORD={$dbPassword}", $envFile);
    // Replace other environment variables and database settings

    file_put_contents('.env', $envFile);

    // Redirect to the next step
    header('Location: install.php?step=4');
    exit;
} else {
    exit('Invalid request');
}
