<?php

class Adoptante {
    private static $ultimoId = 0;
    private $id;
    private $nombre;
    private $dni;
    private $direccion;
    private $telefono;
    private $email;
    private $requisitosCumplidos;

    public function __construct($nombre, $dni, $direccion, $telefono, $email, $requisitosCumplidos) {
        self::$ultimoId++;
        $this->id = self::$ultimoId;

        $this->nombre = $nombre;
        $this->dni = $dni;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->requisitosCumplidos = $requisitosCumplidos;
    }

    // Método original tuyo, muy claro
    public function mostrarPerfilLimpio() {
        echo "===== Perfil del Adoptante =====" . PHP_EOL;
        echo "ID: " . $this->id . PHP_EOL;
        echo "Nombre: " . $this->nombre . PHP_EOL;
        echo "DNI: " . $this->dni . PHP_EOL;
        echo "Dirección: " . $this->direccion . PHP_EOL;
        echo "Teléfono: " . $this->telefono . PHP_EOL;
        echo "Email: " . $this->email . PHP_EOL;
        echo "¿Requisitos cumplidos?: " . ($this->requisitosCumplidos ? 'Sí' : 'No') . PHP_EOL;
        echo PHP_EOL;
    }

    // Método mágico para resumen rápido
    public function __toString() {
        return "ID: {$this->id} - Nombre: {$this->nombre} - Requisitos: " . ($this->requisitosCumplidos ? 'Sí' : 'No');
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDni() {
        return $this->dni;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getEmail() {
        return $this->email;
    }

    public function cumpleRequisitos() {
        return $this->requisitosCumplidos;
    }

    // Setters
    public function setRequisitosCumplidos($valor) {
        $this->requisitosCumplidos = $valor;
    }
}
