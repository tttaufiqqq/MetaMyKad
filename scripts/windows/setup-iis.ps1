param(
    [string]$SiteName = "Default Web Site",
    [string]$AppPath = "/2026/all/GroupMDB/GS02/MetaMyKad",
    [string]$AppPoolName = "MetaMyKad",
    [string]$ProjectPath = "",
    [string]$PhpCgiPath = "C:\php\php-cgi.exe",
    [switch]$SkipPhpHandler
)

$ErrorActionPreference = "Stop"

if (-not ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole] "Administrator")) {
    throw "Run this PowerShell script as Administrator."
}

if ($ProjectPath -eq "") {
    $ProjectPath = Resolve-Path (Join-Path $PSScriptRoot "..\..")
} else {
    $ProjectPath = Resolve-Path $ProjectPath
}

$PublicPath = Join-Path $ProjectPath "public"
$StoragePath = Join-Path $ProjectPath "storage"
$AppCmd = Join-Path $env:windir "System32\inetsrv\appcmd.exe"

if (-not (Test-Path $AppCmd)) {
    throw "appcmd.exe not found. Make sure IIS is installed."
}

if (-not (Test-Path $PublicPath)) {
    throw "Missing public folder: $PublicPath"
}

& $AppCmd list apppool $AppPoolName | Out-Null
if ($LASTEXITCODE -ne 0) {
    & $AppCmd add apppool /name:$AppPoolName | Out-Null
}

$FullAppName = "$SiteName$AppPath"
& $AppCmd list app $FullAppName | Out-Null
if ($LASTEXITCODE -eq 0) {
    & $AppCmd set app $FullAppName /physicalPath:$PublicPath | Out-Null
    & $AppCmd set app $FullAppName /applicationPool:$AppPoolName | Out-Null
} else {
    & $AppCmd add app /site.name:$SiteName /path:$AppPath /physicalPath:$PublicPath /applicationPool:$AppPoolName | Out-Null
}

if (-not $SkipPhpHandler) {
    if (-not (Test-Path $PhpCgiPath)) {
        throw "php-cgi.exe not found at $PhpCgiPath. Pass -PhpCgiPath with the correct path, or use -SkipPhpHandler."
    }

    & $AppCmd set config /section:handlers /+"[name='PHP via FastCGI',path='*.php',verb='*',modules='FastCgiModule',scriptProcessor='$PhpCgiPath',resourceType='Either']" | Out-Null
}

if (Test-Path $StoragePath) {
    $Identity = "IIS AppPool\$AppPoolName"
    & icacls $StoragePath /grant "${Identity}:(OI)(CI)M" /T | Out-Null
}

Write-Host "IIS application configured."
Write-Host "Site: $SiteName"
Write-Host "Application path: $AppPath"
Write-Host "Physical path: $PublicPath"
Write-Host "App pool: $AppPoolName"

