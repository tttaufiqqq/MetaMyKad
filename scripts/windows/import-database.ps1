param(
    [string]$Database = "metamykad",
    [string]$User = "root",
    [string]$MySqlExe = "mysql"
)

$ErrorActionPreference = "Stop"
$ProjectRoot = Resolve-Path (Join-Path $PSScriptRoot "..\..")

$files = @(
    "database\schema.sql",
    "database\functions.sql",
    "database\views.sql",
    "database\procedures.sql"
)

& $MySqlExe -u $User -p -e "CREATE DATABASE IF NOT EXISTS $Database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
if ($LASTEXITCODE -ne 0) {
    throw "Failed to create database."
}

foreach ($file in $files) {
    $path = Join-Path $ProjectRoot $file
    if (-not (Test-Path $path)) {
        throw "Missing SQL file: $path"
    }

    Write-Host "Importing $file"
    Get-Content -Raw $path | & $MySqlExe -u $User -p $Database
    if ($LASTEXITCODE -ne 0) {
        throw "Failed to import $file"
    }
}

Write-Host "Database import completed."

