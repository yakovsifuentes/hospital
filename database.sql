CREATE DATABASE IF NOT EXISTS hospital;
USE hospital;

CREATE TABLE users(
id 				int(255) auto_increment not null,
name			varchar(255),
password		varchar(255),
rol				varchar(20),
created_at		timestamp,
updated_at		timestamp,
remember_token	varchar(255),
CONSTRAINT	pk_user PRIMARY KEY(id)
)ENGINE = InnoDb;


CREATE TABLE specialtys(
id 		int(255) auto_increment not null,
name	varchar(255),
created_at varchar(255),
updated_at varchar(255),
CONSTRAINT pk_special PRIMARY KEY(id)
)ENGINE = InnoDb;


CREATE TABLE patients(
id 		int(255) auto_increment not null,
name	varchar(255),
rfc		varchar(255),
phone	varchar(255),
origin	varchar(255),
created_at	timestamp,
updated_at	timestamp,
CONSTRAINT pk_patient PRIMARY KEY(id)
)ENGINE=InnoDb;


CREATE TABLE doctors(
id 				int(255) auto_increment not null,
name			varchar(255),
id_specialty	int(255),
turn			varchar(255),
patients_sub		int(255),
weekend			varchar(20),
status			varchar(20),
created_at		timestamp,
updated_at		timestamp,
CONSTRAINT pk_doctors PRIMARY KEY(id),
CONSTRAINT fk_doctors_speciality FOREIGN KEY(id_specialty) REFERENCES specialtys(id)
)ENGINE=InnoDb;


CREATE TABLE appointments(
id 				int(255) auto_increment not null,
id_patient		int(255) not null,
id_doctor		int(255) not null,
id_user			int(255) not null,
date_appointment	timestamp,
date_actual			timestamp,
specialty		varchar(255),
type_patient    varchar(255),
status			varchar(20),
created_at		timestamp,
updated_at		timestamp,
CONSTRAINT pk_app PRIMARY KEY(id),
CONSTRAINT fk_appointments_doctor FOREIGN KEY(id_doctor) REFERENCES doctors(id),
CONSTRAINT fk_appointments_patients	FOREIGN KEY(id_patient) REFERENCES patients(id),
CONSTRAINT fk_appointments_user FOREIGN KEY(id_user) REFERENCES users(id)
)ENGINE=InnoDb;



CREATE TABLE festival(
id          int(255) auto_increment not null,
date_festival   timestamp,
created_at		timestamp,
updated_at		timestamp,
CONSTRAINT pk_festival PRIMARY KEY(id)
)ENGINE=InnoDb;