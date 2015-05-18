<?php
namespace mecontrola\entities;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe encarregada de fazer operações referentes ao CEI (Cadastro Específico do INSS), entre essas operações estão a geração e validação.
 * <strong>Definição:</strong> 
 * <blockquote cite="http://www.conectividadeicp.org/pis-nis-e-cei-o-que-sao-e-qual-a-sua-importancia/">
 * O CEI se destina para empresas ou equiparados à empresa que não tenham a obrigação de se inscrever no CNPJ, obra de construção civil, produtor rural contribuinte individual, segurado especial, consórcio de produtores rurais, titular de cartório, adquirente de produção rural e empregador doméstico.
 * </blockquote>
 * 
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities
 * @since 1.0.20150518
 */
class CEI extends Formatter implements Validator
{
	protected $pattern = '/[0-9]{2}\.[0-9]{3}\.[0-9]{5}\/[0-9]{2}/';

	/**
	 * Valida o CEI informado.
	 *
	 * @access public
	 * @param String $value O valor que deve ser verificado.
	 * @return Boolean Retorna <code>TRUE</code> caso sejá um valor válido ou <code>FALSE</code> caso não seja.
	 * @see \mecontrola\base\Validator::isValid()
	 */
	public function isValid($value)
	{
		$value = preg_replace('/[^0-9]/', '', $value);
		$weight = [7, 4, 1, 8, 5, 2, 1, 6, 3, 7, 4];
		
		if(!$this->canBeFormatted($value))
			return FALSE;
		
		$sum = 0;
		for($i = 0, $l = count($weight); $i < $l; $i++)
			$sum += intval($value[$i]) * $weight[$i];
		
		if($sum > 100)
			$sum %= 100; // Pega a dezena e a unidade da soma;
		
		$sum = (($sum - ($sum % 10)) / 10 + ($sum % 10)); // Soma a dezena e a unidade
		$sum = 10 - ($sum % 10); // Subtrair de 10 a unidade do número calculado anteriormente
		
		return intval($value[strlen($value) - 1]) === ($sum > 9 ? 0 : $sum);
	}
	
	/**
	 * Retorna a máscara de formatação do CEI.
	 * 
	 * @access public
	 * @return String Retorna a máscara.
	 * @see \mecontrola\base\Formatter::getMask()
	 */
	public function getMask()
	{
		return '99.999.99999/99';
	}
	
	/**
	 * Gera um número de CEI formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
	 * 
	 * @access public
	 * @param Boolean $formatar Define se o valor retornado será ou não formatado.
	 * @return String
	 */
	public function generate($formatar = TRUE)
	{
		$weight = [7, 4, 1, 8, 5, 2, 1, 6, 3, 7, 4];
		$n = [
				rand(0, 9), rand(0, 9), rand(0, 9),
				rand(0, 9), rand(0, 9), rand(0, 9),
				rand(0, 9), rand(0, 9), rand(0, 9),
				rand(0, 9), rand(0, 9)
		];
		
		$d = 0;
		for($i = 0, $l = count($weight); $i < $l; $i++)
			$d += $n[$i] * $weight[$i];
		
		if($d > 100)
			$d %= 100; // Pega a dezena e a unidade da soma;
		
		$d = (($d - ($d % 10)) / 10 + ($d % 10)); // Soma a dezena e a unidade
		$d = 10 - ($d % 10); // Subtrair de 10 a unidade do número calculado anteriormente
		
		$n[] = ($d > 9) ? 0 : $d;
	
		return ($formatar ? $this->format(implode('', $n)) : implode('', $n));
	}
}