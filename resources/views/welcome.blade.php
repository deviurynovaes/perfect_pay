@extends('base_layout')

@section('title', 'Pagamento')

@section('content')
    <div id="loading" class="d-none" >
        <div class="spinner-border">
        </div>
    </div>

    <div id="card-global" class="card w-50 p-4 card-custom">
        <div class="card-body text-center">
            <div class="card-image d-flex justify-content-center mb-5">
                <img src="{{ asset('images/perfectpay_logo.png') }}" class="img-fluid mx-auto" alt="Perfect Pay Logo">
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <p><b>Desculpa :(</b></p>
                    <ul style="list-style: none">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section id="client-area">
                <h4 class="card-title mb-4">Área do Cliente</h4>

                <p>Identifique-se com seu e-mail</p>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email_cliente"
                                   title="E-mail do Cliente">
                            <label for="email_cliente">E-mail</label>
                        </div>
                    </div>
                </div>

                <button id="btn-continue" class="btn btn-custom mt-5" >Continuar</button>
            </section>

            <section id="payment-area" class="d-none">
                <h4 class="card-title mb-4">Olá, <span id="nome_cliente"></span>! <br> Escolha sua forma de
                    pagamento:</h4>

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

                <form id="form" action="{{route('invoice.create')}}" method="POST">
                    @csrf

                    <input type="hidden" id="tipo_pagamento" name="tipo_pagamento" value="1">
                    <input type="hidden" id="codigo_cliente" name="codigo_cliente" value="">

                    <div class="row mt-4">
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="valor_cobranca"
                                       name="valor_cobranca"
                                       title="Valor da Cobrança" required>
                                <label for="valor_cobranca">Valor da Cobrança (R$)</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="vencimento_cobranca"
                                       name="vencimento_cobranca"
                                       title="Vencimento da Cobrança" required>
                                <label for="vencimento_cobranca">Vencimento da Cobrança</label>
                            </div>
                        </div>
                    </div>

                    <div id="div-parcelas" class="row d-none mt-3">
                        <div class="col-6">
                            <div class="form-floating">
                                <select class="form-control" id="parcelas_cobranca" name="parcelas_cobranca" title="Nº de
                             Parcelas da Cobrança" onchange="updateInstallmentValue();">
                                    <option value="" selected>Selecione o Nº de Parcelas</option>
                                    <option value="1">1x (à vista, sem juros)</option>
                                    <option value="2">2x (sem juros)</option>
                                    <option value="3">3x (sem juros)</option>
                                    <option value="4">4x (sem juros)</option>
                                    <option value="5">5x (sem juros)</option>
                                    <option value="6">6x (sem juros)</option>
                                    <option value="7">7x (sem juros)</option>
                                    <option value="8">8x (sem juros)</option>
                                    <option value="9">9x (sem juros)</option>
                                    <option value="10">10x (sem juros)</option>
                                    <option value="11">11x (sem juros)</option>
                                    <option value="12">12x (sem juros)</option>
                                </select>
                                <label for="parcelas_cobranca">Nº de Parcelas *</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="valor_parcela"
                                       title="Valor da Parcela" readonly>
                                <label for="valor_parcela">Valor da Parcela (R$)</label>
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
                                               title="Proprietário do Cartão" minlength="3" maxlength="50">
                                        <label for="nome_proprietario">Nome *</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="cpf_cnpj_proprietario" name="cpf_cnpj_proprietario"
                                               title="Somente números" maxlength="20">
                                        <label for="cpf_cnpj_proprietario">CPF/CNPJ *</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email_proprietario" name="email_proprietario"
                                               title="test@perfectpay.com.br" maxlength="50">
                                        <label for="email_proprietario">E-mail *</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="cep_proprietario"
                                               name="cep_proprietario"
                                               title="Somente números" maxlength="8">
                                        <label for="cep_proprietario">CEP *</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="numero_proprietario"
                                               name="numero_proprietario"
                                               title="Número da Residência" min="1">
                                        <label for="numero_proprietario">Número *</label>
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
                                        <label for="telefone_proprietario">Telefone *</label>
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
                                               title="Nome Impresso no Cartão" minlength="3" maxlength="50">
                                        <label for="nome_cartao">Nome no Cartão *</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="numero_cartao"
                                               name="numero_cartao"
                                               title="Número do Cartão" maxlength="20">
                                        <label for="numero_cartao">Número *</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" id="mes_cartao" name="mes_cartao"
                                                title="Mês de expiração (ex: 06)">
                                            <option value="" selected>Selecione o mês</option>
                                            <option value="01">01 - janeiro</option>
                                            <option value="02">02 - fevereiro</option>
                                            <option value="03">03 - março</option>
                                            <option value="04">04 - abril</option>
                                            <option value="05">05 - maio</option>
                                            <option value="06">06 - junho</option>
                                            <option value="07">07 - julho</option>
                                            <option value="08">08 - agosto</option>
                                            <option value="09">09 - setembro</option>
                                            <option value="10">10 - outubro</option>
                                            <option value="11">11 - novembro</option>
                                            <option value="12">12 - dezembro</option>
                                        </select>
                                        <label for="mes_cartao">Mês *</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="ano_cartao"
                                               name="ano_cartao"
                                               title="Ano de expiração com 4 dígitos (ex: 2019)" minlength="4"
                                               maxlength="4">
                                        <label for="ano_cartao">Ano *</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="cvv_cartao"
                                               name="cvv_cartao"
                                               title="Código de segurança" minlength="3" maxlength="3">
                                        <label for="cvv_cartao">CVV *</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="pix-tab-pane" role="tabpanel" aria-labelledby="btn-pix"
                             tabindex="0">
                            <hr>
                            <p>
                                Você escolheu a opção <strong>PIX</strong>. Após finalizar o pagamento, será
                                exibido o QR Code e o Copia e Cola como opções de pagamento.
                            </p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-2">
                        <button id="btn-back" type="button" class="btn btn-lg btn-default">Voltar</button>
                        <button id="btn-submit" type="button" class="btn btn-lg btn-success">Finalizar
                            Pagamento</button>
                    </div>

                </form>
            </section>

        </div>
    </div>
@endsection

@section('script')
    <script>

        var customer = JSON.parse(window.localStorage.getItem('customer'));
        var tipoCobranca = $('#payment-options-tab button.active')[0].id;

        $(document).ready(function() {

            const options = {
                clearIfNotMatch: true
            };

            const options_with_reverse = { ...options, reverse: true };

            $('#valor_cobranca').mask('000.000.000.000.000,00', { reverse: true });
            $('#valor_parcela').mask('000.000.000.000.000,00', { reverse: true });
            $('#cpf_cnpj_proprietario').mask('000.000.000-00', options_with_reverse);
            $('#cep_proprietario').mask('00000-000', options);
            $('#telefone_proprietario').mask('(00) 0.0000-0000', options);
            $('#celular_proprietario').mask('(00) 0.0000-0000', options);

            $('#numero_cartao').mask('0000 0000 0000 0000', options);
            $('#ano_cartao').mask('0000', options);
            $('#cvv_cartao').mask('000', options);

            $('#cpf_cnpj_proprietario').on('change', function() {
                if ($(this).val().length > 14) {
                    $(this).mask('00.000.000/0000-00', options_with_reverse);
                }
            });

            let dataHoje = new Date().toISOString().split('T')[0];
            $('#vencimento_cobranca').attr('min', dataHoje);

            if(customer) {
                setCustomer(customer);
            }

        });

        $('#valor_cobranca').on('change', function() {
            if (tipoCobranca === "btn-cartao") {
                updateInstallmentValue();
            }
        });

        $('#btn-continue').on('click', function(e) {
            e.preventDefault();

            let email = $('#email_cliente').val();

            if (email !== null && email.length > 0) {
                $.ajax({
                    url: '{{route('invoice.find')}}?email=' + $('#email_cliente').val(),
                    method: 'get',
                    beforeSend: function() {
                        $('#loading').removeClass('d-none');
                        $('#card-global').addClass('d-none');
                    },
                    complete: function() {
                        $('#loading').addClass('d-none');
                        $('#card-global').removeClass('d-none');
                    },
                    success: function (res) {
                        if (res.success) {
                            window.localStorage.setItem('customer', JSON.stringify(res.result));
                            setCustomer(res.result);
                        } else {
                            alert('Cliente com e-mail ('+email+') não encontrado');
                        }
                    },
                    error: function (e) {
                        alert('Falha na busca por e-mail');
                    }
                });
            } else {
                alert('Informe o e-mail para continuar');
            }

        });

        $('#btn-back').on('click', function(e) {
            e.preventDefault();
            $('#client-area').removeClass('d-none');
            $('#payment-area').addClass('d-none');
        });

        $('button[role="tab"]').on('click', function() {

            tipoCobranca = $('#payment-options-tab button.active')[0].id;

            resetFields();

            switch (tipoCobranca) {
                case 'btn-boleto':
                    $('#tipo_pagamento').val(1);
                    break;
                case 'btn-pix':
                    $('#tipo_pagamento').val(2);
                    break;
                case 'btn-cartao':
                    $('#tipo_pagamento').val(3);
                    setRequiredFields();
                    updateInstallmentValue();
                    break;
            }
        });

        function setCustomer(customer) {
            $('#codigo_cliente').val(customer.id);
            $('#email_cliente').val(customer.email);
            $('#nome_cliente').html(customer.name);
            $('#payment-area').removeClass('d-none');
            $('#client-area').addClass('d-none');
        }

        function updateInstallmentValue() {
            let valorCobranca = $('#valor_cobranca').val().replaceAll('.', '').replaceAll(',', '.');
            let numParcelas = $('#parcelas_cobranca option:selected').val();
            let valorParcela = parseFloat(valorCobranca) / parseFloat(numParcelas);

            $('#valor_parcela').val(valorParcela.toFixed(2)).trigger('input');
        }

        function resetFields() {
            $('#div-parcelas').addClass('d-none');
            $('#parcelas_cobranca').val('').change().removeAttr('required');

            $('#nome_proprietario').val('').removeAttr('required');
            $('#cpf_cnpj_proprietario').val('').removeAttr('required');
            $('#email_proprietario').val('').removeAttr('required');
            $('#cep_proprietario').val('').removeAttr('required');
            $('#numero_proprietario').val('').removeAttr('required');
            $('#complemento_proprietario').val('');
            $('#telefone_proprietario').val('').removeAttr('required');
            $('#celular_proprietario').val('');

            $('#nome_cartao').val('').removeAttr('required');
            $('#numero_cartao').val('').removeAttr('required');
            $('#mes_cartao').val('').removeAttr('required');
            $('#ano_cartao').val('').removeAttr('required');
            $('#cvv_cartao').val('').removeAttr('required');
        }

        function setRequiredFields() {
            $('#div-parcelas').removeClass('d-none');
            $('#parcelas_cobranca').val('1').attr('required', '').change();

            $('#nome_proprietario').val('').attr('required', '');
            $('#cpf_cnpj_proprietario').val('').attr('required', '');
            $('#email_proprietario').val('').attr('required', '');
            $('#cep_proprietario').val('').attr('required', '');
            $('#numero_proprietario').val('').attr('required', '');
            $('#complemento_proprietario').val('');
            $('#telefone_proprietario').val('').attr('required', '');
            $('#celular_proprietario').val('');

            $('#nome_cartao').val('').attr('required', '');
            $('#numero_cartao').val('').attr('required', '');
            $('#mes_cartao').val('').attr('required', '');
            $('#ano_cartao').val('').attr('required', '');
            $('#cvv_cartao').val('').attr('required', '');
        }

        function prepareFormData() {
            $('#valor_cobranca').val($('#valor_cobranca').val().replaceAll('.', '').replaceAll(',', '.'));
            $('#cpf_cnpj_proprietario').val($('#cpf_cnpj_proprietario').val().replaceAll(/\D/g, ''));
            $('#cep_proprietario').val($('#cep_proprietario').val().replaceAll(/\D/g, ''));
            $('#telefone_proprietario').val($('#telefone_proprietario').val().replaceAll(/\D/g, ''));
            $('#celular_proprietario').val($('#celular_proprietario').val().replaceAll(/\D/g, ''));
            $('#numero_cartao').val($('#numero_cartao').val().replaceAll(/\D/g, ''));
        }

        $('#btn-submit').on('click', function(e) {
            e.preventDefault();
            prepareFormData();
            $('#form').submit();
        })

    </script>
@endsection
