<?php
namespace mecontrola\entities;

/**
 * Classe encarregada de gerar todas os feriados comemorativos do Brasil.
 * 
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities
 * @since 1.0.20150518
 */
class Feriados
{
	/**
	 * Lista de dias da semana em português.
	 * 
	 * @access private
	 * @var Array
	 * @static
	 */
	private static $dayOfWeek = [
		'Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'
	];
	
	/**
	 * Inicializa todas as configurações necessárias para o funcionamento das funções de data.
	 * 
	 * @access public
	 */
	public function __construct()
	{
		date_default_timezone_set('America/Sao_Paulo');
	}
	
	/**
	 * Retorna uma lista de todos os feriados comemorados no Brasil, caso o ano não seja informado, será utilizado o ano corrente.
	 * 
	 * @access public
	 * @param Integer $ano Ano em que se deseja recuperar os feriados.
	 * @return Array Lista de todos os feriados.
	 */
	public function getAll($ano = NULL)
	{
		$arr = $this->getAllTimestamp($ano);
		
		foreach($arr as $i => $itm)
			$arr[$i]['timestamp'] = self::$dayOfWeek[date('w', $itm['timestamp'])] . date(', d/m/Y', $itm['timestamp']);
		
		return $arr;
	}
	
	/**
	 * Lista de todos os feríados, fixos ou variados, a partir de um ano especificado.
	 * 
	 * @access public
	 * @param Integer $ano Ano em que se deseja recuperar os feriados.
	 * @return Array Lista de todos os feriados em Unix timestamp.
	 */
	public function getAllTimestamp($ano = NULL)
	{
		if(is_null($ano) || !is_integer($ano))
			$ano = date('Y');
		
		$pascoa = $this->getPascoa($ano);
		
		$arr = [
			['nome' => 'Carnaval', 'timestamp' => strtotime('-47 days', $pascoa)],
			['nome' => 'Sexta-feira Santa', 'timestamp' => strtotime('-2 days', $pascoa)],
			['nome' => 'Páscoa', 'timestamp' => $pascoa],
			['nome' => 'Corpus Christi', 'timestamp' => strtotime('+60 days', $pascoa)],
			['nome' => 'Dia das Mães', 'timestamp' => $this->get2Domingo(5, $ano)],
			['nome' => 'Dia dos Pais', 'timestamp' => $this->get2Domingo(8, $ano)],
			['nome' => 'Ano Novo', 'timestamp' => mktime(0, 0, 0, 1, 1, $ano)],
			['nome' => 'Tiradentes', 'timestamp' => mktime(0, 0, 0, 4, 21, $ano)],
			['nome' => 'Dia do Trabalhador', 'timestamp' => mktime(0, 0, 0, 5, 1, $ano)],
			['nome' => 'Independência do Brasil', 'timestamp' => mktime(0, 0, 0, 9, 7, $ano)],
			['nome' => 'Nossa Senhora Aparecida', 'timestamp' => mktime(0, 0, 0, 10, 12, $ano)],
			['nome' => 'Finados', 'timestamp' => mktime(0, 0, 0, 2, 11, $ano)],
			['nome' => 'Proclamação da República', 'timestamp' => mktime(0, 0, 0, 11, 15, $ano)],
			['nome' => 'Natal', 'timestamp' => mktime(0, 0, 0, 12, 25, $ano)]
		];
		
		usort($arr, [$this, 'sortDate']);
		
		return $arr;
	}
	
	/**
	 * Rotina utlizada para calcular os feriados que caem no segundo domingo de um dado mês (Dia das Mães e dos Pais).
	 * 
	 * @access private
	 * @param Integer $mes Mês em que se deseja calcular.
	 * @param Integer $ano Ano em que se deseja calcular.
	 * @return Integer Retorna a data em Unix timestamp.
	 */
	private function get2Domingo($mes, $ano)
	{
		$w = $itm = intval(date('w', mktime(0, 0, 0, $mes, 1, $ano)));
		$day = 7 + ($w === 0 ? 1 : (8 - $w));
		return mktime(0, 0, 0, $mes, $day, $ano);
	}
	
	/**
	 * Rotina que calcula a data base dos feriádos variáveis.
	 * Utilizando o algoritmo de Meeus/Jones/Butcher para calcular a data da Páscoa que é utilizada para calcular os outros feriados móveis como Carnaval, Seta-feira Santa e Corpus Christi.
	 * 
	 * @access private
	 * @param Integer $ano Ano em que se deseja calcular.
	 * @return Integer Retorna a data em Unix timestamp.
	 */
	private function getPascoa($ano)
	{
		$a = $ano % 19;
		$b = intval($ano / 100);
		$c = $ano % 100;
		$d = intval($b / 4);
		$e = $b % 4;
		$f = intval(($b + 8) / 25);
		$g = intval(($b - $f + 1) / 3);
		$h = (19 * $a + $b - $d - $g + 15) % 30;
		$i = intval($c / 4);
		$k = $c % 4;
		$l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
		$m = intval(($a + 11 * $h + 22 * $l) / 451);
		$n = $h + $l - 7 * $m + 114;
		$day = ($n % 31) + 1;
		$month = intval($n / 31);
	
		return mktime(0, 0, 0, $month, $day, $ano);
	}
	
	/**
	 * Métodos utilizado para ordenar a lista de datas comemorativas.
	 * 
	 * @param Array $a Data 1.
	 * @param Array $b Data 2.
	 * @return Integer Retorn -1 caso <code>$a</code> for menos que <code>$b</code>, 0 caso <code>$a</code> e <code>$b</code> sejam iguais e 1 caso <code>$a</code> for maior que <code>$b</code>.
	 */
	private function sortDate($a, $b)
	{
		if($a['timestamp'] > $b['timestamp'])
			return 1;
		elseif($a['timestamp'] < $b['timestamp'])
			return -1;
		else
			return 0;
	}
}