<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pronóstico del clima a las 2 PM para los próximos 5 días en Tlaquepaque, México</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.8); /* Fondo semi-transparente para mejorar la legibilidad del texto */
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .forecast {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .time {
            background-color: rgba(255, 255, 255, 0.9); /* Fondo semi-transparente para mejorar la legibilidad del texto */
            border-radius: 5px;
            padding: 10px;
        }
        .time h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .time p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pronóstico del clima a las 8 PM para los próximos 5 días en Tlaquepaque, México</h1>
        <div class="forecast">
            <?php
            $apiKey = '0a89598dfe5e643b700f3ab7176b35ca'; // Reemplaza con tu clave de API válida
            $city = 'Tlaquepaque,mx';
            $apiUrl = "https://api.openweathermap.org/data/2.5/forecast?q={$city}&units=metric&appid={$apiKey}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            if ($response === false) {
                $error = curl_error($ch);
                echo "<p>Error en la petición cURL: $error</p>";
            } else {
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($httpCode == 200) {
                    $data = json_decode($response, true);
                    if (isset($data['list'])) {
                        $count = 0;
                        foreach ($data['list'] as $forecast) {
                            if (date('H', $forecast['dt']) == '20') { // Filtrar solo a las 14:00
                                $date = date('l, d M Y H:i', $forecast['dt']);
                                $temp = $forecast['main']['temp'];
                                $weatherDescription = $forecast['weather'][0]['description'];
                                $weatherIcon = $forecast['weather'][0]['icon'];
                                
                                // Determinar la imagen de fondo según el clima
                                $backgroundImage = '';
                                if (strpos($weatherIcon, '01') !== false) {
                                    $backgroundImage = 'sun.jpg'; // Ejemplo: Imagen para clima despejado
                                } elseif (strpos($weatherIcon, '09') !== false || strpos($weatherIcon, '10') !== false) {
                                    $backgroundImage = 'lluvia.png'; // Ejemplo: Imagen para lluvia
                                } elseif (strpos($weatherIcon, '13') !== false) {
                                    $backgroundImage = 'temp-low.png'; // Ejemplo: Imagen para nieve
                                } elseif (strpos($weatherIcon, '50') !== false) {
                                    $backgroundImage = 'temp-mid.png'; // Ejemplo: Imagen para niebla
                                } else {
                                    $backgroundImage = 'nubes.jpg'; // Por defecto: Imagen para nubes
                                }
                                
                                echo "<div class='time' style='background-image: url(images/{$backgroundImage});'>";
                                echo "<h3>{$date}</h3>";
                                echo "<p>Temperatura: {$temp}°C</p>";
                                echo "<p>Descripción: {$weatherDescription}</p>";
                                echo "</div>";
                                $count++;
                                if ($count >= 5) {
                                    break; // Mostrar solo 5 días
                                }
                            }
                        }
                        if ($count == 0) {
                            echo "<p>No hay datos disponibles para el pronóstico a las 8 PM en los próximos 5 días.</p>";
                        }
                    } else {
                        echo "<p>Error al obtener datos del clima.</p>";
                    }
                } elseif ($httpCode == 401) {
                    echo "<p>Error: No autorizado. Verifica tu clave de API.</p>";
                } else {
                    echo "<p>Error en la respuesta de la API: HTTP {$httpCode}</p>";
                }
            }

            curl_close($ch);
            ?>
        </div>
    </div>
</body>
</html>
