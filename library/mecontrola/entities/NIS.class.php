<?php
namespace mecontrola\entities;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe encarregada de fazer operações referentes ao NIS (Número de Integração Social), entre essas operações estão a geração e validação.
 * <strong>Definição:</strong> 
 * <blockquote cite="http://www.conectividadeicp.org/pis-nis-e-cei-o-que-sao-e-qual-a-sua-importancia/">
 * Esse número pode ser composto por um PIS (Programa de Integração Social) ou um PASEP (Programa de Formação do Patrimônio do Servidor) ferramentas criadas pelo governo para promover a integração do trabalhador e que garantem o Abono Salarial e o Seguro-Desemprego. O PIS se destina a quem atua no setor privado, já o PASEP é para os funcionários e servidores públicos.
 * </blockquote>
 * 
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities
 * @since 1.0.20150518
 */
class NIS extends Formatter implements Validator
{
	protected $pattern = '/[0-9]{3}\.[0-9]{5}\.[0-9]{2}-[0-9]/';
	
	/**
	 * Valida o PIS informado.
	 * 
	 * @access public
	 * @param String $value
	 * @return Boolean
	 * @see \mecontrola\base\Validator::isValid()
	 */
	public function isValid($value)
	{
		$value = preg_replace('/[^0-9]/', '', $value);
		$weight = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
		
		if(!$this->canBeFormatted($value))
			return FALSE;
		
		$digit = 0;
		foreach($weight as $i => $v)
			$digit += intval($value[$i]) * $v;
		
		$digit = (11 - ($digit % 11));
		
		return (($digit >= 10) ? 0 : $digit) === intval($value[10]);
	}
	
	/**
	 * Retorna a máscara de formatação do PIS.
	 *
	 * @access public
	 * @return String Retorna a máscara.
	 */
	public function getMask()
	{
		return '999.99999.99-9';
	}
	
	/**
	 * Gera um número de PIS formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
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
				rand(0, 9), rand(0, 9), rand(0, 9),
				rand(0, 9)
		];
		
		$weight = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
		
		$d = 0;
		foreach($n as $i => $num)
			$d += $num * $weight[$i];
		
		$d = (11 - ($d % 11));
		
		$n[] = ($d >= 10) ? 0 : $d;
		
		return ($formatar ? $this->format(implode('', $n)) : implode('', $n));
	}
}