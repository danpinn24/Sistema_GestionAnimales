<?php
require_once "adoptante.php";
require_once "animal.php";

class Adopciones {
    public function realizarAdopcion($animales, $adoptantes) {
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
        $numAnimal = intval(trim(fgets(STDIN))) - 1;
        $animal = $animales[$numAnimal] ?? null;

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
                echo ($i + 1) . ". " . $adoptante . "\n";
            }
        }

        if (empty($habilitados)) {
            echo "No hay adoptantes habilitados.\n";
            return;
        }

        echo "Seleccione número de adoptante: ";
        $numAdoptante = intval(trim(fgets(STDIN))) - 1;
        $adoptante = $adoptantes[$numAdoptante] ?? null;

        if (!$adoptante || !$adoptante->cumpleRequisitos()) {
            echo "Selección inválida.\n";
            return;
        }

        // Paso 3: Confirmar
        echo "\n¿Confirmar adopción de {$animal->getNombre()} por {$adoptante->getNombre()}? (s/n): ";
        $respuesta = strtolower(trim(fgets(STDIN)));

        if ($respuesta === 's') {
            $animal->setEstado('Adoptado');
            echo "\n✅ Adopción realizada con éxito.\n";
        } else {
            echo "\n❌ Adopción cancelada.\n";
        }
    }
}
