<?php
namespace mecontrola\entities\inscricaoestadual;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe utilizada para fazer a formatação e validação dos dados de inscrição estadual do estado do Maranhão.
 *
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities\inscricaoestadual
 * @since 1.0.20150518
 * @link http://www.sintegra.gov.br/Cad_Estados/cad_AC.html
 * @link http://sistemas.sefaz.ma.gov.br/sintegra/jsp/consultaSintegra/consultaSintegraFiltro.jsf
 * @link http://www.jusbrasil.com.br/diarios/71517594/djma-09-06-2014-pg-427
 */
class Maranhao extends Formatter implements Validator
{
	protected $pattern = '/^12\.[0-9]{6}-[0-9]$/';
	
	/**
	 * Faz a validação da inscrição estadual informada utilizando a rotina de validação do estado do Maranhão.
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
		
		return $d === intval(substr($value, strlen($value) - 1, 1));
	}

	/**
	 * Retorna a máscara de formatação da inscrição estadual do estado do Maranhão.
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
	 * Gera um número de inscrição estadual do Maranhão formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
	 * 
	 * @access public
	 * @param Boolean $formatar Define se o valor retornado será ou não formatado.
	 * @return String A inscrição estadual gerada.
	 */
	public function generate($formatar = TRUE)
	{
		$n = [
			1, 2, 
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
		
		$d = 0;
		for($i = 0, $l = count($weight); $i < $l; $i++)
			$d += $value[$i] * $weight[$i];
		
		return ($d = $d % 11) > 1 ? 11 - $d : 0;
	}
}