<?php
class Animal {
    private static $ultimoId = 0;
    private $id;
    private $nombre;
    private $especie;
    private $raza;
    private $edad;
    private $sexo;
    private $caracteristicasFisicas;
    private $fechaIngreso;
    private $estado;

    public function __construct($nombre, $especie, $raza, $edad, $sexo, $caracteristicasFisicas, $fechaIngreso, $estado) {
        self::$ultimoId++;
        $this->id = self::$ultimoId;

        $this->nombre = $nombre;
        $this->especie = $especie;
        $this->raza = $raza;
        $this->edad = $edad;
        $this->sexo = $sexo;
        $this->caracteristicasFisicas = $caracteristicasFisicas;
        $this->fechaIngreso = $fechaIngreso;
        $this->estado = $estado;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getEspecie() {
        return $this->especie;
    }

    public function getRaza() {
        return $this->raza;
    }

    public function getEdad() {
        return $this->edad;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function getCaracteristicasFisicas() {
        return $this->caracteristicasFisicas;
    }

    public function getFechaIngreso() {
        return $this->fechaIngreso;
    }

    public function getEstado() {
        return $this->estado;
    }

    // Setters
    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    // En clase Animal, debajo de los setters existentes:

public function setEspecie($especie) {
    $this->especie = $especie;
}

public function setRaza($raza) {
    $this->raza = $raza;
}

public function setEdad($edad) {
    $this->edad = $edad;
}

public function setSexo($sexo) {
    $this->sexo = $sexo;
}

public function setCaracteristicasFisicas($caracteristicasFisicas) {
    $this->caracteristicasFisicas = $caracteristicasFisicas;
}

public function setFechaIngreso($fechaIngreso) {
    $this->fechaIngreso = $fechaIngreso;
}


    // Método para mostrar información del animal
    public function __toString() {
        return "ID: {$this->id} - Nombre: {$this->nombre} - Especie: {$this->especie} -  Fecha Ingreso: {$this->fechaIngreso} - Estado: {$this->estado}";
    }
}


