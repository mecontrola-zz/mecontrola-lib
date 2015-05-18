<?php
namespace mecontrola\entities;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe encarregada de fazer operações referentes ao CPF (Cadastro de Pessoa Física), entre essas operações estão a geração e validação. 
 *
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities
 * @since 1.0.20150518
 */
class CPF extends Formatter implements Validator
{
	protected $pattern = '/[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}/';
	
	/**
	 * Valida o CPF informado.
	 * 
	 * @access public
	 * @param String $value O valor que deve ser verificado.
	 * @return Boolean Retorna <code>TRUE</code> caso sejá um valor válido ou <code>FALSE</code> caso não seja.
	 * @see \mecontrola\base\Validator::isValid()
	 */
	public function isValid($value)
	{
		$value = preg_replace('/[^0-9]/', '', $value);
		
		if(!$this->canBeFormatted($value) || in_array($value, ["00000000000", "11111111111", "22222222222", "33333333333", 
				"44444444444", "55555555555", "66666666666", "77777777777", "88888888888", "99999999999"]))
			return FALSE;
		
		$add = 0;
		for($i = 0; $i < 9; $i++)
			$add += intval($value[$i]) * (10 - $i);
		
		$rev = 11 - ($add % 11);
		if($rev === 10 || $rev === 11)
			$rev = 0;
		
		if($rev !== intval($value[9]))
			return FALSE;
		
		$add = 0;
		for($i = 0; $i < 10; $i++)
			$add += intval($value[$i]) * (11 - $i);
		
		$rev = 11 - ($add % 11);
		if($rev === 10 || $rev === 11)
			$rev = 0;
		
		if($rev !== intval($value[10]))
			return FALSE;
		
		return TRUE;
	}
	
	/**
	 * Retorna a máscara de formatação do CPF.
	 * 
	 * @access public
	 * @return String Retorna a máscara.
	 * @see \mecontrola\base\Formatter::getMask()
	 */
	public function getMask()
	{
		return '999.999.999-99';
	}
	
	/**
	 * Gera um número de CPF formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
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
	
		$d = 0;
		foreach($n as $i => $num)
			$d += $num * (10 - $i);
	
		$d = 11 - ($d % 11);
	
		$n[] = ($d >= 10) ? 0 : $d;
	
		$d = 0;
		foreach($n as $i => $num)
			$d += $num * (11 - $i);
	
		$d = 11 - ($d % 11);
	
		$n[] = ($d >= 10) ? 0 : $d;
	
		return ($formatar ? $this->format(implode('', $n)) : implode('', $n));
	}
	
	/**
	 * Retorna o nome do estado ao qual pertence o documento. Caso não seja encontrado será retornado <code>NULL</code>. 
	 * 
	 * @access public
	 * @param String $cpf O número do documento.
	 * @return String Nome do estado gerador.
	 */
	public function getEstado($value)
	{
		$value = preg_replace('/[^0-9]/', '', $value);
		
		$uf = [
			'Rio Grande do Sul',
			'Distrito Federal, Goiás, Mato Grosso, Mato Grosso do Sul e Tocantins',
			'Amazonas, Pará, Roraima, Amapá, Acre e Rondônia',
			'Ceará, Maranhão e Piauí',
			'Paraíba, Pernambuco, Alagoas e Rio Grande do Norte',
			'Bahia e Sergipe',
			'Minas Gerais',
			'Rio de Janeiro e Espírito Santo',
			'São Paulo',
			'Paraná e Santa Catarina'
		];
		
		if($this->isValid($value))
			return $uf[intval($value[8])];
		
		return NULL;
	}
}