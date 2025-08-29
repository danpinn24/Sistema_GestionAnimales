<?php
class DB {
    private $animales = [];
    private $adoptantes = [];
    private $adopciones = [];

    public function agregarAnimal($animal) {
        $this->animales[] = $animal;
    }

    public function getAnimales() {
        return $this->animales;
    }

    public function agregarAdoptante($adoptante) {
        $this->adoptantes[] = $adoptante;
    }

    public function getAdoptantes() {
        return $this->adoptantes;
    }

    public function agregarAdopcion($adopcion) {
        $this->adopciones[] = $adopcion;
    }

    public function getAdopciones() {
        return $this->adopciones;
    }

    // Nuevos métodos de búsqueda por ID
    public function buscarAdopcionPorId($id) {
        foreach ($this->adopciones as $adopcion) {
            if ($adopcion->getIdAdopcion() === $id) {
                return $adopcion;
            }
        }
        return null;
    }

    public function buscarAnimalPorId($id) {
        foreach ($this->animales as $animal) {
            if ($animal->getId() === $id) {
                return $animal;
            }
        }
        return null;
    }

    public function buscarAdoptantePorId($id) {
        foreach ($this->adoptantes as $adoptante) {
            if ($adoptante->getId() === $id) {
                return $adoptante;
            }
        }
        return null;
    }

    public function modificarAnimalPorId($id, $nuevosDatos) {
    foreach ($this->animales as $animal) {
        if ($animal->getId() == $id) {

            if (isset($nuevosDatos['nombre'])) {
                $animal->setNombre($nuevosDatos['nombre']);
            }

            if (isset($nuevosDatos['especie'])) {
                $animal->setEspecie($nuevosDatos['especie']);
            }

            if (isset($nuevosDatos['raza'])) {
                $animal->setRaza($nuevosDatos['raza']);
            }

            if (isset($nuevosDatos['edad'])) {
                $animal->setEdad($nuevosDatos['edad']);
            }

            if (isset($nuevosDatos['sexo'])) {
                $animal->setSexo($nuevosDatos['sexo']);
            }

            if (isset($nuevosDatos['caracteristicasFisicas'])) {
                $animal->setCaracteristicasFisicas($nuevosDatos['caracteristicasFisicas']);
            }

            if (isset($nuevosDatos['estado'])) {
                $animal->setEstado($nuevosDatos['estado']);
            }

            return true;
        }
    }
    return false;
}

public function eliminarAnimal($indice) {
    if (isset($this->animales[$indice])) {
        array_splice($this->animales, $indice, 1);
    }
}

public function modificarAdoptantePorId($id, $nuevosDatos) {
    foreach ($this->adoptantes as $adoptante) {
        if ($adoptante->getId() == $id) {
            if (isset($nuevosDatos['nombre'])) {
                $adoptante->setNombre($nuevosDatos['nombre']);
            }
            if (isset($nuevosDatos['dni'])) {
                $adoptante->setDni($nuevosDatos['dni']);
            }
            if (isset($nuevosDatos['direccion'])) {
                $adoptante->setDireccion($nuevosDatos['direccion']);
            }
            if (isset($nuevosDatos['telefono'])) {
                $adoptante->setTelefono($nuevosDatos['telefono']);
            }
            if (isset($nuevosDatos['email'])) {
                $adoptante->setEmail($nuevosDatos['email']);
            }
            if (isset($nuevosDatos['requisitosCumplidos'])) {
                $adoptante->setRequisitosCumplidos($nuevosDatos['requisitosCumplidos']);
            }
            return true;
        }
    }
    return false;
}

public function eliminarAdoptante($indice) {
    if (isset($this->adoptantes[$indice])) {
        array_splice($this->adoptantes, $indice, 1);
    }
}

public function eliminarAdopcion($indice) {
    if (isset($this->adopciones[$indice])) {
        array_splice($this->adopciones, $indice, 1);
    }
}


public function modificarAdopcionPorId($id, $nuevosDatos) {
    foreach ($this->adopciones as $adopcion) {
        if ($adopcion->getIdAdopcion() == $id) {
            if (isset($nuevosDatos['animalId']) && $nuevosDatos['animalId'] !== '') {
                $adopcion->setAnimalId($nuevosDatos['animalId']);
            }

            if (isset($nuevosDatos['adoptanteId']) && $nuevosDatos['adoptanteId'] !== '') {
                $adopcion->setAdoptanteId($nuevosDatos['adoptanteId']);
            }

            if (isset($nuevosDatos['fecha']) && $nuevosDatos['fecha'] !== '') {
                $adopcion->setFechaAdopcion($nuevosDatos['fecha']);
            }

            return true;
        }
    }
    return false;
}
}