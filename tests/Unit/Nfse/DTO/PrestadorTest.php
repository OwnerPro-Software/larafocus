<?php

use Larafocus\Nfse\DTO\Prestador;
use Larafocus\Shared\InvalidDtoException;

covers(Prestador::class);

test('constructs with required fields', function () {
    $p = new Prestador(cnpj: '12345678000195', inscricao_municipal: '12345');

    expect($p->cnpj)->toBe('12345678000195')
        ->and($p->inscricao_municipal)->toBe('12345')
        ->and($p->codigo_municipio)->toBeNull();
});

test('constructs with all fields', function () {
    $p = new Prestador(cnpj: '12345678000195', inscricao_municipal: '12345', codigo_municipio: '3550308');

    expect($p->codigo_municipio)->toBe('3550308');
});

test('toArray omits null codigo_municipio', function () {
    $p = new Prestador(cnpj: '12345678000195', inscricao_municipal: '12345');

    expect($p->toArray())->toBe([
        'cnpj' => '12345678000195',
        'inscricao_municipal' => '12345',
    ]);
});

test('toArray includes codigo_municipio when set', function () {
    $p = new Prestador(cnpj: '12345678000195', inscricao_municipal: '12345', codigo_municipio: '3550308');

    expect($p->toArray())->toHaveKey('codigo_municipio', '3550308');
});

test('fromArray creates instance', function () {
    $p = Prestador::fromArray([
        'cnpj' => '12345678000195',
        'inscricao_municipal' => '12345',
        'codigo_municipio' => '3550308',
    ]);

    expect($p->cnpj)->toBe('12345678000195')
        ->and($p->codigo_municipio)->toBe('3550308');
});

test('fromArray handles missing optional fields', function () {
    $p = Prestador::fromArray([
        'cnpj' => '12345678000195',
        'inscricao_municipal' => '12345',
    ]);

    expect($p->codigo_municipio)->toBeNull();
});

test('accepts an alphanumeric cnpj and sends it unchanged', function () {
    $p = new Prestador(cnpj: '12ABC34501DE35', inscricao_municipal: '12345');

    expect($p->toArray())->toHaveKey('cnpj', '12ABC34501DE35');
});

test('rejects a malformed cnpj', function (string $cnpj) {
    new Prestador(cnpj: $cnpj, inscricao_municipal: '12345');
})->with([
    'shorter than 14 positions' => '12ABC34501DE3',
    'longer than 14 positions' => '12ABC34501DE350',
    'letter in the first check digit' => '12ABC34501DEA5',
    'letter in the second check digit' => '12ABC34501DE3A',
    'lowercase letters' => '12abc34501de35',
    'masked' => '12.ABC.345/01DE-35',
    'symbol among the first 12 positions' => '12ABC3450_DE35',
])->throws(InvalidDtoException::class);

test('validates codigo_municipio pattern', function () {
    new Prestador(cnpj: '12345678000195', inscricao_municipal: '12345', codigo_municipio: '123');
})->throws(InvalidDtoException::class);
