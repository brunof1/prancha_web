
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'usuario') NOT NULL DEFAULT 'admin',
    token_recuperacao VARCHAR(255) DEFAULT NULL
);

CREATE TABLE pranchas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT,
    criado_por INT,
    FOREIGN KEY (criado_por) REFERENCES usuarios(id)
);

CREATE TABLE cartoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prancha_id INT NOT NULL,
    texto VARCHAR(255),
    imagem VARCHAR(255),
    FOREIGN KEY (prancha_id) REFERENCES pranchas(id)
);
