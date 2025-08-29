<?php
// Asegúrate de que las rutas son correctas según tu estructura de carpetas
// Asumiendo que animal.php y adoptante.php están en la misma carpeta que este archivo (db)
require_once "animal.php";
require_once "adoptante.php";

class Adopcion {
    private static $ultimoId = 0;
    private $idAdopcion;
    private $idAnimal;
    private $idAdoptante;
    private $fechaAdopcion;

    public function __construct($idAnimal, $idAdoptante, $fechaAdopcion) {
        self::$ultimoId++;
        $this->idAdopcion = self::$ultimoId;
        $this->idAnimal = $idAnimal;
        $this->idAdoptante = $idAdoptante;
        $this->fechaAdopcion = $fechaAdopcion;
    }

    // Getters
    public function getIdAdopcion() {
        return $this->idAdopcion;
    }

    public function getIdAnimal() {
        return $this->idAnimal;
    }

    public function getIdAdoptante() {
        return $this->idAdoptante;
    }

    public function getFechaAdopcion() {
        return $this->fechaAdopcion;
    }

    // Setters
    public function setAnimalId($id) {
        $this->idAnimal = $id;
    }

    public function setAdoptanteId($id) {
        $this->idAdoptante = $id;
    }

    public function setFechaAdopcion($fecha) {
        $this->fechaAdopcion = $fecha;
    }

    public function __toString() {
        return "ID Adopción: {$this->idAdopcion} - Animal ID: {$this->idAnimal} - Adoptante ID: {$this->idAdoptante} - Fecha: {$this->fechaAdopcion}";
    }
}

class AdopcionesManager {
    public function realizarAdopcion($animales, $adoptantes, $db) {
        echo "\n===== REALIZAR ADOPCIÓN =====\n";

        echo "Animales listos para adopción:\n";
        $listos = [];
        foreach ($animales as $animal) {
            if (strtolower($animal->getEstado()) === 'listo para adopcion') {
                $listos[] = $animal;
            }
        }

        if (empty($listos)) {
            echo "No hay animales listos para adopción.\n";
            return;
        }

        foreach ($listos as $animal) {
            echo "ID: " . $animal->getId() . " - Nombre: " . $animal->getNombre() . " - Especie: " . $animal->getEspecie() . " - Estado: " . $animal->getEstado() . "\n";
        }

        echo "Ingrese el ID del animal a adoptar: ";
        $idAnimalSeleccionado = intval(trim(fgets(STDIN)));
        $animalSeleccionado = null;

        foreach ($listos as $animal) {
            if ($animal->getId() === $idAnimalSeleccionado) {
                $animalSeleccionado = $animal;
                break;
            }
        }

        if (!$animalSeleccionado) {
            echo "ID de animal inválido o no disponible.\n";
            return;
        }

        echo "\nAdoptantes habilitados:\n";
        $habilitados = [];
        foreach ($adoptantes as $adoptante) {
            if ($adoptante->cumpleRequisitos()) {
                $habilitados[] = $adoptante;
            }
        }

        if (empty($habilitados)) {
            echo "No hay adoptantes habilitados.\n";
            return;
        }

        foreach ($habilitados as $adoptante) {
            echo "ID: " . $adoptante->getId() . " - Nombre: " . $adoptante->getNombre() . " - Requisitos: " . ($adoptante->cumpleRequisitos() ? 'Sí' : 'No') . "\n";
        }

        echo "Ingrese el ID del adoptante: ";
        $idAdoptanteSeleccionado = intval(trim(fgets(STDIN)));
        $adoptanteSeleccionado = null;

        foreach ($habilitados as $adoptante) {
            if ($adoptante->getId() === $idAdoptanteSeleccionado) {
                $adoptanteSeleccionado = $adoptante;
                break;
            }
        }

        if (!$adoptanteSeleccionado) {
            echo "ID de adoptante inválido o no habilitado.\n";
            return;
        }

        echo "\n¿Confirmar adopción de {$animalSeleccionado->getNombre()} por {$adoptanteSeleccionado->getNombre()}? (s/n): ";
        $respuesta = strtolower(trim(fgets(STDIN)));

        if ($respuesta === 's') {
            $animalSeleccionado->setEstado('Adoptado');
            $fechaActual = date('Y-m-d');

            $nuevaAdopcion = new Adopcion($animalSeleccionado->getId(), $adoptanteSeleccionado->getId(), $fechaActual);
            $db->agregarAdopcion($nuevaAdopcion);

            echo "\n✅ Adopción realizada con éxito. " . $nuevaAdopcion . "\n";
        } else {
            echo "\n❌ Adopción cancelada.\n";
        }
    }

    public function editarAdopcion($db) {
        echo "\n===== EDITAR ADOPCIÓN =====\n";

        $adopciones = $db->getAdopciones();
        if (empty($adopciones)) {
            echo "No hay adopciones registradas.\n";
            return;
        }

        foreach ($adopciones as $i => $adopcion) {
            echo ($i + 1) . ". " . $adopcion . "\n";
        }

        echo "Seleccione número de adopción: ";
        $numAdopcionIndex = intval(trim(fgets(STDIN))) - 1;
        $adopcion = $adopciones[$numAdopcionIndex] ?? null;

        if (!$adopcion) {
            echo "Selección inválida.\n";
            return;
        }

        echo "\nAdopción actual: " . $adopcion . "\n";

        echo "¿Desea cambiar el adoptante? (s/n): ";
        $resp = strtolower(trim(fgets(STDIN)));
        if ($resp === 's') {
            echo "Ingrese nuevo adoptanteId: ";
            $nuevoAdoptante = trim(fgets(STDIN));
            $adopcion->setAdoptanteId($nuevoAdoptante);
        }

        echo "¿Desea cambiar el estado del animal asociado? (s/n): ";
        $resp = strtolower(trim(fgets(STDIN)));
        if ($resp === 's') {
            echo "Ingrese nuevo estado del animal: ";
            $nuevoEstado = trim(fgets(STDIN));
            foreach ($db->getAnimales() as $animal) {
                if ($animal->getId() == $adopcion->getIdAnimal()) {
                    $animal->setEstado($nuevoEstado);
                }
            }
        }
        echo "✅ Adopción editada correctamente.\n";
    }
}