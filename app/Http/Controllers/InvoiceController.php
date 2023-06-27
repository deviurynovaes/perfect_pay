<?php

namespace App\Http\Controllers;

use App\Classes\AsaasApi;
use App\Classes\Utils;
use App\Models\CardHolderInfo;
use App\Models\CardInfo;
use App\Models\Payment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Throwable;

class InvoiceController extends Controller
{
    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function createInvoice(Request $request)
    {
        $data = $request->all();

        $validationParams = $this->getValidationParams();
        $validator = Validator::make($data, $validationParams['rules'], $validationParams['messages']);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {

            $asaasApi = new AsaasApi();
            $payment = $asaasApi->createInvoice($data);

            $paymentArr = [
                'customer_id' => $payment->customer,
                'value' => $payment->value,
                'due_date' => $payment->dueDate,
                'billing_type' => $payment->billingType,
                'description' => $payment->description,
            ];

            if ($data['tipo_pagamento'] == Config::get('constants.payment_options.cartao')) {
                $cardHolder = CardHolderInfo::create([
                    'name' => $data['nome_proprietario'],
                    'document' => $data['cpf_cnpj_proprietario'],
                    'email' => $data['email_proprietario'],
                    'zipcode' => $data['cep_proprietario'],
                    'address_number' => $data['numero_proprietario'],
                    'address_complement' => $data['complemento_proprietario'],
                    'phone_number' => $data['telefone_proprietario'],
                    'mobile_number' => $data['celular_proprietario']
                ]);

                if (!$cardHolder) {
                    throw new \Exception('Não foi possível salvar as informações do proprietário do cartão');
                }

                $card = CardInfo::create([
                    'name' => $data['nome_cartao'],
                    'number' => $data['numero_cartao'],
                    'month' => $data['mes_cartao'],
                    'year' => $data['ano_cartao'],
                    'cvv' => $data['cvv_cartao'],
                    'card_holder_id' => $cardHolder->id
                ]);

                if (!$cardHolder) {
                    throw new \Exception('Não foi possível salvar as informações do cartão');
                }

                $paymentArr['remote_ip'] = Utils::getUserIP();
                $paymentArr['installmentCount'] = $data['parcelas_cobranca'];
                $paymentArr['installmentValue'] = round(floatval($data['valor_cobranca']) / floatval($data['parcelas_cobranca']), 2);
                $paymentArr['card_id'] = $card->id;
                $paymentArr['card_holder_id'] = $cardHolder->id;
            }

            $paymentRecord = Payment::create($paymentArr);

            if (!$paymentRecord) {
                throw new \Exception('Não foi possível salvar as informações do pagamento');
            }

            return redirect()->route('invoice.success')->with(['payment' => $payment]);

        } catch (Throwable $th) {
            return redirect()->back()->withInput()->withErrors(['msg' => $th->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function findClientByEmail(Request $request) {

        $email = $request->input('email');

        if (!empty($email)) {

            $asaasApi = new AsaasApi();
            $client = $asaasApi->getClientByEmail($email);

            return response()->json([
                'success' => (bool) count($client->data),
                'result' => $client->data[0] ?? null
            ]);
        }

        return response()->json([
            'success' => false,
            'result' => null
        ]);
    }

    /**
     * @return array[]
     */
    private function getValidationParams(): array {
        $rules = [
            'tipo_pagamento' => 'required',
            'codigo_cliente' => 'required',
            'valor_cobranca' => 'required|numeric',
            'vencimento_cobranca' => 'required|date|after_or_equal:yesterday',
            'parcelas_cobranca' => 'nullable|required_if:tipo_pagamento,=,3',
            'nome_proprietario' => 'nullable|required_if:tipo_pagamento,=,3|string|min:3|max:50',
            'cpf_cnpj_proprietario' => 'nullable|required_if:tipo_pagamento,=,3',
            'email_proprietario' => 'nullable|required_if:tipo_pagamento,=,3|email',
            'cep_proprietario' => 'nullable|required_if:tipo_pagamento,=,3',
            'numero_proprietario' => 'nullable|required_if:tipo_pagamento,=,3|numeric',
            'complemento_proprietario' => 'string|nullable',
            'telefone_proprietario' => 'nullable|required_if:tipo_pagamento,=,3',
            'nome_cartao' => 'nullable|required_if:tipo_pagamento,=,3|string|min:3|max:50',
            'numero_cartao' => 'nullable|required_if:tipo_pagamento,=,3|size:16',
            'mes_cartao' => 'nullable|required_if:tipo_pagamento,=,3|size:2',
            'ano_cartao' => 'nullable|required_if:tipo_pagamento,=,3|size:4',
            'cvv_cartao' => 'nullable|required_if:tipo_pagamento,=,3|size:3',
        ];

        $messages = [
            'tipo_pagamento.required' => 'Tipo de pagamento é obrigatório',

            'codigo_cliente.required' => 'Cliente é obrigatório',

            'valor_cobranca.required' => 'Valor da cobrança é obrigatório',
            'valor_cobranca.numeric' => 'Valor da cobrança deve ser numérico',

            'vencimento_cobranca.required' => 'Vencimento da cobrança é obrigatório',
            'vencimento_cobranca.date' => 'Vencimento da cobrança deve ser do tipo Data',
            'vencimento_cobranca.after_or_equal' => 'Vencimento da cobrança deve ser a data atual ou uma futura',

            'parcelas_cobranca.required_if' => 'Nº de Parcelas é obrigatório',

            'nome_proprietario.required_if' => 'Nome do Proprietário é obrigatório',
            'nome_proprietario.min' => 'Nome do Proprietário deve possuir no mínimo 3 caracteres',
            'nome_proprietario.max' => 'Nome do Proprietário deve possuir no máximo 50 caracteres',

            'cpf_cnpj_proprietario.required_if' => 'CPF/CNPJ é obrigatório',

            'email_proprietario.required_if' => 'E-mail é obrigatório',
            'email_proprietario.email' => 'E-mail inválido',

            'cep_proprietario.required_if' => 'CEP é obrigatório',

            'numero_proprietario.required_if' => 'Número do endereço é obrigatório',

            'telefone_proprietario.required_if' => 'Telefone é obrigatório',

            'nome_cartao.required_if' => 'Nome do Cartão é obrigatório',
            'nome_cartao.min' => 'Nome do Cartão deve possuir no mínimo 3 caracteres',
            'nome_cartao.max' => 'Nome do Cartão deve possuir no máximo 50 caracteres',

            'numero_cartao.required_if' => 'Número do Cartão é obrigatório',
            'numero_cartao.size' => 'Número do Cartão deve possuir 16 dígitos',

            'mes_cartao.required_if' => 'Mês de validade é obrigatório',
            'mes_cartao.size' => 'Mês de validade deve possuir 2 dígitos',

            'ano_cartao.required_if' => 'Ano de validade é obrigatório',
            'ano_cartao.size' => 'Ano de validade deve possuir 4 dígitos',

            'cvv_cartao.required_if' => 'CVV do Cartão é obrigatório',
            'cvv_cartao.size' => 'CVV do Cartão deve possuir 3 dígitos',
        ];

        return [
            'rules' => $rules,
            'messages' => $messages,
        ];
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application|RedirectResponse
     */
    public function success() {

        $payment = Session::get('payment') ?? null;

        if (empty($payment)) {
            return redirect()->route('invoice.index');
        }

        return view('success', compact('payment'));
    }
}
