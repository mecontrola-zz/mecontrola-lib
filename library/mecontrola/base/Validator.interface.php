<?php
namespace mecontrola\base;

/**
 * Interface que contem os métodos necessários para implementar as regras de validação.
 * 
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\base
 * @since 1.0
 */
interface Validator
{
	/**
	 * Verifica se o valor informado é valido ou não.
	 * 
	 * @access public
	 * @param String $value O valor que deseja ser validado.
	 * @return Boolean Retorna <code>TRUE</code> caso o valor informador seja valido e <code>FALSE</code> caso contrário.
	 * @throws IllegalArgumentException Caso o valor fornecido seja <code>NULL</code> ou não seja do tipo <code>String</code>.
	 */
	public function isValid($value);
}