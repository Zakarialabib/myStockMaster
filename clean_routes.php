<?php

declare(strict_types=1);
$content = file_get_contents('routes/web.php');
$lines = explode("\n", $content);
$newLines = [];
foreach ($lines as $line) {
    if (strpos($line, 'HomeController::class, \'changeLanguage\'') !== false) {
        continue;
    }
    if (strpos($line, 'HomeController::class, \'salesPurchasesChart\'') !== false) {
        continue;
    }
    if (strpos($line, 'HomeController::class, \'currentMonthChart\'') !== false) {
        continue;
    }
    if (strpos($line, 'HomeController::class, \'paymentChart\'') !== false) {
        continue;
    }
    if (strpos($line, 'ExportController::class') !== false) {
        continue;
    }
    if (strpos($line, 'SendQuotationEmailController::class') !== false) {
        continue;
    }
    if (strpos($line, 'QuotationSalesController::class') !== false) {
        continue;
    }
    if (strpos($line, 'IntegrationController::class') !== false) {
        continue;
    }
    if (strpos($line, 'PurchasesReturnController::class') !== false) {
        continue;
    }
    if (strpos($line, 'SalesReturnController::class') !== false) {
        continue;
    }
    $newLines[] = $line;
}
file_put_contents('routes/web.php', implode("\n", $newLines));
