CREATE DATABASE IF NOT EXISTS vistar_technology CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vistar_technology;

CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL
);
CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(120) NOT NULL,
  description TEXT,
  image VARCHAR(255) DEFAULT '',
  status ENUM('active','hidden') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  category VARCHAR(80) DEFAULT '',
  client_name VARCHAR(120) DEFAULT '',
  description TEXT,
  url VARCHAR(255) DEFAULT '',
  image VARCHAR(255) DEFAULT '',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE clients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  logo VARCHAR(255) DEFAULT '',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  duration VARCHAR(60) DEFAULT '',
  fees VARCHAR(60) DEFAULT '',
  description TEXT,
  image VARCHAR(255) DEFAULT '',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE jobs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  type ENUM('Full-time','Part-time','Internship') NOT NULL DEFAULT 'Full-time',
  location VARCHAR(100) DEFAULT '',
  description TEXT,
  last_date DATE NULL,
  status ENUM('open','closed') NOT NULL DEFAULT 'open',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE enquiries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type ENUM('project','course','job','internship','general') NOT NULL DEFAULT 'general',
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(20) DEFAULT '',
  subject VARCHAR(150) DEFAULT '',
  message TEXT NOT NULL,
  status ENUM('new','contacted','closed') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE settings (
  skey VARCHAR(50) PRIMARY KEY,
  svalue TEXT
);

-- Default admin: username "admin", password "password". Change it in Admin > Settings right after first login.
INSERT INTO admins (username, password_hash) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO settings (skey, svalue) VALUES
('site_name','Vistar Technology'),
('tagline','Websites, software and training for growing businesses.'),
('hero_title','We build software that your business actually uses.'),
('hero_text','Websites, web apps and training from a small team that answers its phone.'),
('about_text','Tell your story here: who you are, what you build, and who you build it for.'),
('address','Your office address'),('phone','+91 00000 00000'),('email','info@example.com'),('hours','Mon-Sat, 10am-6pm');

INSERT INTO services (title, description) VALUES
('Website development','Fast, mobile-friendly business websites with an admin panel you can manage yourself.'),
('Web applications','Custom portals, dashboards and management systems built on PHP and MySQL.'),
('Training and internships','Hands-on courses and internships for students and job seekers.');
