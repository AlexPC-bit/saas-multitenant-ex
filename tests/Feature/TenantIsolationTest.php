<?php

use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('usuário não consegue ver clientes de outra empresa', function () {
    $companyA = Company::factory()->create();
    $companyB = Company::factory()->create();

    $userA = User::factory()->create(['company_id' => $companyA->id]);
    $customerB = Customer::factory()->create(['company_id' => $companyB->id]);

    $response = $this->actingAs($userA, 'sanctum')
        ->getJson("/api/customers/{$customerB->id}");

    $response->assertStatus(404);
});

test('usuário só vê clientes da própria empresa na listagem', function () {
    $companyA = Company::factory()->create();
    $companyB = Company::factory()->create();

    $userA = User::factory()->create(['company_id' => $companyA->id]);
    Customer::factory()->count(3)->create(['company_id' => $companyA->id]);
    Customer::factory()->count(5)->create(['company_id' => $companyB->id]);

    $response = $this->actingAs($userA, 'sanctum')
        ->getJson('/api/customers');

    $response->assertJsonCount(3, 'data');
});