<?php
/**
 * MovieStreamDB_19 - Database Connection (SQL Server via SQLSRV)
 */
$serverName = ".\SQLEXPRESS";   // SQL Server Express instance
$database   = "MovieStreamDB_19";

$connectionOptions = [
    "Database"             => $database,
    "TrustServerCertificate" => true,
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    $errors = sqlsrv_errors();
    $msg = "";
    if ($errors) { foreach ($errors as $err) { $msg .= "[" . $err["SQLSTATE"] . "] " . $err["message"] . "\n"; } }
    die("<pre style='background:#1a0000;color:#ff8a80;padding:2rem;font-family:monospace'><b>Database connection failed.</b>\n\nServer: $serverName\nDatabase: $database\n\n$msg\n\nCheck: SQL Server Express is running | Windows Auth enabled | SQLSRV extension loaded</pre>");
}

function db_query($conn, string $sql, array $params = []) {
    return empty($params) ? sqlsrv_query($conn, $sql) : sqlsrv_query($conn, $sql, $params);
}
function db_fetch_all($stmt): array {
    $rows = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { $rows[] = $row; }
    return $rows;
}
function db_fetch_one($stmt): ?array {
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) ?: null;
}
