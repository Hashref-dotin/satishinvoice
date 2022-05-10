<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = $_GET['tablename'];

// Table's primary key
$primaryKey = 'order_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'invoice_id',
        'dt' => 0),
    array('db' => 'date_created',
        'dt' => 1,
        'formatter' => function ($d, $row) {
            return date('d/m/Y', strtotime($d));
        }),
    array('db' => 'order_receiver_name', 'dt' => 2),
    array('db' => 'order_date', 'dt' => 3),
    array('db' => 'order_total_after_tax', 'dt' => 4),
    array(
        'db' => 'datatype',
        'dt' => 5,
    ),
    array(
        'db' => 'order_id',
        'dt' => 6,
        'formatter' => function ($d, $row) {
            $printpage = array(
                'invoice_order' => 'print_invoice.php',
                'satish_invoice_order' => 'print_satish.php',
                'ssv_invoice_order' => 'print_ssv.php',
                'super_invoice_order' => 'print_super.php',
                'prowin_invoice_order' => 'print_prowin.php',
            );
            return '<a href="' . $printpage[$_GET['tablename']] . '?invoice_id=' . $d . '" target="_blank" title="Print Invoice"><span class="glyphicon glyphicon-print"></span></a>';
        },
    ),
    array(
        'db' => 'order_id',
        'dt' => 7,
        'formatter' => function ($d, $row) {
            $printpage = array(
                'invoice_order' => 'print_invoice.php',
                'satish_invoice_order' => 'print_satish.php',
                'ssv_invoice_order' => 'print_ssv.php',
                'super_invoice_order' => 'print_super.php',
                'prowin_invoice_order' => 'print_prowin.php',
            );
            return '<a href="' . $printpage[$_GET['tablename']] . '?invoice_id=' . $d . '&download=1" target="_blank" title="Print Invoice"><span class="glyphicon glyphicon-download"></span></a>';
        },
    ),
    array(
        'db' => 'order_id',
        'dt' => 8,
        'formatter' => function ($d, $row) {

            $editpage = array(
                'invoice_order' => 'edit_invoice.php',
                'satish_invoice_order' => 'edit_satish.php',
                'ssv_invoice_order' => 'edit_ssv.php',
                'super_invoice_order' => 'edit_super.php',
                'prowin_invoice_order' => 'edit_prowin.php',
            );

            return '<a href="' . $editpage[$_GET['tablename']] . '?update_id=' . $d . '" target="_blank" title="Edit Invoice"><span class="glyphicon glyphicon-edit"></span></a>';
        },
    ),
    array(
        'db' => 'order_id',
        'dt' => 9,
        'formatter' => function ($d, $row) {

            $deletepage = array(
                'invoice_order' => 'deleteInvoice',
                'satish_invoice_order' => 'deleteSatish',
                'ssv_invoice_order' => 'deleteSsv',
                'super_invoice_order' => 'deleteSuper',
                'prowin_invoice_order' => 'deleteSuper'
            );
            return '<a href="#" id="' . $d . '" class="' . $deletepage[$_GET['tablename']] . '"  title="Delete Invoice"><span class="glyphicon glyphicon-remove"></span></a>';
        },
    ),
);

$getconfigs = parse_ini_file('config.ini');
define('DB_USER', $getconfigs['DB_USER']);
define('DB_HOST', $getconfigs['DB_HOST']);
define('DB_NAME', $getconfigs['DB_NAME']);
define('DB_PASS', $getconfigs['DB_PASSWORD']);
// SQL server connection information
$sql_details = array(
    'user' => DB_USER,
    'pass' => DB_PASS,
    'db' => DB_NAME,
    'host' => DB_HOST,
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require 'ssp.class.php';

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
