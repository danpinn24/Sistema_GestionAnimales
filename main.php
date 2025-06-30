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

function registrarAnimal() {
    mostrar("===== Registrar Nuevo Animal =====");
    mostrar("Función: Registrar nuevo animal (pendiente de implementar)");
   
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

function verDetallesAnimal() {
    mostrar("===== Ver Detalles de Animal =====");
    mostrar("Función: Ver detalles de un animal (pendiente de implementar)");
    // Aquí iría la lógica para solicitar un ID/nombre y mostrar el perfil completo del animal
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