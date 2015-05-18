<?php
namespace mecontrola\entities\inscricaoestadual;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe utilizada para fazer a formatação e validação dos dados de inscrição estadual do estado de Roraima.
 *
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities\inscricaoestadual
 * @since 1.0.20150518
 * @link http://www.sintegra.gov.br/Cad_Estados/cad_RR.html
 * @link https://www.sefaz.rr.gov.br/sintegra/servlet/hwsintco
 * @link http://www.jusbrasil.com.br/diarios/5773026/pg-16-diario-oficial-do-estado-de-roraima-doerr-de-30-05-2008
 */
class Roraima extends Formatter implements Validator
{
	protected $pattern = '/^24\.[0-9]{6}-[0-9]$/';
	
	/**
	 * Faz a validação da inscrição estadual informada utilizando a rotina de validação do estado de Roraima.
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
	 * Retorna a máscara de formatação da inscrição estadual do estado de Roraima.
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
	 * Gera um número de inscrição estadual do Roraima formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
	 * 
	 * @access public
	 * @param Boolean $formatar Define se o valor retornado será ou não formatado.
	 * @return String A inscrição estadual gerada.
	 */
	public function generate($formatar = TRUE)
	{
		$n = [
			2, 4, 
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
		$weight = [8, 7, 6, 5, 4, 3, 2, 1];
		
		$d = 0;
		foreach($weight as $i => $v)
			$d += $value[$i] * $v;
		
		return ($d = 9 - ($d % 9)) < 9 ? $d : 9 - $d;
	}
}