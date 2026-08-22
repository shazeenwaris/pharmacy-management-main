<?php

return [

    /*
     * Full path to mysqldump.exe — used to create backups.
     * Configured for XAMPP's default MySQL install location.
     */
    'mysqldump_path' => env(
        'MYSQLDUMP_PATH',
        'C:\\xampp\\mysql\\bin\\mysqldump.exe'
    ),

    /*
     * Full path to mysql.exe — used to restore backups.
     */
    'mysql_path' => env(
        'MYSQL_PATH',
        'C:\\xampp\\mysql\\bin\\mysql.exe'
    ),

];