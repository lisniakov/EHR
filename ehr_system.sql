-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2025 at 07:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ehr_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `username`, `password`, `email`, `full_name`, `created_at`) VALUES
(7, 'Artem', '$2y$10$aVH14X405HpYiIyVQDYQjOLSsPUacl4h1jUKjWELGfgWQeCrkC6NW', 'qwerty123@gmail.com', 'Artem Lisniakov', '2025-01-20 18:47:14');

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `chief_complaint` text DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `current_medications` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `blood_pressure` varchar(20) DEFAULT NULL,
  `temperature` decimal(4,2) DEFAULT NULL,
  `immunization_status` text DEFAULT NULL,
  `lab_results` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `treatment_plan` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`id`, `patient_id`, `visit_date`, `chief_complaint`, `medical_history`, `current_medications`, `allergies`, `height`, `weight`, `blood_pressure`, `temperature`, `immunization_status`, `lab_results`, `diagnosis`, `treatment_plan`, `notes`, `created_at`) VALUES
(10, 9, '2025-01-18', 'Persistent headache and dizziness', 'History of migraines and hypertension', 'Amlodipine 5 mg, Ibuprofen as needed', 'Penicillin', 170.00, 72.00, '130/85', 37.20, 'Up to date, including seasonal flu vaccine', 'Elevated cholesterol (LDL: 160 mg/dL), normal blood glucose', 'Tension headache exacerbated by hypertension', 'Adjust antihypertensive medication\r\nPrescribe acetaminophen for pain relief\r\nEncourage stress management and regular exercise', 'Follow-up in 2 weeks to reassess blood pressure and headache severity.', '2025-01-20 18:54:10'),
(11, 6, '2025-01-17', 'Shortness of breath and persistent cough', 'Asthma diagnosed at age 12', 'Albuterol inhaler, Montelukast 10 mg', 'None', 175.00, 68.00, '115/75', 37.80, 'Up to date; COVID-19 booster received 3 months ago', 'Chest X-ray: Normal\r\nCBC: Mildly elevated eosinophils', 'Asthma exacerbation', 'Start a short course of oral corticosteroids (Prednisone 30 mg for 5 days)\r\nContinue using the Albuterol inhaler as needed\r\nRecommend avoiding known triggers', 'Patient advised to monitor symptoms and return if there is no improvement within a week.', '2025-01-21 14:29:15'),
(12, 9, '2025-01-23', 'frgreg', 'ASDFGHJKL', '', '', 0.00, 0.00, '', 0.00, '', '', 'regregre', 'regerg', 'ASDFGHJKL:', '2025-01-23 10:01:50');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('M','F','Other') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `doctor_id`, `first_name`, `last_name`, `date_of_birth`, `gender`, `created_at`) VALUES
(6, 7, 'Alexander', 'Thompson', '1991-06-15', 'M', '2025-01-20 18:49:09'),
(7, 7, 'Emily', 'Johnson', '1986-05-22', 'F', '2025-01-20 18:50:03'),
(8, 7, 'Michael', 'Williams', '2000-12-08', 'M', '2025-01-20 18:50:49'),
(9, 7, 'Sophia', 'Brown', '1977-09-25', 'F', '2025-01-20 18:51:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `medical_records_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
