<?php
require 'config/db.php';
$stmt = sqlsrv_query($conn, "SELECT TOP 1 * FROM Movies_19 ORDER BY movie_id DESC");
$movie = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
print_r($movie);
?>
