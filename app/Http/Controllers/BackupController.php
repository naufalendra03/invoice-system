<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;

class BackupController extends Controller
{

public function backup()
{

$filename = 'backup-'.date('Y-m-d-H-i-s').'.zip';
$zipPath = storage_path($filename);

/*
CREATE ZIP
*/

$zip = new ZipArchive;

if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {

/*
EXPORT DATABASE
*/

$db = env('DB_DATABASE');
$user = env('DB_USERNAME');
$pass = env('DB_PASSWORD');

$sqlFile = storage_path('database.sql');

$command = "mysqldump --user=$user --password=$pass $db > $sqlFile";

system($command);

/*
ADD SQL TO ZIP
*/

$zip->addFile($sqlFile,'database.sql');

/*
ADD INVOICE FILES
*/

$invoicePath = storage_path('app/public/invoices');

if(file_exists($invoicePath)){

$files = scandir($invoicePath);

foreach($files as $file){

if($file != '.' && $file != '..'){

$zip->addFile(
$invoicePath.'/'.$file,
'invoices/'.$file
);

}

}

}

$zip->close();

}

/*
DOWNLOAD FILE
*/

return response()->download($zipPath)->deleteFileAfterSend(true);

}

public function download()
{

$filename = 'backup-'.date('Y-m-d-H-i-s').'.zip';
$zipPath = storage_path($filename);

$zip = new ZipArchive;

if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {

/* EXPORT DATABASE */

$db = env('DB_DATABASE');
$user = env('DB_USERNAME');
$pass = env('DB_PASSWORD');

$sqlFile = storage_path('database.sql');

$command = "mysqldump --user=$user --password=$pass $db > $sqlFile";

system($command);

$zip->addFile($sqlFile,'database.sql');


/* ADD INVOICE FILES */

$invoicePath = storage_path('app/public/invoices');

if(file_exists($invoicePath)){

$files = scandir($invoicePath);

foreach($files as $file){

if($file != '.' && $file != '..'){

$zip->addFile(
$invoicePath.'/'.$file,
'invoices/'.$file
);

}

}

}


/* ADD META FILE */

$meta = [
"system"=>"Invoice System",
"date"=>date('Y-m-d H:i:s')
];

file_put_contents(storage_path('meta.json'),json_encode($meta));

$zip->addFile(storage_path('meta.json'),'meta.json');

$zip->close();

}

return response()->download($zipPath)->deleteFileAfterSend(true);

}

public function restore(Request $request)
{

$request->validate([
'backup'=>'required|file'
]);

$file = $request->file('backup');

$zipPath = storage_path('restore.zip');

$file->move(storage_path(), 'restore.zip');

$zip = new ZipArchive;

if($zip->open($zipPath) === TRUE){

$zip->extractTo(storage_path('restore'));
$zip->close();

}


/* RESTORE DATABASE */

$sqlFile = storage_path('restore/database.sql');

$db = env('DB_DATABASE');
$user = env('DB_USERNAME');
$pass = env('DB_PASSWORD');

$command = "mysql --user=$user --password=$pass $db < $sqlFile";

system($command);


/* RESTORE INVOICE FILE */

$source = storage_path('restore/invoices');
$dest = storage_path('app/public/invoices');

if(file_exists($source)){

foreach(scandir($source) as $file){

if($file!='.' && $file!='..'){

copy($source.'/'.$file,$dest.'/'.$file);

}

}

}

return back()->with('success','Restore berhasil');

}

}