<?php
//receber a frase e transformar em um array com cada um dos caracteres dela.
function arrayLetras($texto){
    $texto = strtolower($texto); 
    return str_split($texto);
}

//definir a posição da letra equivalente baseado no num de casas deslocadas.
function cifra($letra, $numCasa){
    global $letras;
    $totalLetras = strlen($letras);
    $posInicial = strpos($letras, $letra);
    $posFinal = ($posInicial + $numCasa) % $totalLetras;
    return $posFinal;
}

// qual a letra que substituira a original baseada na posição da função anterior.
function novaLetra($posFinal){
    global $letras;
    $novaLetra = $letras[$posFinal];
    return $novaLetra;
}

//codificação do texto.
function decode($textoEmLetras){
    $resultado = '';
    foreach ($textoEmLetras as $letra) {
        
        if (!($letra == " " or $letra == '.')) {
            $resultado.= novaLetra(cifra($letra, -1));
        } else {
            $resultado.= novaLetra(cifra($letra, 0));
        }
    }
    return $resultado;
}

//receber dados da api, fazer a codificação do texto com os dados obtidos, fazer criptografia sha1 no resultado e salvar tudo no arquivo final.
$jsonbase = file_get_contents('end_api_traz_dados');
file_put_contents('answer.json', $jsonbase);
$letras = ('abcdefghijklmnopqrstuvwxyz. ');
$answer = json_decode(file_get_contents('answer.json'), true);
$texto = $answer['cifrado']; 
$textoEmLetras = arrayLetras($texto);
$decript = decode($textoEmLetras);
$answer['decifrado'] = $decript; 
$answer['resumo_criptografico'] = sha1($decript); 
$json = json_encode($answer);
file_put_contents('answer.json', $json);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
<!-- submeter o arquivo gerado via post (neste caso para uma api) -->
    <form action="end_da_api" method="POST" enctype="multipart/form-data">

        <input type="file" name="answer"><br><br>
        <input type="submit">

    </form>
</body>

</html>