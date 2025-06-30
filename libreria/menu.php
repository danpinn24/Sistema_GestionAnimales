<?php
require_once('opcion.php');

class Menu {
    private $titulo;
    private $opciones = [];

    function __construct($titulo) {
        $this->titulo = $titulo;
    }

    function addOpcion($opcion) {
        $this->opciones[] = $opcion;
    }

    private function mostrar() {
        system('clear');
        mostrar($this->titulo);
        mostrar(str_repeat('-', strlen($this->titulo)));

        foreach ($this->opciones as $key => $opcion) {
            mostrar("\033[1;32m{$key}\033[0m - {$opcion->getNombre()}");
        }
    }

    function elegir() {
        $this->mostrar();
        do {
            $entrada = trim(fgets(STDIN));
        } while ($entrada === "");

        return $this->opciones[$entrada];
    }

    
    static function getMenuPrincipal() {
        $menu = new Menu("Menú Principal");
        $menu->addOpcion(new Opcion("Salir", "salir"));
        $menu->addOpcion(new Opcion("Gestión de Animales", "menuAnimales"));
        $menu->addOpcion(new Opcion("Gestión de Adoptantes", "menuAdoptantes"));
        $menu->addOpcion(new Opcion("Gestión de Adopciones", "menuAdopciones"));
        return $menu;
    }

    static function getMenuAnimales() {
        $menu = new Menu("Gestión de Animales");
        $menu->addOpcion(new Opcion("Volver", "salir"));
        $menu->addOpcion(new Opcion("Registrar nuevo animal", "registrarAnimal"));
        $menu->addOpcion(new Opcion("Modificar un animal", "modificarAnimal"));
        $menu->addOpcion(new Opcion("Borrar un animal", "borrarAnimal"));
        $menu->addOpcion(new Opcion("Ver lista de animales", "listarAnimales"));
        $menu->addOpcion(new Opcion("Ver detalles de un animal", "verDetallesAnimal"));
        return $menu;
    }

    static function getMenuAdoptantes() {
        $menu = new Menu("Gestión de Adoptantes");
        $menu->addOpcion(new Opcion("Volver", "salir"));
        $menu->addOpcion(new Opcion("Registrar nuevo adoptante", "registrarAdoptante"));
        $menu->addOpcion(new Opcion("Modificar un adoptante", "modificarAdoptante"));
        $menu->addOpcion(new Opcion("Borrar un adoptante", "borrarAdoptante"));
        $menu->addOpcion(new Opcion("Ver lista de adoptantes", "listarAdoptantes"));
        $menu->addOpcion(new Opcion("Ver detalles de un adoptante", "verDetallesAdoptante"));
        return $menu;
    }

    static function getMenuAdopciones() {
        $menu = new Menu("Gestión de Adopciones");
        $menu->addOpcion(new Opcion("Volver", "salir"));
        $menu->addOpcion(new Opcion("Ver animales disponibles", "verAnimalesDisponibles"));
        $menu->addOpcion(new Opcion("Ver adoptantes con requisitos cumplidos", "verAdoptantesHabilitados"));
        $menu->addOpcion(new Opcion("Realizar una adopción", "realizarAdopcion"));
        $menu->addOpcion(new Opcion("Ver historial de adopciones", "verHistorialAdopciones"));
        return $menu;
    }
}

