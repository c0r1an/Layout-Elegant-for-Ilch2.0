$ErrorActionPreference = 'Stop'

$layoutRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$configFile = Join-Path $layoutRoot 'config\config.php'
$distDir = Join-Path $layoutRoot 'dist'

if (-not (Test-Path $configFile)) {
    throw 'config\config.php not found.'
}

$configContent = Get-Content $configFile -Raw
$versionMatch = [regex]::Match($configContent, "'version'\s*=>\s*'([^']+)'")
$version = if ($versionMatch.Success) { $versionMatch.Groups[1].Value } else { 'dev' }

if (-not (Test-Path $distDir)) {
    New-Item -ItemType Directory -Path $distDir | Out-Null
}

$zipName = "elegant-v$version.zip"
$zipPath = Join-Path $distDir $zipName

if (Test-Path $zipPath) {
    Remove-Item $zipPath -Force
}

$tempRoot = Join-Path ([System.IO.Path]::GetTempPath()) ("elegant-export-" + [guid]::NewGuid().ToString('N'))
$tempLayoutDir = Join-Path $tempRoot 'elegant'

New-Item -ItemType Directory -Path $tempLayoutDir -Force | Out-Null

Get-ChildItem $layoutRoot -Force | Where-Object {
    $_.Name -notin @('dist', '.git')
} | ForEach-Object {
    Copy-Item $_.FullName -Destination $tempLayoutDir -Recurse -Force
}

Compress-Archive -Path (Join-Path $tempRoot 'elegant') -DestinationPath $zipPath -Force
Remove-Item $tempRoot -Recurse -Force

Write-Output "Created package: $zipPath"
