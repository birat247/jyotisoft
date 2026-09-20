 CREATE DATABASE jyotisoft_db;
USE jyotisoft_db;

-- Admin users (no default admin inserted)
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Rest of the tables remain the same...
-- Registrations
CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    course VARCHAR(100) NOT NULL,
    message TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Newsletter subscribers
CREATE TABLE subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Courses
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    duration VARCHAR(50) NOT NULL,
    fee VARCHAR(50) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    image_path VARCHAR(255)
);

-- Insert sample courses
INSERT INTO courses (title, duration, fee, category, description, image_path) VALUES
('Full‑Stack Web Development', '3 Months', 'NPR 15,000', 'web', 'React, Node, MongoDB, Express', './images/webdevelopment.jpg'),
('Python Programming', '2 Months', 'NPR 12,000', 'programming', 'Core Python, OOP, Django', './images/Python.jpg'),
('Flutter App Development', '3 Months', 'NPR 18,000', 'mobile', 'Dart, Firebase, cross-platform', './images/flutte.jpg'),
('Data Science & AI', '4 Months', 'NPR 20,000', 'data', 'Pandas, ML, Scikit-learn', './images/DataScience.jpg'),
('Cybersecurity', '4 Months', 'NPR 22,000', 'security', 'Ethical hacking, Kali Linux', './images/Cybersecurity.jpg');

-- Trainers
CREATE TABLE trainers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    description TEXT,
    image_path VARCHAR(255)
);

INSERT INTO trainers (name, role, description, image_path) VALUES
('Birat Tripathee', 'Senior Full-Stack Developer', '10+ years exp, MERN & Cloud', './images/birat.jpg'),
('Rajan Gautam', 'Data Science & AI Specialist', 'PhD, 8+ years ML', './images/rajan.png'),
('Sandesh Dahal', 'Mobile Innovation Dir.', 'Flutter & iOS, ex-Uber', './images/sandesh.jpg');

-- Testimonials
CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    text TEXT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO testimonials (name, text, rating) VALUES
('Birat Tripathee', 'Jyotisoft changed my career! I''m now a Lead Dev at fintech firm.', 5),
('Sneha Shrestha', 'Instructors are super supportive. Got placed within 2 months.', 5),
('Aryan Pokhrel', 'Live projects gave me real confidence. Placement team is awesome!', 5);