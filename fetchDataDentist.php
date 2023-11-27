<?php 
// Include config file 
include_once 'config.php'; 
 
// Database connection info 
$dbDetails = array( 
    'host' => DB_HOST, 
    'user' => DB_USER, 
    'pass' => DB_PASS, 
    'db'   => DB_NAME 
); 
 
// DB table to use 
$table = 'dentist_list'; 
 
// Table's primary key 
$primaryKey = 'id'; 
 
// Array of database columns which should be read and sent back to DataTables. 
// The `db` parameter represents the column name in the database.  
// The `dt` parameter represents the DataTables column identifier. 
$columns = array( 
    array( 'db' => 'dentist_name',      'dt' => 0 ), 
    array( 'db' => 'address',     'dt' => 1 ), 
    array( 'db' => 'contact_num',    'dt' => 2 ),
    array( 
        'db'=> 'id',
        'dt'=> 3,
        'formatter' => function( $d, $row ) { 
            return ' 
                <a href="javascript:void(0);" class="btn btn-warning btn-sm m-1" onclick="editData('.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').')"><i class="fa-solid fa-pencil me-1"></i><span class=".d-none .d-sm-block .d-sm-none .d-md-block">Edit</span></a>&nbsp; 
                <a href="javascript:void(0);" class="btn btn-danger btn-sm m-1" onclick="deleteData('.$d.')"><i class="fa-solid fa-trash-can me-1"></i><span class="hidden-sm hidden-xs">Delete</span></a> 
            '; 
        }
        )
); 
 
// Include SQL query processing class 
require 'ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ) 
);  