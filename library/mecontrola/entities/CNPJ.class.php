<?php
namespace mecontrola\entities;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe encarregada de fazer operações referentes ao CNPJ (Cadastro Nacional da Pessoa Jurídica), entre essas operações estão a geração e validação. 
 *
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities
 * @since 1.0.20150518
 */
class CNPJ extends Formatter implements Validator
{
	protected $pattern = '/[0-9]{2}\.[0-9]{3}\.[0-9]{3}\/[0-9]{4}-[0-9]{2}/';

	/**
	 * Valida o CNPJ informado.
	 *
	 * @access public
	 * @param String $value O valor que deve ser verificado.
	 * @return Boolean Retorna <code>TRUE</code> caso sejá um valor válido ou <code>FALSE</code> caso não seja.
	 * @see \mecontrola\base\Validator::isValid()
	 */
	public function isValid($value)
	{
		$value = preg_replace('/[^0-9]/', '', $value);
		
		if(!$this->canBeFormatted($value) || in_array($value, ["00000000000000", "11111111111111", "22222222222222", "33333333333333",
				"44444444444444", "55555555555555", "66666666666666", "77777777777777", "88888888888888", "99999999999999"]))
			return FALSE;
		
		$d = 0;
		for($i = 4; $i < 12; $i++)
			$d += $value[$i] * (13 - $i);
		
		for($i = 0; $i < 4; $i++)
			$d += $value[$i] * (5 - $i);
		
		$d = 11 - ($d % 11);
		
		$d = ($d >= 10) ? 0 : $d;
		if($d !== intval($value[12]))
			return FALSE;
		
		$d = 0;
		for($i = 5; $i < 13; $i++)
			$d += $d += $value[$i] * (14 - $i);
		
		for($i = 0; $i < 5; $i++)
			$d += $d += $value[$i] * (6 - $i);
		
		$d = 11 - ($d % 11);
		
		$d = ($d >= 10) ? 0 : $d;
		if($d !== intval($value[13]))
			return FALSE;
		
		return TRUE;
	}
	
	/**
	 * Retorna a máscara de formatação do CNPJ.
	 * 
	 * @access public
	 * @return String Retorna a máscara.
	 * @see \mecontrola\base\Formatter::getMask()
	 */
	public function getMask()
	{
		return '99.999.999/9999-99';
	}
	
	/**
	 * Gera um número de CNPJ formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
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
			rand(0, 9), rand(0, 9), 0, 0, 0, 1
		];
		
		$d = 0;
		for($i = 4; $i < 12; $i++)
			$d += $n[$i] * (13 - $i);
		
		for($i = 0; $i < 4; $i++)
			$d += $n[$i] * (5 - $i);
		
		$d = 11 - ($d % 11);
		
		$n[] = ($d >= 10) ? 0 : $d;
		
		$d = 0;
		for($i = 5; $i < 13; $i++)
			$d += $d += $n[$i] * (14 - $i);
		
		for($i = 0; $i < 5; $i++)
			$d += $d += $n[$i] * (6 - $i);
		
		$d = 11 - ($d % 11);
		
		$n[] = ($d >= 10) ? 0 : $d;
	
		return ($formatar ? $this->format(implode('', $n)) : implode('', $n));
	}
}