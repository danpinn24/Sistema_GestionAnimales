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
    $adopcionesManager = new AdopcionesManager();
    $adopcionesManager->realizarAdopcion($db->getAnimales(), $db->getAdoptantes(), $db);
    leer("\nPresione ENTER para continuar...");
}


// === FUNCIONES DE GESTIÓN DE ANIMALES === //


function registrarAnimal() {
    global $db;

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

    do {
    mostrar("Seleccione el estado de adopción: ");
    mostrar("1. Listo para adopción");
    mostrar("2. En proceso");
    mostrar("3. Adoptado");

    $opcion = leer();

    if ($opcion == "1") {
        $estado = "Listo para adopción";
    } elseif ($opcion == "2") {
        $estado = "En proceso";
    } elseif ($opcion == "3") {
        $estado = "Adoptado";
    } else {
        mostrar(" Opción inválida. Intente de nuevo.\n");
        $estado = null; // queda sin valor hasta que ponga bien
    }

} while ($estado === null);



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


    $db->agregarAnimal($nuevoAnimal);

    mostrar("\nAnimal '" . $nuevoAnimal->getNombre() . "' (ID: " . $nuevoAnimal->getId() . ") registrado con éxito.");

    leer("\nPresione ENTER para continuar...");
}

function modificarAnimal() {
    global $db;
    mostrar("===== Modificar Animal =====");

    if (empty($db->getAnimales())) {
        mostrar("No hay animales registrados.");
        leer("\nPresione ENTER para continuar...");
        return;
    }

    mostrar("Ingrese el ID del animal a modificar:");
    $id = (int) leer();

    // Buscar el animal
    $animalEncontrado = null;
    foreach ($db->getAnimales() as $animal) {
        if ($animal->getId() == $id) {
            $animalEncontrado = $animal;
            break;
        }
    }

    if (!$animalEncontrado) {
        mostrar("No se encontró un animal con ese ID.");
        leer("\nPresione ENTER para continuar...");
        return;
    }

    // Mostrar valores actuales y permitir modificar
    mostrar("Deje en blanco para mantener el valor actual.");

    $nombre = leer("Nombre [{$animalEncontrado->getNombre()}]:");
    $especie = leer("Especie [{$animalEncontrado->getEspecie()}]:");
    $raza = leer("Raza [{$animalEncontrado->getRaza()}]:");
    $edad = leer("Edad [{$animalEncontrado->getEdad()}]:");
    $sexo = leer("Sexo [{$animalEncontrado->getSexo()}]:");
    $caracteristicas = leer("Características físicas [{$animalEncontrado->getCaracteristicasFisicas()}]:");
    $estado = leer("Estado de adopción [{$animalEncontrado->getEstado()}]:");

    $nuevosDatos = [
        'nombre' => $nombre !== '' ? $nombre : $animalEncontrado->getNombre(),
        'especie' => $especie !== '' ? $especie : $animalEncontrado->getEspecie(),
        'raza' => $raza !== '' ? $raza : $animalEncontrado->getRaza(),
        'edad' => is_numeric($edad) ? (int)$edad : $animalEncontrado->getEdad(),
        'sexo' => $sexo !== '' ? $sexo : $animalEncontrado->getSexo(),
        'caracteristicasFisicas' => $caracteristicas !== '' ? $caracteristicas : $animalEncontrado->getCaracteristicasFisicas(),
        'estado' => $estado !== '' ? $estado : $animalEncontrado->getEstado()
    ];

    $db->modificarAnimalPorId($id, $nuevosDatos);

    mostrar("✅ Animal modificado con éxito.");
    leer("\nPresione ENTER para continuar...");
}


function borrarAnimal() {
    global $db;
    mostrar("===== Borrar Animal =====");

    $animales = $db->getAnimales();

    if (empty($animales)) {
        mostrar("No hay animales registrados para borrar.");
        leer("\nPresione ENTER para continuar...");
        return;
    }

    mostrar("Ingrese el ID del animal a borrar:");
    $id = (int) leer();

    $encontrado = false;

    foreach ($animales as $indice => $animal) {
        if ($animal->getId() === $id) {
            $nombre = $animal->getNombre();
            // Confirmación simple (opcional)
            mostrar("¿Estás segura/o de que querés borrar a '$nombre'? (s/n)");
            $confirmar = strtolower(leer());

            if ($confirmar === 's') {
                $db->eliminarAnimal($indice);
                mostrar("✅ Animal '$nombre' eliminado con éxito.");
            } else {
                mostrar("❌ Operación cancelada.");
            }

            $encontrado = true;
            break;
        }
    }

    if (!$encontrado) {
        mostrar("No se encontró ningún animal con ese ID.");
    }

    leer("\nPresione ENTER para continuar...");
}



function verDetallesAnimal() {
    global $db;

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
// === FUNCIONES DE GESTIÓN DE ADOPTANTES ===
function registrarAdoptante() {
    global $db;

    mostrar("===== Registrar Nuevo Adoptante =====");
    mostrar("Ingrese el nombre y apellido del adoptante:");
    $nombre = leer();

    mostrar("Ingrese el DNI del adoptante:");
    $dni = leer();

    mostrar("Ingrese la dirección del adoptante:");
    $direccion = leer();

    mostrar("Ingrese el teléfono del adoptante:");
    $telefono = leer();

    mostrar("Ingrese el email del adoptante:");
    $email = leer();

    $requisitosCumplidos = false;
    do {
        mostrar("¿Cumple con los requisitos para adoptar? (s/n):");
        $respuesta = strtolower(leer());
        if ($respuesta === 's') {
            $requisitosCumplidos = true;
            break;
        } elseif ($respuesta === 'n') {
            $requisitosCumplidos = false;
            break;
        } else {
            mostrar("Respuesta inválida. Por favor, ingrese 's' o 'n'.");
        }
    } while (true);

    $nuevoAdoptante = new Adoptante(
        $nombre,
        $dni,
        $direccion,
        $telefono,
        $email,
        $requisitosCumplidos
    );

    $db->agregarAdoptante($nuevoAdoptante);

    mostrar("\nAdoptante '" . $nuevoAdoptante->getNombre() . "' (ID: " . $nuevoAdoptante->getId() . ") registrado con éxito.");

    leer("\nPresione ENTER para continuar...");
}

function modificarAdoptante() {
    global $db;
    mostrar("===== Modificar Adoptante =====");

    if (empty($db->getAdoptantes())) {
        mostrar("No hay adoptantes registrados.");
        leer("\nPresione ENTER para continuar...");
        return;
    }

    mostrar("Ingrese el ID del adoptante a modificar:");
    $id = (int) leer();

    // Buscar el adoptante
    $adoptanteEncontrado = null;
    foreach ($db->getAdoptantes() as $adoptante) {
        if ($adoptante->getId() == $id) {
            $adoptanteEncontrado = $adoptante;
            break;
        }
    }

    if (!$adoptanteEncontrado) {
        mostrar("No se encontró un adoptante con ese ID.");
        leer("\nPresione ENTER para continuar...");
        return;
    }

    // Mostrar valores actuales y permitir modificar
    mostrar("Deje en blanco para mantener el valor actual.");

    $nombre = leer("Nombre [{$adoptanteEncontrado->getNombre()}]:");
    $dni = leer("DNI [{$adoptanteEncontrado->getDni()}]:");
    $direccion = leer("Dirección [{$adoptanteEncontrado->getDireccion()}]:");
    $telefono = leer("Teléfono [{$adoptanteEncontrado->getTelefono()}]:");
    $email = leer("Email [{$adoptanteEncontrado->getEmail()}]:");

    $requisitosActuales = $adoptanteEncontrado->cumpleRequisitos() ? 'Sí' : 'No';
    $requisitosInput = leer("¿Cumple requisitos? (s/n) [{$requisitosActuales}]:");
    $requisitosCumplidos = $adoptanteEncontrado->cumpleRequisitos(); // Mantener el valor actual por defecto
    if (strtolower($requisitosInput) === 's') {
        $requisitosCumplidos = true;
    } elseif (strtolower($requisitosInput) === 'n') {
        $requisitosCumplidos = false;
    }


    $nuevosDatos = [
        'nombre' => $nombre !== '' ? $nombre : $adoptanteEncontrado->getNombre(),
        'dni' => $dni !== '' ? $dni : $adoptanteEncontrado->getDni(),
        'direccion' => $direccion !== '' ? $direccion : $adoptanteEncontrado->getDireccion(),
        'telefono' => $telefono !== '' ? $telefono : $adoptanteEncontrado->getTelefono(),
        'email' => $email !== '' ? $email : $adoptanteEncontrado->getEmail(),
        'requisitosCumplidos' => $requisitosCumplidos
    ];

    $db->modificarAdoptantePorId($id, $nuevosDatos);

    mostrar("✅ Adoptante modificado con éxito.");
    leer("\nPresione ENTER para continuar...");
}

function borrarAdoptante() {
    global $db;
    mostrar("===== Borrar Adoptante =====");

    $adoptantes = $db->getAdoptantes();

    if (empty($adoptantes)) {
        mostrar("No hay adoptantes registrados para borrar.");
        leer("\nPresione ENTER para continuar...");
        return;
    }

    mostrar("Ingrese el ID del adoptante a borrar:");
    $id = (int) leer();

    $encontrado = false;

    foreach ($adoptantes as $indice => $adoptante) {
        if ($adoptante->getId() === $id) {
            $nombre = $adoptante->getNombre();
            mostrar("¿Estás segura/o de que querés borrar a '$nombre'? (s/n)");
            $confirmar = strtolower(leer());

            if ($confirmar === 's') {
                $db->eliminarAdoptante($indice);
                mostrar("✅ Adoptante '$nombre' eliminado con éxito.");
            } else {
                mostrar("❌ Operación cancelada.");
            }

            $encontrado = true;
            break;
        }
    }

    if (!$encontrado) {
        mostrar("No se encontró ningún adoptante con ese ID.");
    }

    leer("\nPresione ENTER para continuar...");
}

function verDetallesAdoptante() {
    global $db;

    mostrar("===== Ver Detalles de Adoptante =====");

    if (empty($db->getAdoptantes())) {
        mostrar("No hay adoptantes registrados para ver sus detalles.");
        leer("\nPresione ENTER para continuar...");
        return;
    }

    mostrar("Ingrese el nombre o ID del adoptante que desea ver:");
    $busqueda = leer();

    $adoptanteEncontrado = null;

    if (is_numeric($busqueda)) {
        $idBuscado = (int)$busqueda;
        foreach ($db->getAdoptantes() as $adoptante) {
            if ($adoptante->getId() === $idBuscado) {
                $adoptanteEncontrado = $adoptante;
                break;
            }
        }
    }

    if ($adoptanteEncontrado === null) {
        $busquedaLower = strtolower($busqueda);
        foreach ($db->getAdoptantes() as $adoptante) {
            if (strtolower($adoptante->getNombre()) === $busquedaLower || strtolower($adoptante->getDni()) === $busquedaLower) {
                $adoptanteEncontrado = $adoptante;
                break;
            }
        }
    }

    if ($adoptanteEncontrado) {
        $adoptanteEncontrado->mostrarPerfilLimpio();
    } else {
        mostrar("No se encontró ningún adoptante con el nombre, DNI o ID: '" . $busqueda . "'");
    }

    leer("\nPresione ENTER para continuar...");
}
// === FUNCIONES DE GESTIÓN DE ADOPCIONES  ===
function verHistorialAdopciones() {
    global $db;
    mostrar("===== Historial de Adopciones =====");
    $adopciones = $db->getAdopciones();

    if (empty($adopciones)) {
        mostrar("No hay adopciones registradas todavía.");
    } else {
        foreach ($adopciones as $adopcion) {
            echo $adopcion . "\n";
        }
    }
    leer("\nPresione ENTER para continuar...");
}

function verDetallesAdopcion() {
    global $db;
    mostrar("===== Ver Detalles de Adopción =====");

    if (empty($db->getAdopciones())) {
        mostrar("No hay adopciones registradas.");
        leer("\nPresione ENTER para continuar...");
        return;
    }

    mostrar("Ingrese el ID de la adopción que desea ver:");
    $id = (int) leer();

    $adopcion = $db->buscarAdopcionPorId($id);

    if (!$adopcion) {
        mostrar("No se encontró una adopción con ese ID.");
        leer("\nPresione ENTER para continuar...");
        return;
    }

    $animal = $db->buscarAnimalPorId($adopcion->getIdAnimal());
    $adoptante = $db->buscarAdoptantePorId($adopcion->getIdAdoptante());

    mostrar("\n--- Detalles de la Adopción ---");
    mostrar("ID de Adopción: " . $adopcion->getIdAdopcion());
    mostrar("Fecha de Adopción: " . $adopcion->getFechaAdopcion());
    
    mostrar("\n--- Detalles del Animal ---");
    if ($animal) {
        mostrar("ID: " . $animal->getId());
        mostrar("Nombre: " . $animal->getNombre());
        mostrar("Especie: " . $animal->getEspecie());
        mostrar("Estado: " . $animal->getEstado());
    } else {
        mostrar("Animal no encontrado.");
    }

    mostrar("\n--- Detalles del Adoptante ---");
    if ($adoptante) {
        mostrar("ID: " . $adoptante->getId());
        mostrar("Nombre: " . $adoptante->getNombre());
        mostrar("Teléfono: " . $adoptante->getTelefono());
        mostrar("Email: " . $adoptante->getEmail());
    } else {
        mostrar("Adoptante no encontrado.");
    }

    mostrar("-----------------------------");
    leer("\nPresione ENTER para continuar...");
}


function modificarAdopcion() {
    global $db;
    mostrar("===== Modificar Adopción =====");

    if (empty($db->getAdopciones())) {
        mostrar("No hay adopciones registradas.");
        leer("\nPresione ENTER para continuar...");
        return;
    }

    mostrar("Ingrese el ID de la adopción a modificar:");
    $id = (int) leer();

    // Buscar la adopción
    $adopcionEncontrada = null;
    foreach ($db->getAdopciones() as $adopcion) {
        if ($adopcion->getIdAdopcion() == $id) {
            $adopcionEncontrada = $adopcion;
            break;
        }
    }

    if (!$adopcionEncontrada) {
        mostrar("No se encontró una adopción con ese ID.");
        leer("\nPresione ENTER para continuar...");
        return;
    }

    // Mostrar valores actuales y permitir modificar
    mostrar("Deje en blanco para mantener el valor actual.");

    $animalId = leer("ID Animal [{$adopcionEncontrada->getIdAnimal()}]:");
    $adoptanteId = leer("ID Adoptante [{$adopcionEncontrada->getIdAdoptante()}]:");
    $fecha = leer("Fecha de adopción [{$adopcionEncontrada->getFechaAdopcion()}]:");

    $nuevosDatos = [
        'animalId' => $animalId !== '' ? (int)$animalId : $adopcionEncontrada->getIdAnimal(),
        'adoptanteId' => $adoptanteId !== '' ? (int)$adoptanteId : $adopcionEncontrada->getIdAdoptante(),
        'fecha' => $fecha !== '' ? $fecha : $adopcionEncontrada->getFechaAdopcion()
    ];

    $db->modificarAdopcionPorId($id, $nuevosDatos);

    mostrar("✅ Adopción modificada con éxito.");
    leer("\nPresione ENTER para continuar...");
}

// En el archivo main.php, reemplaza la función borrarAdopcion() con esto
function borrarAdopcion() {
    global $db;
    mostrar("===== Borrar Adopción =====");

    $adopciones = $db->getAdopciones();

    if (empty($adopciones)) {
        mostrar("No hay adopciones registradas para borrar.");
        leer("\nPresione ENTER para continuar...");
        return;
    }

    mostrar("Ingrese el ID de la adopción a borrar:");
    $id = (int) leer();

    // Buscar la adopción por ID usando el método de la clase DB
    $adopcion = $db->buscarAdopcionPorId($id);

    if (!$adopcion) {
        mostrar("No se encontró ninguna adopción con ese ID.");
        leer("\nPresione ENTER para continuar...");
        return;
    }

    // Obtener el animal asociado a esta adopción
    $animalAsociado = $db->buscarAnimalPorId($adopcion->getIdAnimal());
    
    mostrar("¿Estás segura/o de que querés borrar la adopción con ID {$id}? Esto hará que el animal vuelva a estar disponible. (s/n)");
    $confirmar = strtolower(leer());

    if ($confirmar === 's') {
        // 1. Cambiar el estado del animal a "Listo para adopcion"
        if ($animalAsociado) {
            $animalAsociado->setEstado('Listo para adopcion');
        }
        
        // 2. Eliminar la adopción del registro
        $db->eliminarAdopcion($adopcion->getIdAdopcion()); // Usamos el ID de la adopción para buscarla y eliminarla

        mostrar("✅ Adopción con ID {$id} eliminada con éxito y animal asociado marcado como 'Listo para adopción'.");
    } else {
        mostrar("❌ Operación cancelada.");
    }

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