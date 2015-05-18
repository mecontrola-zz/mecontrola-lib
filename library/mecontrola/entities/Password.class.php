<?php
namespace mecontrola\entities;

/**
 * Classe encarregada de gerar e verificar qual é o nível de complexidade da senha..
 *
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities
 * @since 1.0.20150518
 */
class Password
{
	// -- Métodos para averiguar a força da senha --
	
	/**
	 * Mede qual é o nível de complexidade da senha informada, retornando a pontuação e complexidade da senha.
	 * 
	 * @access public
	 * @param String $password A senha que deve ser checada.
	 * @return Array Retorna um array com o score e complexity.
	 */
	public function check($password)
	{
		// Remove qualquer tipo de espaço
		$password = preg_replace('/\\s+/', '', $password);
	
		$score = $this->getScore($password);
		$complexity = "";
		
		if($score >= 0 && $score < 20)
			$complexity = "Muito Fraco";
		else if($score >= 20 && $score < 40)
			$complexity = "Fraco";
		else if($score >= 40 && $score < 60)
			$complexity = "Boa";
		else if($score >= 60 && $score < 80)
			$complexity = "Forte";
		else if($score >= 80 && $score <= 100)
			$complexity = "Muito Forte";
	
		return [
			'score' => $score,
			'complexity' => $complexity
		];
	}
	
	/**
	 * Mede o nível de força que a senha fornecida possui, a escala de força é menssurada dentro de um intervalo que varia de 0 até 100.
	 * 
	 * @access private
	 * @param String $password Senha que será verificada a força.
	 * @param Integer $minRequered Tamanho mínimo que a senha deve possuir.
	 * @return Integer Retorna o valor da força medida.
	 */
	private function getScore($password, $minRequered = 8)
	{
		// Size password
		$size = strlen($password);
		$hitRequirements = 0;
		$minReqChars = ($size >= $minRequered) ? 3 : 4;
		$score = 0;
		
		// Number of Characters
		$score += $size * 4;
		if($size >= $minRequered)
			$hitRequirements++;
		
		// Uppercase Letters
		$hitUpWord = $this->hitUpWord($password);
		if($hitUpWord > 0 && $hitUpWord < $size)
		{
			$score += ($size - $hitUpWord) * 2;
			$hitRequirements++;
		}
		
		// Lowercase Letters
		$hitLowWord = $this->hitLowWord($password);
		if($hitLowWord > 0 && $hitLowWord < $size)
		{
			$score += ($size - $hitLowWord) * 2;
			$hitRequirements++;
		}
		
		// Numbers
		$hitNumeric = $this->hitNumeric($password);
		if($hitNumeric > 0 && $hitNumeric < $size)
		{
			$score += $hitNumeric * 4;
			$hitRequirements++;
		}
		
		// Symbols
		$hitSymbol = $this->hitSymbols($password);
		if($hitSymbol > 0 && $hitSymbol < $size)
		{
			$score += $hitSymbol * 6;
			$hitRequirements++;
		}
		
		// Middle Numbers or Symbols
		$hitMidNumericSymbol = $this->hitMiddleNumbersSymbols($password);
		if($hitMidNumericSymbol > 0)
			$score += $hitMidNumericSymbol * 2;
		
		// Requirements
		if($hitRequirements > $minReqChars)
			$score += $hitRequirements * 2;
		
		// Letters Only
		$score -= ($hitNumeric === 0 ? strlen($password) :  0);
		
		// Numbers Only
		$score -= ($hitUpWord === 0 && $hitLowWord === 0 ? strlen($password) :  0);
		
		// Repeat Characters (Case Insensitive)
		$score -= $this->getRepeatCharacters($password);
		
		// Consecutive Uppercase Letters
		$score -= $this->getConsecutiveUpWord($password) * 2;
		
		// Consecutive Lowercase Letters
		$score -= $this->getConsecutiveLowWord($password) * 2;
		
		// Consecutive Numbers
		$score -= $this->getConsecutiveNumbers($password) * 2;
		
		// Sequential Letters (3+)
		$score -= $this->getSequentialLetters($password) * 3;
		
		// Sequential Numbers (3+)
		$score -= $this->getSequentialNumbers($password) * 3;
		
		// Sequential Symbols (3+)
		$score -= $this->getSequentialSymbols($password) * 3;
		
		return (($score > 100) ? 100 : ($score < 0 ? 0 : $score));
	}
	
	/**
	 * Remove todos os caracteres especiais existentes em uma palavra.
	 * 
	 * @access private
	 * @param String $word A palavra em que se deve remover os caracteres especiais.
	 * @return String Retorna a palavra sem os caracteres especiais.
	 */
	private function removeSymbols($word)
	{
		$symbols = preg_replace('/([A-Z]|[a-z]|[0-9])*/', '', $word);
		
		for($i = 0, $l = strlen($word); $i < $l; $i++)
			for($j = 0, $k = strlen($symbols); $j < $k; $j++)
				if($word[$i] === $symbols[$j])
				{
					$word = substr_replace($word, '', $i, 1);
				
					if($i !== 0)
						$i--;
					
					$l = strlen($word);
				}
		
		return $word;
	}
	
	/**
	 * Retorna a quantidade de letras minúsculas contidas na palavras
	 * 
	 * @access public
	 * @param String $word A palavra que deve ser conferida.
	 * @return Integer Quantidade de letras minúsculas.
	 */
	private function hitLowWord($word)
	{
		$aux = $this->removeSymbols(preg_replace('/[A-Z]*[0-9]*/', '', $word));
		return strlen($aux);
	}
	
	/**
	 * Retorna a quantidade de letras maiúsculas contidas na palavras.
	 * 
	 * @access public
	 * @param String $word A palavra que deve ser conferida.
	 * @return Integer Quantidade de letras maiúsculas.
	 */
	private function hitUpWord($word)
	{
		$aux = $this->removeSymbols(preg_replace('/[a-z]*[0-9]*/', '', $word));
		return strlen($aux);
	}
	
	/**
	 * Retorna a quantidade de números contidas na palavras.
	 * 
	 * @access public
	 * @param String $word A palavra que deve ser conferida.
	 * @return Integer Quantidade de números.
	 */
	private function hitNumeric($word)
	{
		$aux = $this->removeSymbols(preg_replace('/[a-z]*[A-Z]*/', '', $word));
		return strlen($aux);
	}
	
	/**
	 * Retorna a quantidade de símbolos contidas na palavras.
	 * 
	 * @access public
	 * @param String $word A palavra que deve ser conferida.
	 * @return Integer Quantidade de símbolos.
	 */
	private function hitSymbols($word)
	{
		$aux = preg_replace('/[a-z]*[A-Z]*[0-9]*/', '', $word);
		return strlen($aux);
	}
	
	/**
	 * Retorna a quantidade de símbolos e números contidas na palavras.
	 *
	 * @access public
	 * @param String $word A palavra que deve ser conferida.
	 * @return Integer Quantidade de símbolos e números.
	 */
	private function hitMiddleNumbersSymbols($word)
	{
		$size = strlen($word);
		$out = 0;
		
		for($i = 0; $i < $size; $i++)
		{
			if(preg_match('/[0-9]/', $word[$i]) === 1)
			{
				if($i > 0 && $i < ($size - 1))
					$out++;
			} else if(preg_match('/[^a-zA-Z0-9_]/', $word[$i]) === 1)
			{
				if($i > 0 && $i < ($size - 1))
					$out++;
			}
		}
		
		return $out;
	}
	
	/**
	 * Retorna a quantidade de caracteres repetidos existente na palavra informada.
	 * 
	 * @access private
	 * @param String $word A palavra que deve ser conferida.
	 * @return Integer Retorna a quantidade de repetições.
	 */
	private function getRepeatCharacters($word)
	{
		$size = strlen($word);
		$nRepNumber = 0;
		$out = 0;

		for($i = 0; $i < $size; $i++)
		{
			$charExists = FALSE;
			for($j = 0; $j < $size; $j++)
			{
				if($word[$i] === $word[$j] && $i !== $j)
				{
					$charExists = TRUE;
					$out += abs($size / ($j - $i));
				}
			}
			if($charExists)
			{
				$nRepNumber++;
				$aux = $size - $nRepNumber;
				$out = ($aux != 0) ? ceil($out / $aux) : ceil($out);
			}
		}
		
		return intval($out);
	}
	
	/**
	 * Retorna a quantidade de letras maiúsculas estão de forma consecutiva na palavra. 
	 * 
	 * @access private
	 * @param String $word A palavra que deve ser conferida.
	 * @return Integer Quantidade consecutiva das letras maiúsculas.
	 */
	private function getConsecutiveUpWord($word)
	{
		$numberPrevious = FALSE;
		$out = 0;
		
		for($i = 0, $l = strlen($word); $i < $l; $i++)
		{
			$ascii = ord($word[$i]);
			
			if($ascii > 64 && $ascii < 91)
			{
				if($numberPrevious)
					$out++;
				
				$numberPrevious = TRUE;
			} else
				$numberPrevious = FALSE;
		}
		
		return $out;
	}

	/**
	 * Retorna a quantidade de letras minúsculas estão de forma consecutiva na palavra. 
	 * 
	 * @access private
	 * @param String $word A palavra que deve ser conferida.
	 * @return Integer Quantidade consecutiva das letras minúsculas.
	 */
	private function getConsecutiveLowWord($word)
	{
		$numberPrevious = FALSE;
		$out = 0;
		
		for($i = 0, $l = strlen($word); $i < $l; $i++)
		{
			$ascii = ord($word[$i]);
			
			if($ascii > 96 && $ascii < 123)
			{
				if($numberPrevious)
					$out++;
				
				$numberPrevious = TRUE;
			} else
				$numberPrevious = FALSE;
		}
		
		return $out;
	}

	/**
	 * Retorna a quantidade de números estão de forma consecutiva na palavra.
	 *
	 * @access private
	 * @param String $word A palavra que deve ser conferida.
	 * @return Integer Quantidade consecutiva dos números.
	 */
	private function getConsecutiveNumbers($word)
	{
		$numberPrevious = FALSE;
		$out = 0;
		
		for($i = 0, $l = strlen($word); $i < $l; $i++)
		{
			$ascii = ord($word[$i]);
			
			if($ascii > 47 && $ascii < 58)
			{
				if($numberPrevious)
					$out++;
				
				$numberPrevious = TRUE;
			} else
				$numberPrevious = FALSE;
		}
		
		return $out;
	}

	/**
	 * Verificar a quantidade de sequência de caracteres encontradas na palavra.
	 *
	 * @access private
	 * @param String $word A palavra que deve ser conferida.
	 * @return Integer Retorna a quantidade de sequências entradas.
	 */
	private function getSequentialLetters($word)
	{
		$out = 0;
		$alpha = [];
		
		for($i = 97; $i <= 122; $i++)
			$alpha[$i - 97] = chr($i);
		
		$word = strtolower($word);
		for($i = 0, $l = count($alpha) - 3; $i < $l; $i++)
		{
			$seqText = $alpha[$i] . $alpha[$i + 1] . $alpha[$i + 2];
			
			$nText = trim($seqText);
			$rText = strrev($nText);
			
			if(strpos($word, $nText) !== FALSE || strpos($word, $rText) !== FALSE)
				$out += 1;
		}
		
		return $out;
	}

	/**
	 * Verificar a quantidade de sequência de números encontradas na palavra.
	 *
	 * @access private
	 * @param String $word A palavra que deve ser conferida.
	 * @return Integer Retorna a quantidade de sequências entradas.
	 */
	private function getSequentialNumbers($word)
	{
		$out = 0;
		$numbers = ['0' , '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
		
		for($i = 0, $l = count($numbers) - 3; $i < $l; $i++)
		{
			$seqText = $numbers[$i] . $numbers[$i + 1] . $numbers[$i + 2];
			
			$nText = trim($seqText);
			$rText = strrev($nText);
			
			if(strpos($word, $nText) !== FALSE || strpos($word, $rText) !== FALSE)
				$out += 1;
		}
		
		return $out;
	}
	
	/**
	 * Verificar a quantidade de sequência de símbolos encontradas na palavra.
	 * 
	 * @access private
	 * @param String $word A palavra que deve ser conferida.
	 * @return Integer Retorna a quantidade de sequências entradas.
	 */
	private function getSequentialSymbols($word)
	{
		$out = 0;
		$symbols = [')', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')'];
		
		for($i = 0, $l = count($symbols) - 3; $i < $l; $i++)
		{
			$seqText = $symbols[$i] . $symbols[$i + 1] . $symbols[$i + 2];
			
			$nText = trim($seqText);
			$rText = strrev($nText);
			
			if(strpos($word, $nText) !== FALSE || strpos($word, $rText) !== FALSE)
				$out += 1;
		}
		
		return $out;
	}
	
	// -- Métodos para gerar senha --
	
	/**
	 * Gera uma senha randomica de acordo com o tamanho e os tipos de caracteres definidos.
	 * Por padrão todos os tipos de caracteres serão utilizados. 
	 * 
	 * @access public
	 * @param Integer $length Tamanho que terá a senha.
	 * @param Boolean $number <code>TRUE</code> permite utilizadar números para compor a senha.
	 * @param Boolean $lower <code>TRUE</code> permite utilizadar letras minúsculas para compor a senha.
	 * @param Boolean $upper <code>TRUE</code> permite utilizadar letras maiúsculas para compor a senha.
	 * @param Boolean $special <code>TRUE</code> permite utilizadar caracteres especiais para compor a senha.
	 * @return String Retorna a senha gerada.
	 */
	public function generate($length = 8, $number = TRUE, $lower = TRUE, $upper = TRUE, $special = TRUE)
	{
		if($length < 1)
			return '';
		
		$password = '';
		$rest = [];
		$char = [
			'lower' => 'abcdefghijkmnopqrstuvwxyz', // Without l
			'upper' => 'ABCDEFGHJKLMNPQRSTUVWXYZ', // Without I & O
			'number' => '23456789', // Without 0 & 1
			'special' => '~!@#$%^&*()-_=+[{]}\\|;:\'",<.>/?!' // Other symbols
		];
		
		for($i = 0; $i < $length; $i++)
		{
			$rest[] = $i;
			$password .= chr(1);
		}
		
		$weight = $this->getWeight($length, $number, $lower, $upper, $special);
		
		$this->getRandomKeys($weight);
				
		foreach($weight as $key => $value)
			$this->getRandomCharacter($password, $rest, $char[$key], $value);
		
		return $password;
	}
	
	/**
	 * Embaralha as posições do array de pesos.
	 * 
	 * @access private
	 * @param Array $weight Array de pesos.
	 */
	private function getRandomKeys(&$weight)
	{
		foreach($weight as $key => $value)
			if($value === 0)
				unset($weight[$key]);
		
		$aux = [];
		$keys = array_keys($weight);
		for($i = 0, $l = count($keys); $i < $l; $i++)
		{
			$key = mt_rand(0, count($keys) - 1);
			$aux[$keys[$key]] = $weight[$keys[$key]];
			unset($keys[$key]);
			$keys = array_values($keys);
		}
		
		$weight = $aux;
	}
	
	/**
	 * Gerar um Array de pesos dos tipos de dados que serão usados para compor a senha.
	 * 
	 * @access private
	 * @param Integer $length Tamanho que terá a senha.
	 * @param Boolean $number <code>TRUE</code> permite utilizadar números para compor a senha.
	 * @param Boolean $lower <code>TRUE</code> permite utilizadar letras minúsculas para compor a senha.
	 * @param Boolean $upper <code>TRUE</code> permite utilizadar letras maiúsculas para compor a senha.
	 * @param Boolean $special <code>TRUE</code> permite utilizadar caracteres especiais para compor a senha.
	 * @return Array Retorna um array de pesos
	 */
	private function getWeight($length, $number, $lower, $upper, $special)
	{
		$weight = ['number' => 1, 'lower' => 1, 'upper' => 1, 'special' => 1];
		$total = 0;
		foreach($weight as $key => $value)
			if(${$key})
				$total += $value;
			else
				$weight[$key] = 0;
		
		$aux = array_keys($weight);
		for(; $total < $length;)
		{
			$key = $aux[mt_rand(0, 3)];
			if(${$key})
			{
				$weight[$key]++;
				$total++;
			}
		}
		
		return $weight;
	}
	
	/**
	 * Gerar um char aleatória e inserir na sua posição.
	 * 
	 * @access private
	 * @param String $password Ponteiro da senha gerada.
	 * @param Array $rest Ponteiro do array de posições disponíveis para substituir.
	 * @param String $char String com todos os caracteres permitidos.
	 * @param Integer $weight Quantidade de caracteres serão inseridos.
	 */
	private function getRandomCharacter(&$password, &$rest, $char, $weight)
	{
		if(count($rest) === 0)
			return;
		
		for($i = 0; $i < $weight; $i++)
		{
			$ch = mt_rand(0, count($rest) - 1);
			$x = mt_rand(0, strlen($char)-1);
			$password{$rest[$ch]} = $char{$x};
			unset($rest[$ch]);
			$rest = array_values($rest);
		}
	}
}