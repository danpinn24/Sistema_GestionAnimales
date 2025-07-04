<?php
require_once('./libreria/util.php');
require_once('./libreria/menu.php');
require_once('./db/loadDatos.php'); 
require_once('./db/class_adopcion.php');

// === FUNCIONES PRINCIPALES ===

function salir() {
    mostrar("Saliendo del sistema...");
    exit;
}

function listarAnimales() {
    global $db;
    mostrar("===== Lista de Animales =====");
    if (empty($db->getAnimales())) {
        mostrar("No hay animales registrados.");
    } else {
        foreach ($db->getAnimales() as $animal) {
            echo $animal . "\n";
        }
    }
    leer("\nPresione ENTER para continuar...");
}

function listarAdoptantes() {
    global $db;
    mostrar("===== Lista de Adoptantes =====");
    if (empty($db->getAdoptantes())) {
        mostrar("No hay adoptantes registrados.");
    } else {
        foreach ($db->getAdoptantes() as $adoptante) {
            echo $adoptante . "\n";
        }
    }
    leer("\nPresione ENTER para continuar...");
}

function verAnimalesDisponibles() {
    global $db;
    $enAdopcion = array_filter($db->getAnimales(), function($a) {
        return strtolower($a->getEstado()) === 'listo para adopcion';
    });

    if (empty($enAdopcion)) {
        mostrar("No hay animales listos para adopción.");
    } else {
        mostrar("Animales listos para adopción:");
        foreach ($enAdopcion as $a) {
            echo $a . "\n";
        }
    }
    leer("\nPresione ENTER para continuar...");
}

function verAdoptantesHabilitados() {
    global $db;
    $habilitados = array_filter($db->getAdoptantes(), fn($a) => $a->cumpleRequisitos());

    if (empty($habilitados)) {
        mostrar("No hay adoptantes habilitados.");
    } else {
        mostrar("Adoptantes con requisitos cumplidos:");
        foreach ($habilitados as $a) {
            echo $a . "\n";
        }
    }
    leer("\nPresione ENTER para continuar...");
}

function realizarAdopcion() {
    global $db;
    $adopciones = new Adopciones();
    $adopciones->realizarAdopcion($db->getAnimales(), $db->getAdoptantes());
    leer("\nPresione ENTER para continuar...");
}

// === FUNCIONES DE GESTIÓN DE ANIMALES (PENDIENTES DE IMPLEMENTAR LÓGICA) ===

// ... (resto de tu código y includes)

function registrarAnimal() {
    global $db; // Accede a la instancia global de tu "base de datos"

    mostrar("===== Registrar Nuevo Animal =====");

    mostrar("Ingrese el nombre del animal:");
    $nombre = leer();

    mostrar("Ingrese la especie del animal (ej. 'Perro', 'Gato'):");
    $especie = leer();

    mostrar("Ingrese la raza del animal:");
    $raza = leer();

    $edad = '';
    do {
        mostrar("Ingrese la edad del animal (solo números):");
        $inputEdad = leer();
        if (is_numeric($inputEdad) && $inputEdad >= 0) {
            $edad = (int)$inputEdad;
        } else {
            mostrar("Entrada inválida. Por favor, ingrese un número para la edad.");
        }
    } while (!is_numeric($edad));

    mostrar("Ingrese el sexo del animal (M/H):");
    $sexo = leer();

    mostrar("Ingrese características físicas (ej. 'Pelo corto, blanco y negro'):");
    $caracteristicasFisicas = leer();

    $fechaIngreso = date('Y-m-d'); // Formato AAAA-MM-DD

    mostrar("Ingrese el estado de adopción (ej. 'Listo para adopcion', 'En proceso', 'Adoptado'):");
    $estado = leer();

    // Crear un nuevo objeto Animal
    $nuevoAnimal = new Animal(
        $nombre,
        $especie,
        $raza,
        $edad,
        $sexo,
        $caracteristicasFisicas,
        $fechaIngreso,
        $estado
    );

    // ¡Aquí está el cambio! Usamos 'agregarAnimal' en lugar de 'addAnimal'
    $db->agregarAnimal($nuevoAnimal); // <--- CAMBIO AQUÍ

    mostrar("\nAnimal '" . $nuevoAnimal->getNombre() . "' (ID: " . $nuevoAnimal->getId() . ") registrado con éxito.");

    leer("\nPresione ENTER para continuar...");
}

function modificarAnimal() {
    mostrar("===== Modificar Animal =====");
    mostrar("Función: Modificar un animal (pendiente de implementar)");
    // Aquí iría la lógica para buscar un animal por ID/nombre y modificar sus datos
    leer("\nPresione ENTER para continuar...");
}

function borrarAnimal() {
    mostrar("===== Borrar Animal =====");
    mostrar("Función: Borrar un animal (pendiente de implementar)");
    // Aquí iría la lógica para buscar un animal por ID/nombre y eliminarlo de $db
    leer("\nPresione ENTER para continuar...");
}

// En tu main.php o donde tengas las funciones del menú

function verDetallesAnimal() {
    global $db; // Aseguramos el acceso a la instancia de la "base de datos"

    mostrar("===== Ver Detalles de Animal =====");

    if (empty($db->getAnimales())) {
        mostrar("No hay animales registrados para ver sus detalles.");
        leer("\nPresione ENTER para continuar...");
        return; // Salir de la función si no hay animales
    }

    mostrar("Ingrese el nombre o ID del animal que desea ver:");
    $busqueda = leer();

    // 1. Buscar el animal
    // Necesitamos un método en $db para buscar. Lo asumimos como findAnimal()
    $animalEncontrado = null;

    // Intentar buscar por ID primero (si es numérico)
    if (is_numeric($busqueda)) {
        $idBuscado = (int)$busqueda;
        foreach ($db->getAnimales() as $animal) {
            if ($animal->getId() === $idBuscado) {
                $animalEncontrado = $animal;
                break; // Salir del bucle una vez encontrado
            }
        }
    }

    // Si no se encontró por ID o la búsqueda no fue numérica, intentar por nombre
    if ($animalEncontrado === null) {
        // Convertimos la búsqueda y el nombre del animal a minúsculas para hacerla insensible a mayúsculas/minúsculas
        $busquedaLower = strtolower($busqueda);
        foreach ($db->getAnimales() as $animal) {
            if (strtolower($animal->getNombre()) === $busquedaLower) {
                $animalEncontrado = $animal;
                break; // Salir del bucle una vez encontrado
            }
        }
    }


    // 2. Mostrar los detalles si se encuentra
    if ($animalEncontrado) {
        mostrar("\n--- Detalles del Animal ---");
        mostrar("ID: " . $animalEncontrado->getId());
        mostrar("Nombre: " . $animalEncontrado->getNombre());
        mostrar("Especie: " . $animalEncontrado->getEspecie());
        mostrar("Raza: " . $animalEncontrado->getRaza());
        mostrar("Edad: " . $animalEncontrado->getEdad());
        mostrar("Sexo: " . $animalEncontrado->getSexo());
        mostrar("Características Físicas: " . $animalEncontrado->getCaracteristicasFisicas());
        mostrar("Fecha de Ingreso: " . $animalEncontrado->getFechaIngreso());
        mostrar("Estado: " . $animalEncontrado->getEstado());
        mostrar("--------------------------");
    } else {
        mostrar("No se encontró ningún animal con el nombre o ID: '" . $busqueda . "'");
    }

    leer("\nPresione ENTER para continuar...");
}
// === FUNCIONES DE GESTIÓN DE ADOPTANTES (PENDIENTES DE IMPLEMENTAR LÓGICA) ===

function registrarAdoptante() {
    mostrar("===== Registrar Nuevo Adoptante =====");
    mostrar("Función: Registrar nuevo adoptante (pendiente de implementar)");
    // Aquí iría la lógica para solicitar los datos del nuevo adoptante y agregarlo a $db
    leer("\nPresione ENTER para continuar...");
}

function modificarAdoptante() {
    mostrar("===== Modificar Adoptante =====");
    mostrar("Función: Modificar un adoptante (pendiente de implementar)");
    // Aquí iría la lógica para buscar un adoptante por ID/DNI y modificar sus datos
    leer("\nPresione ENTER para continuar...");
}

function borrarAdoptante() {
    mostrar("===== Borrar Adoptante =====");
    mostrar("Función: Borrar un adoptante (pendiente de implementar)");
    // Aquí iría la lógica para buscar un adoptante por ID/DNI y eliminarlo de $db
    leer("\nPresione ENTER para continuar...");
}

function verDetallesAdoptante() {
    mostrar("===== Ver Detalles de Adoptante =====");
    mostrar("Función: Ver detalles de un adoptante (pendiente de implementar)");
    // Aquí iría la lógica para solicitar un ID/DNI y mostrar el perfil completo del adoptante
    leer("\nPresione ENTER para continuar...");
}

// === FUNCIONES DE GESTIÓN DE ADOPCIONES ADICIONALES (PENDIENTES DE IMPLEMENTAR LÓGICA) ===

function verHistorialAdopciones() {
    mostrar("===== Historial de Adopciones =====");
    mostrar("Función: Ver historial de adopciones (pendiente de implementar)");
    // Aquí iría la lógica para mostrar las adopciones realizadas
    leer("\nPresione ENTER para continuar...");
}


// === MENÚS SECUNDARIOS ===

function menuAnimales() {
    $menu = Menu::getMenuAnimales();
    ejecutarMenu($menu);
}

function menuAdoptantes() {
    $menu = Menu::getMenuAdoptantes();
    ejecutarMenu($menu);
}

function menuAdopciones() {
    $menu = Menu::getMenuAdopciones();
    ejecutarMenu($menu);
}

// Función auxiliar para ejecutar cualquier menú
function ejecutarMenu($menu) {
    global $db; 
    do {
        $opcion = $menu->elegir();
        if ($opcion->getNombre() === 'Volver' || $opcion->getNombre() === 'Salir') {
            break; // Sale del bucle para volver al menú anterior o salir del sistema
        }
        // Llama a la función asociada con la opción elegida
        call_user_func($opcion->getFuncion());
    } while (true); // Mantiene el menú activo hasta que se elija 'Volver' o 'Salir'
}


// === INICIO DEL PROGRAMA ===

mostrar("===== Sistema de Adopciones de Animales =====");
$menu = Menu::getMenuPrincipal();
ejecutarMenu($menu);

?>