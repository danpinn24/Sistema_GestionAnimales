<?php
require_once "adoptante.php";
require_once "animal.php";

class Adopcion { // Renombrada de Adopciones a Adopcion (singular)
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

    public function __toString() {
        return "ID Adopción: {$this->idAdopcion} - Animal ID: {$this->idAnimal} - Adoptante ID: {$this->idAdoptante} - Fecha: {$this->fechaAdopcion}";
    }
}

class AdopcionesManager { // Nueva clase para gestionar el proceso de adopción
    public function realizarAdopcion($animales, $adoptantes, $db) { // Pasamos $db para guardar la adopción
        echo "\n===== REALIZAR ADOPCIÓN =====\n";

        // Paso 1: Mostrar animales listos para adopción
        echo "Animales listos para adopción:\n";
        $listos = [];
        foreach ($animales as $i => $animal) {
            if (strtolower($animal->getEstado()) === 'listo para adopcion') {
                $listos[] = $animal;
                echo ($i + 1) . ". " . $animal . "\n";
            }
        }

        if (empty($listos)) {
            echo "No hay animales listos para adopción.\n";
            return;
        }

        echo "Seleccione número de animal: ";
        $numAnimalIndex = intval(trim(fgets(STDIN))) - 1;
        $animal = $listos[$numAnimalIndex] ?? null; 

        if (!$animal || strtolower($animal->getEstado()) !== 'listo para adopcion') {
            echo "Selección inválida.\n";
            return;
        }

        // Paso 2: Mostrar adoptantes habilitados
        echo "\nAdoptantes habilitados:\n";
        $habilitados = [];
        foreach ($adoptantes as $i => $adoptante) {
            if ($adoptante->cumpleRequisitos()) {
                $habilitados[] = $adoptante;
               echo (count($habilitados)) . ". " . $adoptante . "\n";
            }
        }

        if (empty($habilitados)) {
            echo "No hay adoptantes habilitados.\n";
            return;
        }

        echo "Seleccione número de adoptante: ";
        $numAdoptanteIndex = intval(trim(fgets(STDIN))) - 1;
        $adoptante = $habilitados[$numAdoptanteIndex] ?? null; // Obtener de $habilitados, no de $adoptantes directamente

        if (!$adoptante || !$adoptante->cumpleRequisitos()) {
            echo "Selección inválida.\n";
            return;
        }

        // Paso 3: Confirmar
        echo "\n¿Confirmar adopción de {$animal->getNombre()} por {$adoptante->getNombre()}? (s/n): ";
        $respuesta = strtolower(trim(fgets(STDIN)));

        if ($respuesta === 's') {
            $animal->setEstado('Adoptado');
            $fechaActual = date('Y-m-d'); // Fecha actual

            // Crear un nuevo objeto Adopcion
            $nuevaAdopcion = new Adopcion($animal->getId(), $adoptante->getId(), $fechaActual);
            $db->agregarAdopcion($nuevaAdopcion); // Agregar a la DB

            echo "\n✅ Adopción realizada con éxito. " . $nuevaAdopcion . "\n";
        } else {
            echo "\n❌ Adopción cancelada.\n";
        }
    }
}