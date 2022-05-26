$attdelete = 0;
$files = glob('images/*');
foreach ($files as $file) {
    if (is_file($file))
        $attdelete++;
}

if ($attdelete == 1000) {
    $files = glob('images/*');
    foreach ($files as $file) {
        if (is_file($file))
            unlink($file);
    }
}