-- ============================================================
-- Online Art Gallery - Database Schema + Seed Data
-- Fundamentals of Web Development - Final Project
-- ============================================================

DROP DATABASE IF EXISTS art_gallery;
CREATE DATABASE art_gallery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE art_gallery;

-- -----------------------------------------------------------
-- Users: registration & login (Chapter 12 - superglobals, Chapter 13 - OOP)
-- Passwords are stored using PHP password_hash() (bcrypt).
-- Below are pre-hashed values for seed accounts:
--   admin / admin123  -> role admin
--   demo  / demo123   -> role user
-- -----------------------------------------------------------
CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  UNIQUE NOT NULL,
    email      VARCHAR(100) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------------
-- Categories: admin-managed grouping for artworks
-- -----------------------------------------------------------
CREATE TABLE categories (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50) NOT NULL,
    description TEXT
);

-- -----------------------------------------------------------
-- Artists: admin-managed creators
-- -----------------------------------------------------------
CREATE TABLE artists (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    first_name  VARCHAR(50),
    last_name   VARCHAR(50) NOT NULL,
    birth_year  INT,
    death_year  INT,
    nationality VARCHAR(50),
    biography   TEXT
);

-- -----------------------------------------------------------
-- Artworks: the "products" the admin manages (CRUD)
-- -----------------------------------------------------------
CREATE TABLE artworks (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    title          VARCHAR(200) NOT NULL,
    artist_id      INT,
    category_id    INT,
    year           INT,
    medium         VARCHAR(100),
    dimensions     VARCHAR(100),
    description    TEXT,
    price          DECIMAL(14,2),
    image_filename VARCHAR(255),
    is_featured    BOOLEAN DEFAULT FALSE,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (artist_id)   REFERENCES artists(id)   ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- -----------------------------------------------------------
-- Favorites: many-to-many user <-> artwork
-- -----------------------------------------------------------
CREATE TABLE favorites (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT,
    artwork_id INT,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (artwork_id) REFERENCES artworks(id) ON DELETE CASCADE
);

-- -----------------------------------------------------------
-- Reviews: user ratings + comments on artworks
-- -----------------------------------------------------------
CREATE TABLE reviews (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT,
    artwork_id INT,
    rating     INT,
    comment    TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (artwork_id) REFERENCES artworks(id) ON DELETE CASCADE
);

-- ============================================================
-- SEED DATA
-- ============================================================

-- Users (passwords: admin123 / demo123)
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@gallery.com', '$2y$10$N9qo8uLOickgx2ZMRZoMy.MrqKQc0VhDqt/ABKEtP1cLepoZJQv.y', 'admin'),
('demo',  'demo@gallery.com',  '$2y$10$N9qo8uLOickgx2ZMRZoMy.MrqKQc0VhDqt/ABKEtP1cLepoZJQv.y', 'user');

-- Categories
INSERT INTO categories (name, description) VALUES
('Renaissance',  'Art from the 14th-17th century revival of classical learning.'),
('Baroque',      'Dramatic, ornate European art of the 17th and 18th centuries.'),
('Impressionism','Late 19th-century movement focused on light and color.'),
('Modern',       'Art from the late 19th to mid 20th century.'),
('Abstract',     'Non-representational art emphasizing form and color.');

-- Artists
INSERT INTO artists (first_name, last_name, birth_year, death_year, nationality, biography) VALUES
('Leonardo',   'da Vinci',     1452, 1519, 'Italian',   'Polymath of the Renaissance; painter, inventor, scientist.'),
('Michelangelo','Buonarroti',  1475, 1564, 'Italian',   'Sculptor, painter, architect of the High Renaissance.'),
('Rembrandt',  'van Rijn',     1606, 1669, 'Dutch',     'Master of light and shadow in the Dutch Golden Age.'),
('Claude',     'Monet',        1840, 1926, 'French',    'Founder of French Impressionist painting.'),
('Vincent',    'van Gogh',     1853, 1890, 'Dutch',     'Post-Impressionist known for bold color and emotion.'),
('Pablo',      'Picasso',      1881, 1973, 'Spanish',   'Co-founder of Cubism and one of the most influential artists of the 20th century.'),
('Salvador',   'Dali',         1904, 1989, 'Spanish',   'Surrealist known for striking and bizarre images.'),
('Frida',      'Kahlo',        1907, 1954, 'Mexican',   'Painter known for self-portraits and works inspired by nature.');

-- Artworks (image_filename uses real painting names — download these images)
INSERT INTO artworks (title, artist_id, category_id, year, medium, dimensions, description, price, image_filename, is_featured) VALUES
('Mona Lisa',                 1, 1, 1503, 'Oil on wood',     '77 x 53 cm', 'A portrait of enigmatic smile, the most famous painting in the world.', 8500000.00, 'mona-lisa.jpg',  TRUE),
('The Last Supper',           1, 1, 1495, 'Tempera on stone', '460 x 880 cm','Late 15th-century mural depicting the last meal of Jesus.', 9999999.00, 'the-last-supper.jpg', TRUE),
('David',                     2, 1, 1504, 'Marble',           '517 cm',     'Masterpiece of Renaissance sculpture.',                     2000000.00, 'david.jpg',  TRUE),
('The Night Watch',           3, 2, 1642, 'Oil on canvas',    '363 x 437 cm','Famous group portrait with dramatic lighting.',              9000000.00, 'the-night-watch.jpg',  FALSE),
('Water Lilies',              4, 3, 1906, 'Oil on canvas',    '89.9 x 94.1 cm','Series of paintings of Monet''s flower garden.',          5400000.00, 'water-lilies.jpg',  TRUE),
('Impression, Sunrise',       4, 3, 1872, 'Oil on canvas',    '48 x 63 cm', 'The painting that gave its name to Impressionism.',           3000000.00, 'impression-sunrise.jpg',  FALSE),
('The Starry Night',          5, 4, 1889, 'Oil on canvas',    '74 x 92 cm', 'Van Gogh''s swirling night sky over a village.',             10000000.00, 'the-starry-night.jpg',  TRUE),
('Sunflowers',                5, 4, 1888, 'Oil on canvas',    '92.1 x 73 cm','Still life series of vibrant yellow flowers.',               8500000.00, 'sunflowers.jpg',  FALSE),
('Les Demoiselles d''Avignon',6, 4, 1907, 'Oil on canvas',    '244 x 234 cm','Cubist masterpiece depicting five figures.',                 11000000.00, 'demoiselles-avignon.jpg',  TRUE),
('Guernica',                  6, 4, 1937, 'Oil on canvas',    '349 x 776 cm','Powerful anti-war mural in black, white, and gray.',         20000000.00, 'guernica.jpg',  FALSE),
('The Persistence of Memory', 7, 5, 1931, 'Oil on canvas',    '24 x 33 cm', 'Surrealist painting of melting clocks.',                     4000000.00, 'persistence-of-memory.jpg',  FALSE),
('The Two Fridas',            8, 5, 1939, 'Oil on canvas',    '173.5 x 173 cm','Double self-portrait expressing inner duality.',          20000000.00, 'the-two-fridas.jpg',  FALSE);

-- Favorites for demo user (user_id = 2)
INSERT INTO favorites (user_id, artwork_id) VALUES
(2, 1), (2, 5), (2, 7);

-- Reviews for demo user
INSERT INTO reviews (user_id, artwork_id, rating, comment) VALUES
(2, 1, 5, 'Absolutely mesmerizing in person.'),
(2, 7, 5, 'The colors are so much more vivid than in photos.'),
(2, 5, 4, 'A beautiful study of light on water.');
