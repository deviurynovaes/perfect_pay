@extends('base_layout')

@section('title', 'Pagamento Finalizado')

@section('content')
    <div class="card w-50 p-4 card-custom">
        <div class="card-body text-center">
            <div class="card-image d-flex justify-content-center mb-5">
                <img src="{{ asset('images/perfectpay_logo.png') }}" class="img-fluid mx-auto" alt="Perfect Pay Logo">
            </div>

            <h1 class="card-title mb-4">OBRIGADO :)</h1>

            @if($payment->billingType === 'PIX')
                <p>Acesse o seu boleto através do botão abaixo e pague agora mesmo!</p>
                <div class="row mt-5 d-flex justify-content-center">
                    <img src="data:image/png;base64, {{$payment->qrCodeData->encodedImage}}" alt="QR Code"
                         class="img-fluid"
                         style="max-width: 300px; max-height: 300px">
                    <br>
                    <p class="mt-4"><b>PIX Copia & Cola</b></p>
                    <p class="mt-2"><code>{{$payment->qrCodeData->payload}}</code></p>
                    <p class="mt-4"><b>Valor: R$ </b>{{number_format($payment->value,2,',','.')}}</p>
                    <p class="mt-2"><b>Expira em: </b>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',
                    $payment->qrCodeData->expirationDate)->format('d/m/Y 23:59:59')}}</p>

                    <a href="{{route('invoice.index')}}" class="btn btn-default mt-5" >Ir para Página Inicial</a>
                </div>
            @else
                <p>
                    {{
                        $payment->billingType === 'CREDIT_CARD'
                        ? 'Seu pagamento foi finalizado com sucesso!'
                        : 'Acesse o seu boleto através do botão abaixo e pague agora mesmo!'
                    }}
                </p>
                <div class="d-flex justify-content-center gap-2 mt-5">
                    <a href="{{route('invoice.index')}}" class="btn btn-default" >Ir para Página Inicial</a>
                    <a href="{{$payment->invoiceUrl}}" class="btn btn-custom" target="_blank">
                        {{$payment->billingType === 'CREDIT_CARD' ? 'Acessar Fatura' : 'Visualizar Boleto'}}
                    </a>
                </div>
            @endif

        </div>
    </div>
@endsection
