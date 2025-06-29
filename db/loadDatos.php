<?php
require_once('./db/class_db.php');
require_once('./db/class_animales.php');
require_once('./db/class_adoptante.php');

$db = new DB();

// Animales de prueba
$db->agregarAnimal(new Animal("Fido", "Perro", "Labrador", 3, "Macho", "Color dorado", "2025-06-20", "listo para adopcion"));
$db->agregarAnimal(new Animal("Luna", "Gato", "Siames", 2, "Hembra", "Ojos celestes", "2025-06-22", "listo para adopcion"));
$db->agregarAnimal(new Animal("Rocky", "Perro", "Cruza", 4, "Macho", "Negro y blanco", "2025-06-18", "en tratamiento"));

// Adoptantes de prueba
$db->agregarAdoptante(new Adoptante("Juan Pérez", "12345678", "Av. Siempre Viva 123", "1234-5678", "juan@mail.com", true));
$db->agregarAdoptante(new Adoptante("Ana Gómez", "87654321", "Calle Falsa 456", "4321-8765", "ana@mail.com", false));
$db->agregarAdoptante(new Adoptante("Laura Díaz", "11223344", "San Martín 789", "5678-1234", "laura@mail.com", true));
