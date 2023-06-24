@extends('base_layout')

@section('title', 'Forma de Pagamento')

@section('content')
    <div class="card w-50 p-4 card-custom">
        <div class="card-body text-center">
            <div class="card-image d-flex justify-content-center mb-5">
                <img src="{{ asset('images/perfectpay_logo.png') }}" class="img-fluid mx-auto" alt="Perfect Pay Logo">
            </div>

            <h4 class="card-title mb-4">Olá! Escolha sua forma de pagamento:</h4>

            <div id="payment-options">
                <div id="payment-options-tab" class="d-grid gap-3 nav nav-tabs" role="tablist">
                    <button id="btn-boleto" class="btn btn-custom active" data-bs-toggle="tab"
                            data-bs-target="#boleto-tab-pane"
                    type="button" role="tab" aria-controls="boleto-tab-pane" aria-selected="true">
                        Boleto
                    </button>
                    <button id="btn-cartao" class="btn btn-custom" data-bs-toggle="tab" data-bs-target="#cartao-tab-pane"
                    type="button" role="tab" aria-controls="cartao-tab-pane" aria-selected="false">
                        Cartão de Crédito
                    </button>
                    <button id="btn-pix" class="btn btn-custom" data-bs-toggle="tab" data-bs-target="#pix-tab-pane"
                    type="button" role="tab" aria-controls="pix-tab-pane" aria-selected="false">
                        PIX
                    </button>
                </div>
            </div>

            <hr>

            <form class="form" action="" method="POST">
                @csrf
                <div class="row mt-4">
                    <div class="col-12 col-sm-6">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="valor_cobranca"
                                   name="valor_cobranca"
                                   title="Valor da Cobrança" min="0" step="1">
                            <label for="valor_cobranca">Valor da Cobrança (R$)</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="vencimento_cobranca"
                                   name="vencimento_cobranca"
                                   title="Vencimento da Cobrança">
                            <label for="vencimento_cobranca">Vencimento da Cobrança</label>
                        </div>
                    </div>
                </div>

                <div class="tab-content mt-4 mb-5" id="payment-tabs">
                    <div class="tab-pane fade show active" id="boleto-tab-pane" role="tabpanel"
                         aria-labelledby="btn-boleto"
                         tabindex="0">
                        <hr>
                        <p>
                            Você escolheu a opção <strong>Boleto</strong>. Após finalizar o pagamento, será
                            exibido um link para visualizar sua fatura.
                        </p>
                    </div>
                    <div class="tab-pane fade" id="cartao-tab-pane" role="tabpanel" aria-labelledby="btn-cartao"
                         tabindex="0">
                        <hr>
                        <p class="mb-4">Você escolheu a opção <strong>Cartão</strong>. Informe os dados abaixo e finalize o
                            pagamento.</p>

                            <hr>
                            <p style="font-style: italic">Dados do Proprietário do Cartão</p>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nome_proprietario"
                                               name="nome_proprietario"
                                               title="Proprietário do Cartão">
                                        <label for="nome_proprietario">Nome</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="cpf_cnpj_proprietario" name="cpf_cnpj_proprietario"
                                               title="Somente números" maxlength="20">
                                        <label for="cpf_cnpj_proprietario">CPF/CNPJ</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email_proprietario" name="email_proprietario"
                                               title="test@perfectpay.com.br" maxlength="20">
                                        <label for="email_proprietario">E-mail</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="cep_proprietario"
                                               name="cep_proprietario"
                                               title="Somente números" maxlength="8">
                                        <label for="cep_proprietario">CEP</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="numero_proprietario"
                                               name="numero_proprietario"
                                               title="Número da Residência" min="1">
                                        <label for="numero_proprietario">Número</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="complemento_proprietario"
                                               name="complemento_proprietario"
                                               title="Complemento da Residência (opcional)" maxlength="20">
                                        <label for="complemento_proprietario">Complemento</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="telefone_proprietario"
                                               name="telefone_proprietario"
                                               title="DDD + Número (Somente números)" maxlength="20">
                                        <label for="telefone_proprietario">Telefone</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="celular_proprietario"
                                               name="celular_proprietario"
                                               title="DDD + Número (Somente números)" maxlength="20">
                                        <label for="celular_proprietario">Celular</label>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <p style="font-style: italic">Dados do Cartão</p>

                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nome_cartao"
                                               name="nome_cartao"
                                               title="Nome Impresso no Cartão">
                                        <label for="nome_cartao">Nome no Cartão</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="numero_cartao"
                                               name="numero_cartao"
                                               title="Número do Cartão" maxlength="20">
                                        <label for="numero_cartao">Número</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="mes_cartao"
                                               name="mes_cartao"
                                               title="Mês de expiração (ex: 06)" minlength="1" maxlength="2">
                                        <label for="mes_cartao">Mês</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="ano_cartao"
                                               name="ano_cartao"
                                               title="Ano de expiração com 4 dígitos (ex: 2019)" minlength="4"
                                               maxlength="4">
                                        <label for="ano_cartao">Ano</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="cvv_cartao"
                                               name="cvv_cartao"
                                               title="Código de segurança" minlength="3" maxlength="3">
                                        <label for="cvv_cartao">CVV</label>
                                    </div>
                                </div>
                            </div>

                    </div>
                    <div class="tab-pane fade" id="pix-tab-pane" role="tabpanel" aria-labelledby="btn-pix"
                         tabindex="0">
                        <hr>
                        <p>
                            Você escolheu a opção <strong>PIX</strong>. Escaneie o QR Code abaixo ou utilize o
                            código de Copia & Cola
                        </p>
                    </div>
                </div>

                <button class="btn btn-lg btn-success">Finalizar Pagamento</button>
            </form>

        </div>
    </div>
@endsection
