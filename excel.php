<?php
require_once (getcwd(). '/core/loader.php');
require_once (getcwd(). '/vendor/autoload.php');


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use GuzzleHttp\Client;
use Tron\Api;
use Tron\TRX;
use Tron\Address;
use core\DB\Db;





$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


$db = Db::getInstance();

// Add table headers
$sheet->setCellValue('A1', 'Username');
$sheet->setCellValue('B1', 'Full Name');
$sheet->setCellValue('C1', 'Account Name');
$sheet->setCellValue('D1', 'Contact');


$users = $db->query("SELECT * FROM users");
// Add table data
$row = 2;
foreach ($users as $user) {
    $sheet->setCellValue('A' . $row, $user['username']);
    $sheet->setCellValue('B' . $row, $user['fullname']);
    $sheet->setCellValue('C' . $row, $user['account_name']);
    $sheet->setCellValue('D' . $row, strval($user['contact']));
    $row++;
}
// ...

// Auto-fit column widths
foreach (range('A', 'D') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}


$writer = new Xlsx($spreadsheet);
$filename = date('Y-m-d_H-i') . '.xlsx';
$writer->save(getcwd()."/resource/".$filename);

echo  'Creatin Excel File ... in 5 second';
header("Refresh: 3; url=https://chman.janpier.site/resource/$filename");

