$ErrorActionPreference = 'Stop'

$layoutRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$applicationRoot = Split-Path -Parent (Split-Path -Parent $layoutRoot)
$moduleRoot = Join-Path $applicationRoot 'modules\elegant'
$layoutConfigFile = Join-Path $layoutRoot 'config\config.php'
$distDir = Join-Path $layoutRoot 'dist'

if (-not (Test-Path $layoutConfigFile)) {
    throw 'layouts\elegant\config\config.php not found.'
}

if (-not (Test-Path $moduleRoot)) {
    throw 'application\modules\elegant not found.'
}

$configContent = Get-Content $layoutConfigFile -Raw
$versionMatch = [regex]::Match($configContent, "'version'\s*=>\s*'([^']+)'")
$version = if ($versionMatch.Success) { $versionMatch.Groups[1].Value } else { 'dev' }

if (-not (Test-Path $distDir)) {
    New-Item -ItemType Directory -Path $distDir | Out-Null
}

$zipName = "elegant-github-v$version.zip"
$zipPath = Join-Path $distDir $zipName

if (Test-Path $zipPath) {
    Remove-Item $zipPath -Force
}

$tempRoot = Join-Path ([System.IO.Path]::GetTempPath()) ("elegant-github-export-" + [guid]::NewGuid().ToString('N'))
$repoRoot = Join-Path $tempRoot 'elegant-github'
$layoutTarget = Join-Path $repoRoot 'application\layouts\elegant'
$moduleTarget = Join-Path $repoRoot 'application\modules\elegant'

New-Item -ItemType Directory -Path $layoutTarget -Force | Out-Null
New-Item -ItemType Directory -Path $moduleTarget -Force | Out-Null

Get-ChildItem $layoutRoot -Force | Where-Object {
    $_.Name -notin @('dist', '.git')
} | ForEach-Object {
    Copy-Item $_.FullName -Destination $layoutTarget -Recurse -Force
}

Get-ChildItem $moduleRoot -Force | ForEach-Object {
    Copy-Item $_.FullName -Destination $moduleTarget -Recurse -Force
}

@'
# Elegant* for Ilch 2.0

This export contains:

- `application/layouts/elegant`
- `application/modules/elegant`

Install both folders together in your Ilch installation.
'@ | Set-Content -Path (Join-Path $repoRoot 'README.md') -Encoding UTF8

Compress-Archive -Path $repoRoot -DestinationPath $zipPath -Force
Remove-Item $tempRoot -Recurse -Force

Write-Output "Created GitHub bundle: $zipPath"
