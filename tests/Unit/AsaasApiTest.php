<?php

use App\Classes\AsaasApi;
use Tests\TestCase;

class AsaasApiTest extends TestCase
{
    private $asaasApi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->asaasApi = new AsaasApi();
    }

    public function test_that_api_key_is_filled_when_created(): void
    {
        $expect = env('ASAAS_API_KEY');

        $this->assertEquals($expect, $this->asaasApi->getApiKey());
    }

    public function test_that_api_domain_is_filled_when_created(): void
    {
        $expect = env('ASAAS_API_DOMAIN');

        $this->assertEquals($expect, $this->asaasApi->getApiDomain());
    }
}
