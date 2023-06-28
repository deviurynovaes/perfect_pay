## Perfect Pay

Sistema de processamento de pagamentos integrado ao ambiente de homologação do Asaas, levando em consideração que o cliente deve acessar uma página onde irá selecionar a opção de pagamento entre Boleto, Cartão ou Pix.

### Instruções para rodar o projeto:

- Clonar o projeto
- Rodar composer install
- Criar .env a partir do .env.example
- Criar banco de dados local
- Rodar migrations (php artisan migrate)
- Instanciar servidor (php artisan serve)

### Variáveis de Ambiente - Asaas

``` 
ASAAS_API_DOMAIN="https://sandbox.asaas.com/api/v3"
ASAAS_API_KEY="$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAwNTc2Mzc6OiRhYWNoX2U2MDEwMzI2LTZmM2EtNGNmZS1iYjcxLWYxZTM0OGIyYTZhZQ=="
```

### Usuários para Teste

- iury@test.com
- su@test.com

### Atenção

Notei que existe um sdk para integração com o Asaas, porém quis desenvolver uma classe para exemplificar o consumo de API.
