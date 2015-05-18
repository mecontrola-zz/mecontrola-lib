<?php
include 'library/autoloader.php';

use mecontrola\entities\CPF as CPF;
use mecontrola\entities\CNPJ as CNPJ;
use mecontrola\entities\inscricaoestadual\Bahia as Bahia;

$obj = new CPF();
echo('CPF: 681.113.352-02 => ' . ($obj->isValid('681.113.352-02') ? 'Sim' : 'Não') . '<br>');

$obj = new CNPJ();
echo('CNPJ: 86.507.991/0001-18 => ' . ($obj->isValid('86.507.991/0001-18') ? 'Sim' : 'Não') . '<br>');

$obj = new Bahia();
echo('IE BA: 122.096.866 => ' . ($obj->isValid('122.096.866') ? 'Sim' : 'Não') . '<br>');