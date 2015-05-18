<?php
namespace mecontrola\entities\inscricaoestadual;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe utilizada para fazer a formatação e validação dos dados de inscrição estadual do estado do Amapá.
 * 
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities\inscricaoestadual
 * @since 1.0.20150518
 * @link http://www.sintegra.gov.br/Cad_Estados/cad_AP.html
 * @link http://www.sintegra.ap.gov.br/Sintegra/
 */
class Amapa extends Formatter implements Validator
{
	protected $pattern = '/03\.[0-9]{6}-[0-9]/';
	
	/**
	 * Faz a validação da inscrição estadual informada utilizando a rotina de validação do estado do Amapá.
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
		
		return $d === intval(substr($value, 8, 1));
	}
	
	/**
	 * Retorna a máscara de formatação da inscrição estadual do estado do Amapá.
	 * 
	 * @access public
	 * @return String Retorna a máscara.
	 * @see \mecontrola\base\Formatter::getMask()
	 */
	public function getMask()
	{
		return '99.999999-9';
	}
	
	/**
	 * Gera um número de inscrição estadual do Amapá formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
	 * 
	 * @access public
	 * @param Boolean $formatar Define se o valor retornado será ou não formatado.
	 * @return String A inscrição estadual gerada.
	 */
	public function generate($formatar = TRUE)
	{
		$n = [
				0, 3, rand(0, 9),
				rand(0, 9), rand(0, 9), rand(0, 9),
				rand(0, 9), rand(0, 9)
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
		$p = $d = 0;
		
		$aux = intval(implode('', array_slice($value, 0, count($value) - 2)));
		if(3000001 <= $aux && 3017000 >= $aux)
		{
			$p = 5;
		} elseif(3017001 <= $aux && 3019022 >= $aux)
		{
			$p = 9;
			$d = 1;
		}
		
		foreach($weight as $i => $v)
			$p += ($value[$i] * $v);
		
		$rest = 11 - ($p % 11);
		return ($rest === 10 ? 0 : ($rest === 11 ? $d : $rest));
	}
}