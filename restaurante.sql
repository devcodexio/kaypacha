-- KAY-PACHA RESTAURANTE - SCHEMA POSTGRESQL PRO
BEGIN;

-- 1. ROLES
CREATE TABLE IF NOT EXISTS roles (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL
);

INSERT INTO roles (id, nombre) VALUES (1, 'admin'), (2, 'empleado'), (3, 'cliente')
ON CONFLICT (id) DO NOTHING;

-- 2. USUARIOS
CREATE TABLE IF NOT EXISTS usuarios (
  id SERIAL PRIMARY KEY,
  rol_id INTEGER NOT NULL REFERENCES roles(id),
  nombre VARCHAR(150) NOT NULL,
  correo VARCHAR(150) NOT NULL UNIQUE,
  contraseña VARCHAR(255) NOT NULL,
  telefono VARCHAR(20),
  direccion VARCHAR(255),
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin por defecto: admin@correo.com / admin123
INSERT INTO usuarios (rol_id, nombre, correo, contraseña) VALUES 
(1, 'Administrador Pro', 'admin@correo.com', '$2y$10$APGpHzwWQO1qaJn8gJAFIe1bEorKEeXWCu2ytPBa/LhDZuyffb0Vy')
ON CONFLICT (correo) DO NOTHING;

-- 3. CATEGORIAS Y PLATOS
CREATE TABLE IF NOT EXISTS categorias_platos (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL
);

INSERT INTO categorias_platos (nombre) VALUES ('Entradas'), ('Platos Marinos'), ('Carnes'), ('Bebidas')
ON CONFLICT DO NOTHING;

CREATE TABLE IF NOT EXISTS platos (
  id SERIAL PRIMARY KEY,
  categoria_id INTEGER NOT NULL REFERENCES categorias_platos(id),
  nombre VARCHAR(150) NOT NULL,
  descripcion TEXT,
  precio DECIMAL(10,2) NOT NULL,
  imagen VARCHAR(255),
  disponible SMALLINT DEFAULT 1
);

-- 4. MESAS (SISTEMA DE CROQUIS)
CREATE TABLE IF NOT EXISTS mesas (
  id SERIAL PRIMARY KEY,
  numero_mesa INTEGER NOT NULL,
  capacidad INTEGER NOT NULL,
  estado VARCHAR(20) DEFAULT 'disponible',
  pos_top VARCHAR(20) DEFAULT '100px',
  pos_left VARCHAR(20) DEFAULT '100px',
  activo SMALLINT DEFAULT 1
);

INSERT INTO mesas (numero_mesa, capacidad, estado, pos_top, pos_left) VALUES 
(1, 4, 'disponible', '50px', '50px'),
(2, 4, 'disponible', '50px', '200px'),
(3, 8, 'disponible', '200px', '125px')
ON CONFLICT DO NOTHING;

-- 5. RESERVAS
CREATE TABLE IF NOT EXISTS reservas (
  id SERIAL PRIMARY KEY,
  usuario_id INTEGER NOT NULL REFERENCES usuarios(id),
  mesa_id INTEGER NOT NULL REFERENCES mesas(id),
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  cantidad_personas INTEGER NOT NULL,
  estado VARCHAR(20) DEFAULT 'pendiente',
  metodo_pago VARCHAR(50),
  pagado BOOLEAN DEFAULT FALSE,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 6. PAGOS STRIPE
CREATE TABLE IF NOT EXISTS pagos_stripe (
  id SERIAL PRIMARY KEY,
  reserva_id INTEGER NOT NULL REFERENCES reservas(id) ON DELETE CASCADE,
  stripe_id VARCHAR(100) NOT NULL,
  monto DECIMAL(10,2) NOT NULL,
  moneda VARCHAR(10) DEFAULT 'USD',
  estado VARCHAR(50) DEFAULT 'succeeded',
  fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 7. TESTIMONIOS (CON MODERACION)
CREATE TABLE IF NOT EXISTS testimonios (
  id SERIAL PRIMARY KEY,
  usuario_id INTEGER NOT NULL REFERENCES usuarios(id),
  mensaje VARCHAR(500) NOT NULL,
  calificacion INTEGER DEFAULT 5,
  estado VARCHAR(20) DEFAULT 'pendiente',
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 8. NOSOTROS
CREATE TABLE IF NOT EXISTS nosotros (
  id SERIAL PRIMARY KEY,
  tipo VARCHAR(50) NOT NULL,
  titulo VARCHAR(150),
  descripcion TEXT NOT NULL
);

COMMIT;
