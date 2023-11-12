-- Create the Patient Model
CREATE TABLE Patient (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    date_of_birth DATE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Create the Doctor Model
CREATE TABLE Doctor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    date_of_birth DATE,
    specialization VARCHAR(255),
    biography TEXT,
    image VARCHAR(255),
    password VARCHAR(255) NOT NULL
);

-- Create the Appointment Model with Additional Fields
CREATE TABLE Appointment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    doctor_id INT,
    scheduled_date DATE,
    scheduled_time TIME,
    status VARCHAR(20) NOT NULL DEFAULT 'Scheduled',
    seen BOOLEAN NOT NULL DEFAULT 0, -- 0 for unseen, 1 for seen
    comments TEXT,
    consultation_type VARCHAR(255),
    FOREIGN KEY (patient_id) REFERENCES Patient(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES Doctor(id) ON DELETE CASCADE
);