<?php
namespace mecontrola\entities;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe encarregada de fazer operações referentes ao Renavam (Registro Nacional de Veículos Automotores), entre essas operações estão a geração e validação. 
 *
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities
 * @since 1.0.20150518
 */
class Renavam extends Formatter implements Validator
{
	protected $pattern = '/[0-9]{4}\.[0-9]{6}-[0-9]/';
	
	/**
	 * Valida o Renavam informado.
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
		
		$d = 0;
		$aux = strrev(substr($value, 0, strlen($value) - 1));
		for($i = 0; $i < 8; $i++)
			$d += intval($aux[$i]) * (2 + $i);
		
		$d += intval($aux[strlen($aux) - 2]) * 2;
		$d += intval($aux[strlen($aux) - 1]) * 3;
		
		$d = (($d = 11 - ($d % 11)) >= 10 ? 0 : $d);
		
		return $d === intval($value[strlen($value) - 1]);
	}
	
	/**
	 * Retorna a máscara de formatação do Renavam.
	 * 
	 * @access public
	 * @return String Retorna a máscara.
	 * @see \mecontrola\base\Formatter::getMask()
	 */
	public function getMask()
	{
		return '9999.999999-9';
	}
	
	/**
	 * Gera um número de Renavam formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
	 *
	 * @access public
	 * @param Boolean $formatar Define se o valor retornado será ou não formatado.
	 * @return String
	 */
	public function generate($formatar = TRUE)
	{
		$n = [
			rand(0, 9), rand(0, 9), rand(0, 9),
			rand(0, 9), rand(0, 9), rand(0, 9),
			rand(0, 9), rand(0, 9), 0, 0 
		];
		
		$d = 0;
		for($i = 0; $i < 8; $i++)
			$d += $n[$i] * (2 + $i);
		
		$n = array_reverse($n);
		
		$n[] = (($d = 11 - ($d % 11)) >= 10 ? 0 : $d);
	
		return ($formatar ? $this->format(implode('', $n)) : implode('', $n));
	}
}