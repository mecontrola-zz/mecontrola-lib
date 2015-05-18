<?php
namespace mecontrola\entities\inscricaoestadual;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe utilizada para fazer a formatação e validação dos dados de inscrição estadual do estado de São Paulo.
 *
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities\inscricaoestadual
 * @since 1.0.20150518
 * @link http://www.sintegra.gov.br/Cad_Estados/cad_SP.html
 * @link http://pfeserv1.fazenda.sp.gov.br/sintegrapfe/consultaSintegraServlet
*/
class SaoPaulo extends Formatter implements Validator
{
	protected $pattern = '/^[0-9]{3}(\.[0-9]{3}){3}$/';
	
	/**
	 * Faz a validação da inscrição estadual informada utilizando a rotina de validação do estado de São Paulo.
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

		$d = self::numberCheck1($n);
		
		if($d !== intval(substr($value, strlen($value) - 4, 1)))
			return FALSE;
				
		$d = self::numberCheck2($n);
		
		return $d === intval(substr($value, strlen($value) - 1, 1));
	}

	/**
	 * Retorna a máscara de formatação da inscrição estadual do estado de São Paulo.
	 *
	 * @access public
	 * @return String Retorna a máscara.
	 * @see \mecontrola\base\Formatter::getMask()
	 */
	public function getMask()
	{
		return '999.999.999.999';
	}
	
	/**
	 * Gera um número de formatação estadual do São Paulo formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
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
			rand(0, 9), rand(0, 9), rand(0, 9),
			rand(0, 9), rand(0, 9)
		];
		
		$n[count($n)  - 3] = self::numberCheck1($n); 
		$n[] = self::numberCheck2($n);
		
		return ($formatar ? $this->format(implode('', $n)) : implode('', $n));
	}
	
	/**
	 * Realiza o cálculo do 1º digito verificador.
	 * 
	 * @access private
	 * @param String $value Valor que será usado para o cálculo.
	 * @return Integer Retorna o dígito verificador.
	 */
	private function numberCheck1(array $value)
	{
		$weight = [1, 3, 4, 5, 6, 7, 8, 10];
		
		$d = 0;
		foreach($weight as $i => $v)
			$d += $value[$i] * $v;
		
		return ($d = ($d % 11)) > 9 ? 0 : $d;
	}
	
	/**
	 * Realiza o cálculo do 2º digito verificador.
	 * 
	 * @access private
	 * @param String $value Valor que será usado para o cálculo.
	 * @return Integer Retorna o dígito verificador.
	 */
	private function numberCheck2(array $value)
	{
		$weight = [3, 2, 10, 9, 8, 7, 6, 5, 4, 3, 2];
		
		$d = 0;
		foreach($weight as $i => $v)
			$d += $value[$i] * $v;
		
		return ($d = ($d % 11)) > 9 ? 0 : $d;
	}
}