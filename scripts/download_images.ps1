# Script para coletar imagens de pets do adoptmetradingvalues.com

$baseUrl = "https://raw.githubusercontent.com/2C-P/Adopt-Me-Trading-Values.github.io/main/adoptmetradingvalues.com"
$headers = @{ "User-Agent" = "AdoptMeTradeBot/1.0" }

$baseDir = if ($PSScriptRoot) { $PSScriptRoot } else { $pwd.Path }
$imgDir = Join-Path $baseDir "assets\images\pets"
$amtvDir = Join-Path $baseDir "assets\images\adoptmetradingvalues"
New-Item -ItemType Directory -Force -Path $imgDir | Out-Null
New-Item -ItemType Directory -Force -Path $amtvDir | Out-Null

Write-Host "Baixando catálogo index.html de adoptmetradingvalues.com..." -ForegroundColor Cyan
$html = (Invoke-WebRequest -Uri "$baseUrl/index.html" -Headers $headers -UseBasicParsing).Content

# Expressão regular para casar divs de pets e imagens
# Ex: <div id="Shadow Dragon" class="grid-item-white" ...><img src="images/1.png" ... alt="Shadow Dragon - ...">
$pattern = '(?i)<div\s+id="([^"]+)"[^>]*>.*?<img[^>]*src="images/(\d+\.png)"[^>]*alt="([^"]+)"'
$matches = [regex]::Matches($html, $pattern)

Write-Host "Total de pets identificados com ID exato: $($matches.Count)" -ForegroundColor Green

$petMap = @()
foreach ($m in $matches) {
    $petName = $m.Groups[1].Value.Trim()
    $imgFile = $m.Groups[2].Value.Trim()
    $alt = $m.Groups[3].Value.Trim()

    $petMap += [PSCustomObject]@{
        Name = $petName
        File = $imgFile
        Alt = $alt
    }
}

# Salvar mapa em JSON para referência
$jsonPath = Join-Path $baseDir "assets\images\pets_catalog.json"
$petMap | ConvertTo-Json -Depth 3 | Set-Content $jsonPath -Encoding utf8
Write-Host "Catálogo salvo em $jsonPath" -ForegroundColor Yellow

# Baixar imagens principais
$downloadCount = 0
foreach ($pet in $petMap) {
    $remoteImgUrl = "$baseUrl/images/$($pet.File)"
    $cleanName = ($pet.Name.ToLower() -replace '[^a-z0-9]', '_').Trim('_') + ".png"
    $localPath = Join-Path $imgDir $cleanName
    $rawPath = Join-Path $amtvDir $pet.File

    # Baixar para amtvDir
    if (-not (Test-Path $rawPath)) {
        try {
            Invoke-WebRequest -Uri $remoteImgUrl -OutFile $rawPath -UseBasicParsing -TimeoutSec 10
        } catch {
            Write-Warning "Falha ao baixar $($pet.File): $_"
        }
    }

    # Copiar com nome amigável para pets/
    if (Test-Path $rawPath) {
        Copy-Item $rawPath $localPath -Force
        $downloadCount++
        if ($downloadCount % 20 -eq 0) {
            Write-Host "Baixados $downloadCount / $($petMap.Count) pets..." -ForegroundColor Green
        }
    }
}

Write-Host "Concluído! Total de $downloadCount imagens de pets coletadas com sucesso." -ForegroundColor Cyan
