<?php

namespace App\Classes;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class AsaasApi {

    private $apiKey;
    private $apiDomain;

    public function __construct() {
        $this->apiKey     = env('ASAAS_API_KEY');
        $this->apiDomain  = env('ASAAS_API_DOMAIN');
    }

    /**
     * @param $endpoint
     * @param $method
     * @param $body
     * @return object|null
     * @throws \Illuminate\Http\Client\RequestException
     */
    private function _sendRequest($endpoint, $method = 'GET', $body = []) {

        $apiKey = $this->getApiKey();

        if (empty($apiKey)) {
            throw new \Exception('API Key não encontrada');
        }

        $apiDomain = $this->getApiDomain();

        if (empty($apiDomain)) {
            throw new \Exception('URL da API não encontrada');
        }

        $fullUrl = $apiDomain . $endpoint;

        $http = Http::withHeaders([
            'Content-Type' => 'application/json',
            'access_token' => $apiKey,
        ])->timeout(180);

        switch (mb_strtoupper($method)) {
            case 'GET':
                $response = $http->get($fullUrl, $body);
                break;
            case 'POST':
                $response = $http->post($fullUrl, $body);
                break;
            default:
                throw new \Exception('Método de requisição inválido');
        }

        if($response->clientError()) {
            $errors = [];
            foreach ($response->object()->errors as $e) {
                $errors[] = $e->description;
            }

            throw new \Exception(implode(PHP_EOL, $errors));
        }

        if($response->serverError()) {
            throw new \Exception('Servidor indisponível no momento. Tente novamente mais tarde.');
        }



        return $response->object();
    }

    public function createInvoice($data) {

        $paymentType = Config::get('constants.asaas.billing_types')[$data['tipo_pagamento']];

        if (empty($paymentType)) {
            throw new \Exception('Tipo de pagamento inválido');
        }

        $customerId = $data['codigo_cliente'];

        if (empty($customerId)) {
            throw new \Exception('Identificação do cliente não fornecida');
        }

        $body = [
            'customer' => $customerId,
            'billingType' => $paymentType,
            'value' => floatval($data['valor_cobranca']),
            'dueDate' => $data['vencimento_cobranca'],
            'description' => 'Teste Perfect Pay',
        ];

        if (intval($data['tipo_pagamento']) === Config::get('constants.payment_options.cartao')) {

            $data['remoteIp'] = Utils::getUserIP();

            if ($data['parcelas_cobranca'] > 1) {
                $body['installmentCount'] = $data['parcelas_cobranca'];

                $calc = floatval($data['valor_cobranca']) / floatval($data['parcelas_cobranca']);

                $body['installmentValue'] = round($calc, 2);
            }

            $body['creditCard'] = [
                'holderName' => mb_strtoupper($data['nome_cartao']),
                'number' => $data['numero_cartao'],
                'expiryMonth' => $data['mes_cartao'],
                'expiryYear' => $data['ano_cartao'],
                'ccv' => $data['cvv_cartao'],
            ];

            $body['creditCardHolderInfo'] = [
                'name' => mb_strtoupper($data['nome_proprietario']),
                'email' => $data['nome_proprietario'],
                'cpfCnpj' => $data['cpf_cnpj_proprietario'],
                'postalCode' => $data['cep_proprietario'],
                'addressNumber' => $data['numero_proprietario'],
                'phone' => $data['telefone_proprietario'],
            ];

            if (!empty($data['complemento_proprietario'])) {
                $body['creditCardHolderInfo']['addressComplement'] = $data['complemento_proprietario'];
            }

            if (!empty($data['celular_proprietario'])) {
                $body['creditCardHolderInfo']['mobilePhone'] = $data['celular_proprietario'];
            }
        }

        $payment = $this->_sendRequest('/payments', 'POST', $body);

        if (intval($data['tipo_pagamento']) === Config::get('constants.payment_options.pix')) {
            $payment->qrCodeData = $this->getQrCodeData($payment->id);
        }

        return $payment;
    }

    public function getClientByEmail($email) {
        return $this->_sendRequest('/customers', 'GET', ['email' => $email]);
    }

    public function getQrCodeData($paymentId) {
        return $this->_sendRequest('/payments/'.$paymentId.'/pixQrCode');
    }

    /**
     * @return mixed
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return mixed
     */
    public function getApiDomain(): mixed
    {
        return $this->apiDomain;
    }
}
