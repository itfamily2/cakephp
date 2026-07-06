<?php
// templates/Reports/csv/export_data.php

// 1. Output the header row
$header = ['ID', 'Product Name', 'Price (USD)'];
echo implode(',', $header) . "\n";

// 2. Output the data rows
foreach ($data as $row) {
    // Basic CSV escaping
    $product = '"' . str_replace('"', '""', $row['product']) . '"';
    
    echo implode(',', [
        $row['id'],
        $product,
        $row['price']
    ]) . "\n";
}
