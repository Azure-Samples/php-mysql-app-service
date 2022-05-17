<?php

// Configuration for database connection

$host       = getenv('DB_HOST');
$username   = getenv('DB_USERNAME');
$password   = getenv('DB_PASSWORD');
$db_name     = getenv('DB_DATABASE');
$sslcert    = "ssl/DigiCertGlobalRootCA.crt.pem";
