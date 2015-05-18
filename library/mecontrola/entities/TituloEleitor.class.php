<?php
namespace mecontrola\entities;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe encarregada de fazer operações referentes ao Título de Eleitor do Brasil, entre essas operações estão a geração e validação. 
 *
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities
 * @since 1.0.20150518
 */
class TituloEleitor extends Formatter implements Validator
{
	protected $pattern = '/[0-9]{10}-[0-9]{2}/';
	
	/**
	 * Valida o Título de Eleitor informado.
	 * 
	 * @access public
	 * @param String $value
	 * @return Boolean
	 * @see \mecontrola\base\Validator::isValid()
	 */
	public function isValid($value)
	{
		$value = preg_replace('/[^0-9]/', '', $value);
		$weight = [2, 3, 4, 5, 6, 7, 8, 9];
		
		if(!$this->canBeFormatted($value))
			return FALSE;
		
		$d = 0;
		foreach($weight as $i => $v)
			$d += (intval(substr($value, $i, 1)) * $v);
		
		$d = ($d % 11);
		$d = $d === 10 || $d === 11 ? 0 : $d;
		
		if($d !== intval($value[10]))
			return FALSE;
		
		$d = 0;
		for($i = 5, $l = count($weight); $i < $l; $i++)
			$d += (intval(substr($value, 3 + $i, 1)) * $weight[$i]);
		
		$d = ($d % 11);
		$d = $d === 10 || $d === 11 ? 0 : $d;
		
		if($d !== intval($value[11]))
			return FALSE;
		
		return TRUE;
	}
	
	/**
	 * Retorna a máscara de formatação do Título de Eleitor.
	 * 
	 * @access public
	 * @return String Retorna a máscara.
	 */
	public function getMask()
	{
		return '9999999999-99';
	}
	
	/**
	 * Gera um número de Título de Eleitor formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
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
			rand(0, 9), rand(0, 9)
		];
		
		$st = '' . rand(1, 28);
		
		if($st < 10)
		{
			$n[] = 0;
			$n[] = $st;
		} else
		{
			$n[] = $st[0];
			$n[] = $st[1];
		}	
		
		$d = 0;
		for($i = 2, $l = 10; $i < $l; $i++)
			$d += (intval($n[$i - 2]) * $i);
		
		$d = ($d % 11);
		
		$n[] = ($d >= 10) ? 0 : $d;
	
		$d = 0;
		for($i = 7, $l = 10; $i < $l; $i++)
			$d += (intval($n[1 + $i]) * $i);
	
		$d = ($d % 11);
	
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
			'01' => 'SP', '02' => 'MG', '03' => 'RJ', '04' => 'RS', '05' => 'BA',
			'06' => 'PR', '07' => 'CE', '08' => 'PE', '09' => 'SC', '10' => 'GO',
			'11' => 'MA', '12' => 'PB', '13' => 'PA', '14' => 'ES', '15' => 'PI',
			'16' => 'RN', '17' => 'AL', '18' => 'MT', '19' => 'MS', '20' => 'DF',
			'21' => 'SE', '22' => 'AM', '23' => 'RS', '24' => 'AC', '25' => 'AP',
			'26' => 'RR', '27' => 'TO', '28' => 'Exterior'
		];
	
		if($this->isValid($value))
			return $uf[substr($value, 8, 2)];
	
		return NULL;
	}
}