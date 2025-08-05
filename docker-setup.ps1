# Script de setup do Docker para Meta_CrudUser (Windows PowerShell)
# Este script facilita a inicialização do projeto com Docker

Write-Host "🚀 Iniciando setup do Meta_CrudUser com Docker..." -ForegroundColor Green

# Verifica se o Docker está rodando
try {
    docker info | Out-Null
} catch {
    Write-Host "❌ Docker não está rodando. Por favor, inicie o Docker Desktop e tente novamente." -ForegroundColor Red
    exit 1
}

# Para containers existentes
Write-Host "🛑 Parando containers existentes..." -ForegroundColor Yellow
docker-compose down

# Remove volumes antigos (opcional)
$removeVolumes = Read-Host "Deseja remover volumes antigos? (y/N)"
if ($removeVolumes -eq "y" -or $removeVolumes -eq "Y") {
    Write-Host "🗑️ Removendo volumes antigos..." -ForegroundColor Yellow
    docker-compose down -v
}

# Build das imagens
Write-Host "🔨 Fazendo build das imagens..." -ForegroundColor Yellow
docker-compose build --no-cache

# Inicia os containers
Write-Host "🚀 Iniciando containers..." -ForegroundColor Yellow
docker-compose up -d

# Aguarda os containers iniciarem
Write-Host "⏳ Aguardando containers iniciarem..." -ForegroundColor Yellow
Start-Sleep -Seconds 10

# Verifica status dos containers
Write-Host "📊 Status dos containers:" -ForegroundColor Green
docker-compose ps

Write-Host ""
Write-Host "✅ Setup concluído!" -ForegroundColor Green
Write-Host ""
Write-Host "🌐 Acesse os serviços:" -ForegroundColor Cyan
Write-Host "   Frontend: http://localhost:5173" -ForegroundColor White
Write-Host "   Backend:  http://localhost:8000" -ForegroundColor White
Write-Host "   MongoDB:  localhost:27017" -ForegroundColor White
Write-Host "   Mongo Express: http://localhost:8081 (admin/admin123)" -ForegroundColor White
Write-Host ""
Write-Host "📝 Comandos úteis:" -ForegroundColor Cyan
Write-Host "   docker-compose logs -f backend    # Logs do backend" -ForegroundColor Gray
Write-Host "   docker-compose logs -f frontend   # Logs do frontend" -ForegroundColor Gray
Write-Host "   docker-compose logs -f mongodb    # Logs do MongoDB" -ForegroundColor Gray
Write-Host "   docker-compose down               # Parar containers" -ForegroundColor Gray
Write-Host "   docker-compose restart            # Reiniciar containers" -ForegroundColor Gray 