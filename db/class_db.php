<?php
class DB {
    private $animales = [];
    private $adoptantes = [];

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

}
