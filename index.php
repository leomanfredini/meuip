<?php

function getUserIP()
{
    // Retorna o IP real de um visitante que esteja atrás da rede CloudFlare
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

$user_ip = getUserIP();

// Obter informações de geolocalização usando a API ip-api
$geo_data = @file_get_contents("http://ip-api.com/json/{$user_ip}");
$geo_info = $geo_data ? json_decode($geo_data, true) : null;

$city = $geo_info && $geo_info['status'] === 'success' ? $geo_info['city'] : "Cidade não disponível";
$region = $geo_info && $geo_info['status'] === 'success' ? $geo_info['regionName'] : "Região não disponível";
$country = $geo_info && $geo_info['status'] === 'success' ? $geo_info['country'] : "País não disponível";

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Exibe o endereço IP de sua conexão à internet">
        <meta name="author" content="Leonardo">
        <title>Seu Endereço IP</title>

        <!-- Bootstrap core CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            body {
                background-color: rgb(180, 180, 180);
                color: #212529;
                font-family: Arial, sans-serif;
                height: 100vh;
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .ip-container {
                position: relative; /* Necessário para posicionar o pin */
                padding: 30px;
                background-color: #fdfd96; /* Cor amarela de post-it */
                border: 1px solid #e6e600; /* Borda amarela */
                border-radius: 5px;
                box-shadow: 15px 15px 15px rgba(0, 0, 0, 0.3); /* Sombra para dar profundidade */
                transform: rotate(-3deg); /* Leve rotação para parecer um post-it */
                max-width: 400px;
                text-align: center;
            }
            .ip-title {
                font-size: 2rem;
                font-weight: bold;
                margin-bottom: 20px;
            }
            .ip-address {
                font-size: 2.5rem;
                color: rgb(0, 68, 143);
                font-weight: bold;
            }
            .geo-info {
                font-size: 1.2rem;
                color: #555;
                margin-top: 10px;
                text-align: left;
            }
            .geo-info p {
                display: flex;
                align-items: center;
                margin: 5px 0;
            }
            .geo-info i {
                margin-right: 10px;
                color: #333;
            }
            .pin {
                position: absolute;
                top: -15px; /* Ajuste para posicionar o pin acima do post-it */
                left: 50%; /* Centraliza horizontalmente */
                transform: translateX(-50%); /* Centraliza o pin */
                width: 20px;
                height: 20px;
                background-color: red; /* Cor do pin */
                border-radius: 50%; /* Torna o pin circular */
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3); /* Sombra para o pin */
            }
            .pin::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 10px;
                height: 10px;
                background-color: white; /* Centro branco do pin */
                border-radius: 50%;
            }
        </style>
    </head>
    <body>
        <div class="ip-container">
            <div class="pin"></div> <!-- Pin vermelho -->
            <h1 class="ip-title">Seu endereço IP é:</h1>
            <p class="ip-address"><?php echo $user_ip; ?></p>
            <div class="geo-info">
                <p><i class="bi bi-geo-alt-fill"></i><?php echo $city; ?></p>
                <p><i class="bi bi-map"></i><?php echo $region; ?></p>
                <p><i class="bi bi-globe"></i><?php echo $country; ?></p>
            </div>
        </div>

        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
