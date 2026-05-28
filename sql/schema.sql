-- Sistema de Notificación de Previas - E.E.S.T. N°5 Berazategui
-- DDL completo para Neon (PostgreSQL)

CREATE TABLE alumnos (
    id SERIAL PRIMARY KEY,
    dni VARCHAR(10) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    curso VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE preceptoras (
    id SERIAL PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE materias (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE previas (
    id SERIAL PRIMARY KEY,
    alumno_id INT REFERENCES alumnos(id) ON DELETE CASCADE,
    materia_id INT REFERENCES materias(id),
    fecha DATE NOT NULL,
    horario TIME NOT NULL,
    aula VARCHAR(20) NOT NULL,
    estado VARCHAR(10) DEFAULT 'Pendiente' CHECK (estado IN ('Pendiente','Aprobada','Ausente')),
    push_enviado BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE confirmaciones_lectura (
    id SERIAL PRIMARY KEY,
    previa_id INT REFERENCES previas(id) ON DELETE CASCADE,
    alumno_id INT REFERENCES alumnos(id) ON DELETE CASCADE,
    confirmado_at TIMESTAMP DEFAULT NOW(),
    UNIQUE(previa_id, alumno_id)
);

CREATE TABLE push_subscriptions (
    id SERIAL PRIMARY KEY,
    alumno_id INT REFERENCES alumnos(id) ON DELETE CASCADE,
    endpoint TEXT NOT NULL,
    p256dh TEXT NOT NULL,
    auth TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT NOW(),
    UNIQUE(alumno_id, endpoint)
);

-- Seed: preceptora por defecto (usuario: preceptora, contraseña: admin123)
-- Hash generado con: password_hash('admin123', PASSWORD_BCRYPT, ['cost'=>12])
INSERT INTO preceptoras (usuario, password_hash)
VALUES ('preceptora', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Seed: materias
INSERT INTO materias (nombre) VALUES
('Matemática'),('Lengua'),('Historia'),('Física'),('Química'),
('Inglés'),('Programación'),('Electrónica'),('Ed. Física');
