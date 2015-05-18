<?php
namespace mecontrola\base;

/**
 * Classe abstrata possui os métodos necessários para trabalhar com a formatação de <code>Strings</code>.
 *
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\base
 * @since 1.0
 */
abstract class Formatter
{
	protected $pattern = '';
	
	/**
	 * Retorna o valor informado formatado. Caso não seja possível formatar será retornado <code>NULL</code>.
	 * 
	 * @access public
	 * @param String $value Valor que será formatado.
	 * @return String Retorna o valor formatado.  
	 * @see \mecontrola\base\Formatter::format()
	 */
	public function format($value)
	{
		if(!$this->canBeFormatted($value))
			return NULL;
		
		return $this->formatter($this->getMask(), $value);
	}
	
	/**
	 * Retorna o valor informado desformatado. Caso não seja possível desformatar será retornado <code>NULL</code>.
	 * 
	 * @access public
	 * @param String $value Valor que será desformatado.
	 * @return String Retorna o valor desformatado. 
	 */
	public function unformat($value)
	{
		if(!$this->canBeFormatted($value))
			return NULL;
		
		$mask = preg_replace('/[^0-9]/', '', $this->getMask());
		
		return $this->formatter($mask, $value);
	}
	
	/**
	 * Retorna a máscara utilizada para fazer a formatação dos valores.
	 *
	 * @access public
	 * @return String Retorna a máscara.
	 */
	public abstract function getMask();
	
	/**
	 * Verifica se o valor informado está formatado ou não.
	 *
	 * @access public
	 * @param String $value Valor que deve ser verificado.
	 * @return Boolean Retorna <code>TRUE</code> caso esteja formatado o valor ou <code>FALSE</code> caso contrário.
	 */
	public function isFormatted($value)
	{
		return @preg_match($this->pattern, $value) === 1;
	}
	
	/**
	 * Verifica se o valor informado pode ser formatado ou não.
	 *
	 * @access public
	 * @param String $value Valor que deve ser verificado.
	 * @return Boolean Retorna <code>TRUE</code> caso seja possível formatar o valor ou <code>FALSE</code> caso contrário.
	 */
	public function canBeFormatted($value)
	{
		$value = preg_replace('/[^0-9]/', '', $value);
		$mask = preg_replace('/[^0-9]/', '', $this->getMask());
		return (is_string($value) && strlen($value) === strlen($mask));
	}
	
	/**
	 * Formats a given value according to informed mask.
	 * 
	 * @access protected
	 * @param String $mask A máscara que será utilizada para formatar o valor. 
	 * @param String $value O valor que deve ser formatado de acordo com a máscara.
	 * @return String Retorna o valor formatador. 
	 */
	protected function formatter($mask, $value)
	{
		if(($size = strlen(preg_replace('/[^0-9]/', '', $mask))) !== strlen($value))
			$value = str_pad($value, $size, '0', STR_PAD_RIGHT);
	
		for($i = 0, $j = 0, $l = strlen($mask); $i < $l; $i++)
			if(is_numeric($mask[$i]))
				$mask = substr_replace($mask, $value[$j++], $i, 1);
	
		return $mask;
	}
}