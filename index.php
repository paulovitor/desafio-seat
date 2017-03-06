<?php

$desafio = new Desafio('Paulo Vitor de Oliveira Santos');

$get  = $desafio->requisicaoGet();
$post = $desafio->requisicaoPost($get);

echo $post->{'mensagem'};
echo '<br>';
echo 'Chave: ' . $post->{'chave'};
echo '<br>';
echo 'Resultado: ' . $post->{'resultado'};
echo '<br>';
echo 'E-mail: ' . $post->{'mailTo'};
echo '<br>';
echo 'Assunto: ' . $post->{'subject'};

class Desafio
{
	private $get  = 'http://seat.ind.br/processo-seletivo/desafio.php';
	private $post = 'http://seat.ind.br/processo-seletivo/desafio-post.php';

	public function __construct($nome) {
		$this->nome = $nome;
	}

	public function requisicaoGet() {
		$url = $this->get . '?nome=' . urlencode($this->nome);
		$json = file_get_contents($url);
		return json_decode($json);
	}

	public function requisicaoPost($resposta) {
		$numerosNaoPrimos = $this->recuperaNumerosNaoPrimos($resposta->{'input'});
		$valor = implode('', $numerosNaoPrimos);

		$parametros = array('nome' => $this->nome, 'chave' => $resposta->{'chave'}, 'resultado' => $valor);
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($parametros)
		    )
		);

		$contexto  	= stream_context_create($options);
		$json 		= file_get_contents($this->post, false, $contexto);
		return json_decode($json);
	}

	private function recuperaNumerosNaoPrimos($array) {
		$numerosNaoPrimos = array();
		foreach ($array as $valor) {
			if ($this->naoEhPrimo($valor))
				$numerosNaoPrimos[] = $valor;
		}
		return $numerosNaoPrimos;
	}

	private function naoEhPrimo($valor) {
		return gmp_prob_prime($valor) == 0;
	}
}