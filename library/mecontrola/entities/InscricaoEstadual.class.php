<?php
namespace mecontrola\entities;

use mecontrola\entities\inscricaoestadual\Acre as Acre;
use mecontrola\entities\inscricaoestadual\Alagoas as Alagoas;
use mecontrola\entities\inscricaoestadual\Amazonas as Amazonas;
use mecontrola\entities\inscricaoestadual\Amapa as Amapa;
use mecontrola\entities\inscricaoestadual\Bahia as Bahia;
use mecontrola\entities\inscricaoestadual\Ceara as Ceara;
use mecontrola\entities\inscricaoestadual\DistritoFederal as DistritoFederal;
use mecontrola\entities\inscricaoestadual\EspiritoSanto as EspiritoSanto;
use mecontrola\entities\inscricaoestadual\Goias as Goias;
use mecontrola\entities\inscricaoestadual\Maranhao as Maranhao;
use mecontrola\entities\inscricaoestadual\MinasGerais as MinasGerais;
use mecontrola\entities\inscricaoestadual\MatoGrossoDoSul as MatoGrossoDoSul;
use mecontrola\entities\inscricaoestadual\MatoGrosso as MatoGrosso;
use mecontrola\entities\inscricaoestadual\Para as Para;
use mecontrola\entities\inscricaoestadual\Pernambuco as Pernambuco;
use mecontrola\entities\inscricaoestadual\Piaui as Piaui;
use mecontrola\entities\inscricaoestadual\Parana as Parana;
use mecontrola\entities\inscricaoestadual\Paraiba as Paraiba;
use mecontrola\entities\inscricaoestadual\RioDeJaneiro as RioDeJaneiro;
use mecontrola\entities\inscricaoestadual\RioGrandeDoNorte as RioGrandeDoNorte;
use mecontrola\entities\inscricaoestadual\Rondonia as Rondonia;
use mecontrola\entities\inscricaoestadual\Roraima as Roraima;
use mecontrola\entities\inscricaoestadual\RioGrandeDoSul as RioGrandeDoSul;
use mecontrola\entities\inscricaoestadual\SantaCatarina as SantaCatarina;
use mecontrola\entities\inscricaoestadual\Sergipe as Sergipe;
use mecontrola\entities\inscricaoestadual\SaoPaulo as SaoPaulo;
use mecontrola\entities\inscricaoestadual\Tocantins as Tocantins;

/**
 * Classe utilizada para fazer a formatação e validação dos dados das inscrições estaduais.
 *
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities
 * @since 1.0.20150518
 * @link http://www.sintegra.gov.br/
 */
class InscricaoEstadual
{
	/**
	 * Faz a validação da incrição estadual informada utilizando a rotina de validação de acordo com o estado informado.
	 * Caso a estado informado seja inexistente o retorno será <code>FALSE</code>.
	 *
	 * @access public
	 * @param String $value O valor que deve ser verificado.
	 * @param String $estado Sigla do estado.
	 * @return Boolean Retorna <code>TRUE</code> caso sejá um valor válido ou <code>FALSE</code> caso não seja.
	 */
	public function isValid($value, $estado)
	{
		switch($estado)
		{
			case 'AC':
				$obj = new Acre();
				return $obj->isValid($value);
			case 'AL':
				$obj = new Alagoas();
				return $obj->isValid($value);
			case 'AM':
				$obj = new Amazonas();
				return $obj->isValid($value);
			case 'AP':
				$obj = new Amapa();
				return $obj->isValid($value);
			case 'BA':
				$obj = new Bahia();
				return $obj->isValid($value);
			case 'CE':
				$obj = new Ceara();
				return $obj->isValid($value);
			case 'DF':
				$obj = new DistritoFederal();
				return $obj->isValid($value);
			case 'ES':
				$obj = new EspiritoSanto();
				return $obj->isValid($value);
			case 'GO':
				$obj = new Goias();
				return $obj->isValid($value);
			case 'MA':
				$obj = new Maranhao();
				return $obj->isValid($value);
			case 'MG':
				$obj = new MinasGerais();
				return $obj->isValid($value);
			case 'MS':
				$obj = new MatoGrossoDoSul();
				return $obj->isValid($value);
			case 'MT':
				$obj = new MatoGrosso();
				return $obj->isValid($value);
			case 'PA':
				$obj = new Para();
				return $obj->isValid($value);
			case 'PB':
				$obj = new Paraiba();
				return $obj->isValid($value);
			case 'PE':
				$obj = new Pernambuco();
				return $obj->isValid($value);
			case 'PI':
				$obj = new Piaui();
				return $obj->isValid($value);
			case 'PR':
				$obj = new Parana();
				return $obj->isValid($value);
			case 'RJ':
				$obj = new RioDeJaneiro();
				return $obj->isValid($value);
			case 'RN':
				$obj = new RioGrandeDoNorte();
				return $obj->isValid($value);
			case 'RO':
				$obj = new Rondonia();
				return $obj->isValid($value);
			case 'RR':
				$obj = new Roraima();
				return $obj->isValid($value);
			case 'RS':
				$obj = new RioGrandeDoSul();
				return $obj->isValid($value);
			case 'SC':
				$obj = new SantaCatarina();
				return $obj->isValid($value);
			case 'SE':
				$obj = new Sergipe();
				return $obj->isValid($value);
			case 'SP':
				$obj = new SaoPaulo();
				return $obj->isValid($value);
			case 'TO':
				$obj = new Tocantins();
				return $obj->isValid($value);
			default :
				return FALSE;
		}
	}

	/**
	 * Retorna a máscara de formatação da inscrição estadual de acordo com o estado informado.
	 * Caso a estado informado seja inexistente o retorno uma string vazia. 
	 * 
	 * @access public
	 * @param String $estado Sigla do estado.
	 * @return String Retorna a máscara.
	 */
	public function getMask($estado)
	{
		switch($estado)
		{
			case 'AC':
				$obj = new Acre();
				return $obj->getMask();
			case 'AL':
				$obj = new  Alagoas();
				return $obj->getMask();
			case 'AM':
				$obj = new Amazonas();
				return $obj->getMask();
			case 'AP':
				$obj = new Amapa();
				return $obj->getMask();
			case 'BA':
				$obj = new Bahia();
				return $obj->getMask();
			case 'CE':
				$obj = new Ceara();
				return $obj->getMask();
			case 'DF':
				$obj = new DistritoFederal();
				return $obj->getMask();
			case 'ES':
				$obj = new EspiritoSanto();
				return $obj->getMask();
			case 'GO':
				$obj = new Goias();
				return $obj->getMask();
			case 'MA':
				$obj = new Maranhao();
				return $obj->getMask();
			case 'MG':
				$obj = new MinasGerais();
				return $obj->getMask();
			case 'MS':
				$obj = new MatoGrossoDoSul();
				return $obj->getMask();
			case 'MT':
				$obj = new MatoGrosso();
				return $obj->getMask();
			case 'PA':
				$obj = new Para();
				return $obj->getMask();
			case 'PB':
				$obj = new Paraiba();
				return $obj->getMask();
			case 'PE':
				$obj = new Pernambuco();
				return $obj->getMask();
			case 'PI':
				$obj = new Piaui();
				return $obj->getMask();
			case 'PR':
				$obj = new Parana();
				return $obj->getMask();
			case 'RJ':
				$obj = new RioDeJaneiro();
				return $obj->getMask();
			case 'RN':
				$obj = new RioGrandeDoNorte();
				return $obj->getMask();
			case 'RO':
				$obj = new Rondonia();
				return $obj->getMask();
			case 'RR':
				$obj = new Roraima();
				return $obj->getMask();
			case 'RS':
				$obj = new RioGrandeDoSul();
				return $obj->getMask();
			case 'SC':
				$obj = new SantaCatarina();
				return $obj->getMask();
			case 'SE':
				$obj = new Sergipe();
				return $obj->getMask();
			case 'SP':
				$obj = new SaoPaulo();
				return $obj->getMask();
			case 'TO':
				$obj = new Tocantins();
				return $obj->getMask();
			default :
				return '';
		}
	}
	
	/**
	 * Gera um número de inscrição estadual, retornando um array contendo a sigla do estado gerador e a inscrição estadual gerada.
	 * Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
	 * Se o <code>$estado</code> não for informado, será gerada uma inscrição estadual de forma aleatória.
	 * Caso a estado informado seja inexistente o retorno será um array vazio.
	 * 
	 * @access public
	 * @param String $formatar Define se o valor retornado será ou não formatado.
	 * @param String $estado Sigla do estado.
	 * @return Array
	 */
	public function generate($formatar = TRUE, $estado = NULL)
	{
		$arr = [
			'AC', 'AL', 'AM', 'AP', 'BA', 'CE', 'DF', 'ES', 'GO',
			'MA', 'MG', 'MS', 'MT', 'PA', 'PB', 'PE', 'PI', 'PR',
			'RJ', 'RN', 'RO', 'RR', 'RS', 'SC', 'SE', 'SP', 'TO'
		];
		
		$estado = is_null($estado) ? $arr[rand(0, count($arr) - 1)] : $estado;
		
		switch($estado)
		{
			case 'AC':
				$obj = new Acre();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'AL':
				$obj = new Alagoas();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'AM':
				$obj = new Amazonas();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'AP':
				$obj = new Amapa();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'BA':
				$obj = new Bahia();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'CE':
				$obj = new Ceara();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'DF':
				$obj = new DistritoFederal();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'ES':
				$obj = new EspiritoSanto();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'GO':
				$obj = new Goias();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'MA':
				$obj = new Maranhao();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'MG':
				$obj = new MinasGerais();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'MS':
				$obj = new MatoGrossoDoSul();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'MT':
				$obj = new MatoGrosso();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'PA':
				$obj = new Para();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'PB':
				$obj = new Paraiba();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'PE':
				$obj = new Pernambuco();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'PI':
				$obj = new Piaui();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'PR':
				$obj = new Parana();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'RJ':
				$obj = new RioDeJaneiro();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'RN':
				$obj = new RioGrandeDoNorte();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'RO':
				$obj = new Rondonia();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'RR':
				$obj = new Roraima();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'RS':
				$obj = new RioGrandeDoSul();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'SC':
				$obj = new SantaCatarina();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'SE':
				$obj = new Sergipe();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'SP':
				$obj = new SaoPaulo();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			case 'TO':
				$obj = new Tocantins();
				return [
					'state' => $estado,
					'value' => $obj->generate($formatar)
				];
			default :
				return [];
		}
	}
}