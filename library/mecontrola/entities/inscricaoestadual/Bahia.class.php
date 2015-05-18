<?php
namespace mecontrola\entities\inscricaoestadual;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe utilizada para fazer a formatação e validação dos dados de inscrição estadual do estado da Bahia.
 * 
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities\inscricaoestadual
 * @since 1.0.20150518
 * @link http://www.sefaz.ba.gov.br/contribuinte/informacoes_fiscais/doc_fiscal/calculodv.htm
 * @link http://www.sefaz.ba.gov.br/scripts/cadastro/cadastroBa/consultaBa.asp
 */
class Bahia extends Formatter implements Validator
{
	protected $pattern = '/^[0-9]{3}(\.[0-9]{3}){2}$/';
	
	/**
	 * Faz a validação da inscrição estadual informada utilizando a rotina de validação do estado da Bahia.
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
	 * Retorna a máscara de formatação da inscrição estadual do estado da Bahia.
	 * 
	 * @access public
	 * @return String Retorna a máscara.
	 * @see \mecontrola\base\Formatter::getMask()
	 */
	public function getMask()
	{
		return '999.999.999';
	}
	
	/**
	 * Gera um número de inscrição estadual da Bahia formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
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
			rand(0, 9), 0, 0
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
		$weight = [9, 8, 7, 6, 5, 4, 3, 2];
		$mod = in_array($value[1], [0, 1, 2, 3, 4, 5, 8]) ? 10 : 11;
		
		$d = 0;
		for($i = 0; $i < 7; $i++)
			$d += $value[$i] * $weight[$i + 1];
		
		$value[7] = $d1 = (($mod === 10 && ($d % $mod) === 0) || ($mod === 11 && (($d % $mod) === 0 || ($d % $mod) === 1))) ? 0 : $mod - ($d % $mod);
		
		$d = 0;
		for($i = 0; $i < 8; $i++)
			$d += $value[$i] * $weight[$i];
		
		$d1 += (($mod === 10 && ($d % $mod) === 0) || ($mod === 11 && (($d % $mod) === 0 || ($d % $mod) === 1))) ? 0 : ($mod - ($d % $mod)) * 10;
		
		return $d1;
	}
}