<?php
namespace mecontrola\entities;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe encarregada de fazer operações referentes à CNH (Carteira Nacional de Habilitação), entre essas operações estão a geração e validação.
 *
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities
 * @since 1.0.20150518
 */
class CNH extends Formatter implements Validator
{
	protected $pattern = '/[0-9]{9,11}/';

	/**
	 * Valida o CNH informado.
	 *
	 * @access public
	 * @param String $value O valor que deve ser verificado.
	 * @return Boolean Retorna <code>TRUE</code> caso sejá um valor válido ou <code>FALSE</code> caso não seja.
	 * @see \mecontrola\base\Validator::isValid()
	 */
	public function isValid($value)
	{
		if(strlen(trim($value)) < 11)
			$value = str_pad($value, 11, '0', STR_PAD_LEFT);
		
		$sum1 = $sum2 = 0;
		
		for($i = 0, $j = 9, $k = 1; $i < 9; $i++, $j--, $k++)
		{
			$val = intval($value[$i]);
            $sum1 += $val * $j;
            $sum2 += $val * $k;
		}
		
		$check1 = ($check1 = $sum1 % 11) > 9 ? 0 : $check1;
		
		$check2 = ($sum2 % 11) - (($sum1 % 11) > 9 ? 2 : 0);
		
		if($check2 < 0)
			$check2 += 11;
			
		if($check2 > 9)
			$check2 = 0;
		
		$digit = intval(substr($value, strlen($value) - 2, 2));
		$check = $check1 * 10 + $check2;
		
		return $digit === $check;
    }
	
	/**
	 * Retorna a máscara de formatação do CNH.
	 * 
	 * @access public
	 * @return String Retorna a máscara.
	 * @see \mecontrola\base\Formatter::getMask()
	 */
	public function getMask()
	{
		return '99999999999';
	}
	
	/**
	 * Gera um número de CNH formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
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
			rand(0, 9), rand(0, 9), rand(0, 9)
		];
		
		$sum1 = $sum2 = 0;
		
		for($i = 0, $j = 9, $k = 1; $i < 9; $i++, $j--, $k++)
		{
            $sum1 += $n[$i] * $j;
            $sum2 += $n[$i] * $k;
		}
		
		$check1 = ($check1 = $sum1 % 11) > 9 ? 0 : $check1;
		
		$check2 = ($sum2 % 11) - (($sum1 % 11) > 9 ? 2 : 0);
		
		if($check2 < 0)
			$check2 += 11;
			
		if($check2 > 9)
			$check2 = 0;
		
		$n[] = $check1 * 10 + $check2;
		
		return ($formatar ? $this->format(implode('', $n)) : implode('', $n));
	}
}