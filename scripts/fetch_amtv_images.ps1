# Script de Coleta e Integração das Imagens de adoptmetradingvalues.com

$baseDir = $pwd.Path
$petsDir = Join-Path $baseDir "assets\images\pets"
New-Item -ItemType Directory -Force -Path $petsDir | Out-Null

$headers = @{ "User-Agent" = "Mozilla/5.0 (Windows NT 10.0; Win64; x64)" }
$rawIndexUrl = "https://raw.githubusercontent.com/2C-P/Adopt-Me-Trading-Values.github.io/main/adoptmetradingvalues.com/index.html"

Write-Host "Obtendo mapeamento de pets do adoptmetradingvalues.com..." -ForegroundColor Cyan
$content = (Invoke-WebRequest -Uri $rawIndexUrl -Headers $headers -UseBasicParsing).Content

$regex = [regex]'(?i)<div\s+id="([^"]+)"[^>]*>.*?<img[^>]*src="images/(\d+\.png)"'
$matches = $regex.Matches($content)

Write-Host "Mapeados $($matches.Count) pets únicos de adoptmetradingvalues.com." -ForegroundColor Green

$downloaded = 0
$failed = 0

foreach ($m in $matches) {
    $petName = $m.Groups[1].Value.Trim()
    $file = $m.Groups[2].Value.Trim()
    
    $cleanSlug = ($petName.ToLower() -replace '[^a-z0-9]', '_').Trim('_')
    $targetFile = Join-Path $petsDir "$cleanSlug.png"
    $targetRawFile = Join-Path $petsDir $file

    if ((Test-Path $targetFile) -and ((Get-Item $targetFile).Length -gt 1000)) {
        continue
    }

    $urlsToTry = @(
        "https://raw.githubusercontent.com/2C-P/Adopt-Me-Trading-Values.github.io/main/adoptmetradingvalues.com/images/$file",
        "https://web.archive.org/web/20230422202745im_/https://adoptmetradingvalues.com/images/$file"
    )

    $success = $false
    foreach ($url in $urlsToTry) {
        try {
            curl.exe -k -L -s --max-time 10 -o "$targetFile" "$url"
            if ((Test-Path $targetFile) -and ((Get-Item $targetFile).Length -gt 1000)) {
                Copy-Item $targetFile $targetRawFile -Force
                $success = $true
                $downloaded++
                Write-Host "[OK] $petName -> $cleanSlug.png ($file)" -ForegroundColor Green
                break
            }
        } catch {
            # Tentar próxima URL
        }
    }

    if (-not $success) {
        $failed++
        Write-Warning "[FALHA] $petName ($file)"
    }
}

Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "Coleta concluída! $downloaded novas imagens salvas em assets/images/pets/" -ForegroundColor Green
Write-Host "==========================================================" -ForegroundColor Cyan
