param(
  [string]$Output = "live-site.zip"
)
$root = Split-Path -Path $MyInvocation.MyCommand.Definition -Parent
Push-Location $root
if (Test-Path $Output) { Remove-Item $Output }
Write-Host "Creating $Output from contents of $root"
Compress-Archive -Path * -DestinationPath $Output -Force
Write-Host "Created $Output"
Pop-Location
