<?php
namespace mecontrola\entities\inscricaoestadual;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe utilizada para fazer a formatação e validação dos dados de inscrição estadual do estado do Góias.
 *
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities\inscricaoestadual
 * @since 1.0.20150518
 * @link http://www.sintegra.gov.br/Cad_Estados/cad_GO.html
 * @link http://www.sefaz.go.gov.br/ccs/default.asp
 */
class Goias extends Formatter implements Validator
{
	protected $pattern = '/^(10|11|15)(\.[0-9]{3}){2}-[0-9]$/';
	
	/**
	 * Faz a validação da inscrição estadual informada utilizando a rotina de validação do estado do Góias.
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
		
		return $d === intval(substr($value, strlen($value) - 1, 2));
	}

	/**
	 * Retorna a máscara de formatação da inscrição estadual do estado do Góias.
	 *
	 * @access public
	 * @return String Retorna a máscara.
	 * @see \mecontrola\base\Formatter::getMask()
	 */
	public function getMask()
	{
		return '99.999.999-9';
	}
	
	/**
	 * Gera um número de inscrição estadual do Góias formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
	 * 
	 * @access public
	 * @param Boolean $formatar Define se o valor retornado será ou não formatado.
	 * @return String A inscrição estadual gerada.
	 */
	public function generate($formatar = TRUE)
	{
		$tipos = [0, 1, 5];
		
		$n = [
			1, $tipos[rand(0, count($tipos) - 1)],
			rand(0, 9), rand(0, 9), rand(0, 9), 
			rand(0, 9), rand(0, 9), rand(0, 9)
		];
		
		$n[] = self::numberCheck($n);
		
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
		$weight = [9, 8, 7, 6, 5, 4, 3, 2];
		$isGenerate = count($weight) === count($value);
		$int = intval(implode('', array_slice($value, 0, count($value) - ($isGenerate ? 0 : 1))));
		
		if(11094402 === $int)
			return $isGenerate ? rand(0, 1) : (($d = $value[count($value) - 1]) > 1 ? 0 : $d);
		
		$d = 0;
		for($i = 0, $l = count($weight); $i < $l; $i++)
			$d += $value[$i] * $weight[$i];
		
		return ($d = $d % 11) > 1 ? 11 - $d : ($d === 0 ? 0 : (10103105 >= $int && $int <= 10119997 ? 1 : 0));
	}
}