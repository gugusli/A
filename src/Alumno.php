<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

class Alumno {
    public static function getById(int $id): array|false {
        $stmt = DB::get()->prepare('SELECT * FROM alumnos WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getByDni(string $dni): array|false {
        $stmt = DB::get()->prepare('SELECT * FROM alumnos WHERE dni = ?');
        $stmt->execute([$dni]);
        return $stmt->fetch();
    }

    public static function getAll(): array {
        return DB::get()->query('SELECT * FROM alumnos ORDER BY nombre ASC')->fetchAll();
    }

    public static function create(string $dni, string $nombre, string $curso): int {
        $stmt = DB::get()->prepare(
            'INSERT INTO alumnos (dni, nombre, curso) VALUES (?, ?, ?) RETURNING id'
        );
        $stmt->execute([$dni, $nombre, $curso]);
        return (int)$stmt->fetchColumn();
    }

    public static function importarCSV(string $rutaArchivo): array {
        $exitos = 0;
        $errores = [];
        $linea = 0;

        if (($fh = fopen($rutaArchivo, 'r')) === false) {
            return ['exitos' => 0, 'errores' => ['No se pudo abrir el archivo']];
        }

        $stmt = DB::get()->prepare(
            'INSERT INTO alumnos (dni, nombre, curso) VALUES (?, ?, ?)
             ON CONFLICT (dni) DO NOTHING'
        );

        while (($row = fgetcsv($fh)) !== false) {
            $linea++;
            if (count($row) < 3) {
                $errores[] = "Línea $linea: formato inválido";
                continue;
            }
            [$dni, $nombre, $curso] = array_map('trim', $row);
            if ($dni === '' || $nombre === '' || $curso === '') {
                $errores[] = "Línea $linea: campos vacíos";
                continue;
            }
            try {
                $stmt->execute([$dni, $nombre, $curso]);
                if ($stmt->rowCount() > 0) $exitos++;
                else $errores[] = "Línea $linea: DNI $dni ya existe";
            } catch (PDOException $e) {
                $errores[] = "Línea $linea: " . $e->getMessage();
            }
        }
        fclose($fh);
        return ['exitos' => $exitos, 'errores' => $errores];
    }

    public static function delete(int $id): void {
        $stmt = DB::get()->prepare('DELETE FROM alumnos WHERE id = ?');
        $stmt->execute([$id]);
    }
}
