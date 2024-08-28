<?php

header('Content-Type: application/json');

$contactos = [
    ["nombre" => "Juan", "apellido" => "Pérez", "edad" => 30],
    ["nombre" => "Ana", "apellido" => "López", "edad" => 25],
    ["nombre" => "Luis", "apellido" => "Pérez", "edad" => 30],
    ["nombre" => "María", "apellido" => "García", "edad" => 22],
    ["nombre" => "Carlos", "apellido" => "López", "edad" => 28],
    ["nombre" => "Laura", "apellido" => "Pérez", "edad" => 30],
    ["nombre" => "Andrés", "apellido" => "Martínez", "edad" => 35],
    ["nombre" => "Sofía", "apellido" => "García", "edad" => 22],
    ["nombre" => "Lucía", "apellido" => "Pérez", "edad" => 30],
    ["nombre" => "Pedro", "apellido" => "López", "edad" => 28]
];

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$query = $_GET;

if ($method == 'GET' && $path == '/contactos') {
    echo json_encode($contactos);
    exit();
}

if ($method == 'GET' && $path == '/contactos/filtrar') {
    $resultado = $contactos;

    if (isset($query['nombre'])) {
        $nombre = strtolower($query['nombre']);
        $resultado = array_filter($resultado, function($contacto) use ($nombre) {
            return strtolower($contacto['nombre']) == $nombre;
        });
    }

    if (isset($query['apellido'])) {
        $apellido = strtolower($query['apellido']);
        $resultado = array_filter($resultado, function($contacto) use ($apellido) {
            return strtolower($contacto['apellido']) == $apellido;
        });
    }

    if (isset($query['edad'])) {
        $edad = $query['edad'];
        $resultado = array_filter($resultado, function($contacto) use ($edad) {
            return $contacto['edad'] == $edad;
        });
    }

    if (isset($query['term'])) {
        $term = strtolower($query['term']);
        $resultado = array_filter($resultado, function($contacto) use ($term) {
            return strpos(strtolower($contacto['nombre']), $term) !== false ||
                   strpos(strtolower($contacto['apellido']), $term) !== false ||
                   strpos((string)$contacto['edad'], $term) !== false;
        });
    }

    echo json_encode(array_values($resultado));
    exit();
}

if ($method == 'GET' && $path == '/version') {
    echo json_encode(["version" => "1.0", "nombre" => "Libreta de Contactos"]);
    exit();
}

// En caso de que la ruta no sea válida
http_response_code(404);
echo json_encode(["error" => "Ruta no encontrada"]);
exit();

