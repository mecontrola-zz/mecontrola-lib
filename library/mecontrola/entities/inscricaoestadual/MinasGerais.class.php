<?php
namespace mecontrola\entities\inscricaoestadual;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe utilizada para fazer a formatação e validação dos dados de inscrição estadual do estado de Minas Gerais.
 *
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities\inscricaoestadual
 * @since 1.0.20150518
 * @link http://www.sintegra.gov.br/Cad_Estados/cad_MG.html
 * @link http://consultasintegra.fazenda.mg.gov.br/sintegra/
 * @link http://www.jusbrasil.com.br/diarios/54255302/doemg-executivo-31-03-2012-pg-22
 */
class MinasGerais extends Formatter implements Validator
{
	protected $pattern = '/^[0-9]{9}\.[0-9]{2}-[0-9]{2}$/';
	
	/**
	 * Faz a validação da inscrição estadual informada utilizando a rotina de validação do estado de Minas Gerais.
	 *
	 * @access public
	 * @param String $value O valor que deve ser verificado.
	 * @return Boolean Retorna <code>TRUE</code> caso sejá um valor válido ou <code>FALSE</code> caso não seja.
	 * @see \mecontrola\base\Validator::isValid()
	 */
	public function isValid($value)
	{
		$value = preg_replace('/[^0-9]/', '', $value);
		
		if(!$this->canBeFormatted($value))
			return FALSE;
		
		$n = [];
		for($i = 0, $l = strlen($value); $i < $l; $i++)
			$n[] = intval($value[$i]);
		
		$d = self::numberCheck($n);
		
		return $d === intval(substr($value, strlen($value) - 2, 2));
	}

	/**
	 * Retorna a máscara de formatação da inscrição estadual do estado de Minas Gerais.
	 *
	 * @access public
	 * @return String Retorna a máscara.
	 * @see \mecontrola\base\Formatter::getMask()
	 */
	public function getMask()
	{
		return '999999999.99-99';
	}
	
	/**
	 * Gera um número de inscrição estadual de Minas Gerais formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
	 * 
	 * @access public
	 * @param Boolean $formatar Define se o valor retornado será ou não formatado.
	 * @return String A inscrição estadual gerada.
	 */
	public function generate($formatar = TRUE)
	{
		$n = [ 
			rand(0, 9), rand(0, 9), rand(0, 9), 
			rand(0, 9), rand(0, 9), rand(0, 9),
			rand(0, 9), rand(0, 9), rand(0, 9),
			0, 0, 0, 0
		];
		
		$d = self::numberCheck($n);
		
		$n[count($n)  - 2] = $d > 9 ? (($d - ($d % 10)) / 10) : 0; 
		$n[count($n)  - 1] = $d % 10;
		
		return ($formatar ? $this->format(implode('', $n)) : implode('', $n));
	}
	
	/**
	 * Realiza o cálculo do digito verificador.
	 * 
	 * @access private
	 * @param String $value Valor que será usado para o cálculo.
	 * @return Integer Retorna o dígito verificador.
	 */
	private function numberCheck(array $value)
	{
		$weight = [11, 10, 9, 8, 7, 6, 5, 4, 3, 2];
		
		$tmp = array_merge(array_slice($value, 0, 3), [0], array_slice($value, 3, count($value) - 5));
		
		$d = 0;
		for($i = 0, $l = count($tmp); $i < $l; $i++)
			$d += (($j = ($tmp[$i] * ($i % 2 + 1))) > 9 ? ((($j - ($j % 10)) / 10) + ($j % 10)) : $j);
		
		$d1 = (($d = $d % 10) === 0 ? 0 : 10 - $d);
		
		$value[count($value)  - 2] = $d1;
		
		$d = 0;
		for($i = 0, $l = count($value) - 1; $i < $l; $i++)
			$d += $value[$i] * $weight[$i + ($i < 2 ? 8 : - 2)];
		
		$d = ($d = 11 - ($d % 11)) > 9 ? 0 : $d;
		
		return $d1 * 10 + $d;
	}
}