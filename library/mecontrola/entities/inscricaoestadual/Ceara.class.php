<?php
namespace mecontrola\entities\inscricaoestadual;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe utilizada para fazer a formatação e validação dos dados de inscrição estadual do estado do Ceará.
 * 
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities\inscricaoestadual
 * @since 1.0.20150518
 * @link http://www.sintegra.gov.br/Cad_Estados/cad_CE.html
 * @link http://www.sefaz.ce.gov.br/content/aplicacao/internet/servicos_online/sintegra/sintegra.asp?estado=CE
 * @link http://www.jusbrasil.com.br/diarios/5375080/pg-8-caderno-unico-diario-oficial-do-estado-do-ceara-doece-de-22-08-2002
 */
class Ceara extends Formatter implements Validator
{
	protected $pattern = '/^[0-9]{2}\.[0-9]{6}-[0-9]$/';
	
	/**
	 * Faz a validação da inscrição estadual informada utilizando a rotina de validação do estado do Ceará.
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
	 * Retorna a máscara de formatação da inscrição estadual do estado do Ceará.
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
	 * Gera um número de inscrição estadual do Ceará formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
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
		$weight = [
			9,8,7,6,5,4,3,2
		];
		
		$d = 0;
		foreach($weight as $i => $v)
			$d += ($value[$i] * $v);
		
		$d = 11 - ($d % 11);
		return $d === 10 || $d === 11 ? 0 : $d; 
	}
}