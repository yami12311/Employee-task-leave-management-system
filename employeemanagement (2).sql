-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2025 at 07:58 AM
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
-- Database: `employeemanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `role`, `contact_number`) VALUES
(1, 'admin', '$2y$10$VdokqSpY8gtZI6a23/ZUt.NHpT65FYczSFY9.C9u.UenJWn7LK/le', 'admin@example.com', '', '1234567890');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `work_hours` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `employee_id`, `attendance_date`, `check_in_time`, `check_out_time`, `status`, `work_hours`, `remarks`) VALUES
(1, 1, '2025-02-02', '10:30:00', '16:30:00', 'Present', NULL, NULL),
(2, 1, '2025-02-06', '10:30:00', '16:30:00', '0', '00:00:00', NULL),
(3, 1, '2025-02-06', '10:30:00', '16:30:00', '0', '00:00:00', NULL),
(4, 1, '2025-02-06', '10:30:00', '16:30:00', '0', '00:00:00', NULL),
(5, 1, '2025-02-06', '10:30:00', '16:30:00', '0', '00:00:00', ''),
(6, 1, '2025-02-06', '10:30:00', '16:30:00', '0', '00:00:00', ''),
(7, 1, '2025-02-06', '10:30:00', '16:30:00', '0', '00:00:00', ''),
(8, 1, '2025-02-06', '10:11:00', '16:30:00', '0', '00:00:00', 'today little bit late'),
(9, 1, '2025-02-06', '10:11:00', '16:30:00', '0', '00:00:00', 'today little bit late'),
(10, 1, '2025-02-06', '10:11:00', '16:30:00', '0', '00:00:00', 'today little bit late'),
(11, 1, '2025-02-06', '10:11:00', '16:30:00', '0', '00:00:00', 'today little bit late'),
(12, 1, '2025-02-06', '10:11:00', '16:30:00', '0', '00:00:00', 'today little bit late'),
(13, 1, '2025-02-06', '10:11:00', '16:30:00', '0', '00:00:00', 'today little bit late'),
(14, 1, '2025-02-06', '10:00:00', '22:30:00', '0', '00:00:00', 'hello'),
(15, 1, '2025-02-06', '10:00:00', '22:30:00', '0', '00:00:00', 'hello'),
(16, 1, '2025-02-06', '10:30:00', '12:20:00', '0', '00:00:00', 'need to go home its urgent so '),
(17, 1, '2025-02-06', '22:20:00', '17:30:00', '0', '00:00:00', 'help'),
(18, 1, '2025-02-06', '10:20:00', '22:12:00', '0', '00:00:00', 'overtime'),
(19, 1, '2025-02-06', '10:20:00', '22:30:00', '0', '00:00:00', ''),
(20, 2, '2025-02-06', '10:30:00', '23:33:00', '0', '00:00:00', 'project'),
(21, 2, '2025-02-06', '12:22:00', '23:35:00', '0', '00:00:00', ''),
(22, 2, '2025-02-06', '00:01:00', '23:40:00', '0', '00:00:00', ''),
(23, 2, '2025-02-06', '10:20:00', '12:00:00', '0', '00:00:00', ''),
(24, 1, '2025-02-07', '10:30:00', '15:02:00', '0', '00:00:00', 'lit'),
(25, 2, '2025-02-09', '10:00:00', '16:30:00', '0', '00:00:00', 'aayush'),
(26, 3, '2025-02-10', '10:30:00', '02:00:00', '0', '00:00:00', 'doing project'),
(27, 2, '2025-02-11', '10:11:00', '16:30:00', 'Present', '00:00:00', ''),
(29, 2, '2025-02-15', '10:30:00', '22:30:00', 'Present', '00:00:00', 'hello'),
(30, 2, '2025-03-16', '10:11:00', '16:50:00', 'Present', '00:00:00', 'what a busy day sigh'),
(31, 2, '2025-03-16', '10:16:00', '17:20:00', 'Present', '7.066666666666666', 'what a busy day sigh');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `salary` decimal(10,0) UNSIGNED NOT NULL,
  `join_date` date NOT NULL,
  `contact_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `username`, `password`, `email`, `department`, `position`, `salary`, `join_date`, `contact_number`) VALUES
(1, 'hi', '$2y$10$A2E5vncRWCuza3IVrDkdhenPnD5SbbdY2ZlZ5jLYiGI7.IRg.oluq', 'hi@gmail.com', 'IT', 'Manager', 100000, '2021-02-02', '9867543210'),
(2, 'sidash', '$2y$10$ICZf/y.fEvgOBWXsZUDa6ugtq7JHSTlIUynbiuq011pQmrXtxef5u', 'sidash@gmail.com', 'it', 'senior dev', 120000, '2022-02-20', '9823456787'),
(3, 'aayush', '$2y$10$oScXWioRa6kZMiMXrs4hMON6tb7boWyq45OBITYi7BNKAMWaVAqPy', 'ayush@gmail.com', 'IT', 'Intern', 100000, '2025-02-20', '9812345676');

-- --------------------------------------------------------

--
-- Table structure for table `leave_request`
--

CREATE TABLE `leave_request` (
  `leave_request_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `leave_type` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `reason` text DEFAULT NULL,
  `proof_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_request`
--

INSERT INTO `leave_request` (`leave_request_id`, `employee_id`, `id`, `start_date`, `end_date`, `leave_type`, `status`, `reason`, `proof_path`) VALUES
(2, 2, NULL, '2025-02-25', '2025-03-01', 'Sick Leave', 'Rejected', 'fever', NULL),
(3, 2, NULL, '2025-01-01', '2025-02-02', 'Sick Leave', 'Approved', 'common cold and fever', NULL),
(5, 2, NULL, '2025-02-14', '2025-02-16', 'Sick Leave', 'Approved', 'high fever', NULL),
(6, 2, NULL, '2025-02-14', '2025-02-20', 'Sick Leave', 'Rejected', 'dgfd', NULL),
(7, 2, NULL, '2025-02-15', '2025-02-20', 'Sick Leave', 'Rejected', 'feaver', NULL),
(8, 2, NULL, '2025-02-15', '2025-02-20', 'Sick Leave', 'Approved', 'sick', NULL),
(9, 2, NULL, '2025-02-16', '2025-02-18', 'Casual Leave', 'Approved', 'family function', NULL),
(10, 1, NULL, '2025-02-16', '2025-02-18', 'Casual Leave', 'Rejected', 'family function', NULL),
(11, 2, NULL, '2025-03-16', '2025-03-20', 'Sick Leave', 'Rejected', 'sick', NULL),
(12, 2, NULL, '0025-03-16', '2025-03-20', 'Sick Leave', 'Approved', 'suffering from depression', NULL),
(13, 2, NULL, '2025-06-12', '2025-06-15', 'Sick Leave', 'Approved', 'viral fever', './uploads/edd456935429eba58bae05d1e9e6f86b.jpg'),
(14, 2, NULL, '2025-06-12', '2025-06-15', 'Casual Leave', 'Approved', 'i want break to make my mental health good', './uploads/5ca76ccc82f0311b818db77642c7d237.jpg'),
(15, 2, NULL, '2025-06-12', '2025-06-16', 'Annual Leave', 'Approved', 'nothing i just want to take leave', './uploads/1916b4244dcb4944e71285b94eab005d.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `employee_id`, `message`, `status`, `created_at`) VALUES
(1, 2, 'Your leave request #8 has been Approved.', 'read', '2025-02-15 08:23:27'),
(2, 2, 'Your leave request #9 has been Approved.', 'read', '2025-02-16 07:58:28'),
(3, 1, 'Your leave request #10 has been Rejected.', 'read', '2025-02-16 08:07:47'),
(4, 2, 'New task assigned: project (Due: 2025-02-20)', 'read', '2025-02-16 09:07:41'),
(5, 2, 'New task assigned: ok (Due: 2025-02-20)', 'read', '2025-02-16 09:45:52'),
(6, 2, 'New task assigned: helll (Due: 2025-03-20)', 'read', '2025-03-15 11:58:10'),
(7, 2, 'Your leave request #11 has been Rejected.', 'read', '2025-03-16 05:10:48'),
(8, 2, 'New task assigned: index page (Due: 2025-03-17)', 'read', '2025-03-16 05:17:17'),
(9, 2, 'New task assigned: index page (Due: 2025-03-17)', 'read', '2025-03-16 08:17:37'),
(10, 2, 'Your leave request #12 has been Approved.', 'read', '2025-03-16 08:29:48'),
(11, 2, 'New task assigned: design ui (Due: 2025-06-11)', 'read', '2025-06-12 02:16:29'),
(12, 2, 'New task assigned: create catalog (Due: 2025-06-11)', 'read', '2025-06-12 02:32:31'),
(13, 2, 'New task assigned: complete backend (Due: 2025-06-11)', 'read', '2025-06-12 02:37:04'),
(14, 2, 'Your leave request #13 has been Approved.', 'read', '2025-06-12 02:57:34'),
(15, 2, 'Your leave request #14 has been Approved.', 'read', '2025-06-12 03:11:56'),
(16, 2, 'Your leave request #15 has been Approved.', 'read', '2025-06-12 03:25:04');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `task_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  `task_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_date` date NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('pending','in-progress','completed') NOT NULL DEFAULT 'pending',
  `completion_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`task_id`, `employee_id`, `id`, `task_name`, `description`, `assigned_date`, `due_date`, `status`, `completion_date`) VALUES
(1, 2, NULL, ' k xa', 'hello', '2025-02-06', '2025-02-13', 'completed', '2025-02-13'),
(2, 1, NULL, 'hello', 'hi', '2025-02-06', '2025-02-20', 'completed', '2025-02-20'),
(3, 2, NULL, 'nice', 'nice', '2025-02-02', '2025-02-25', 'completed', '2025-02-25'),
(7, 2, NULL, 'hell', 'dhjdhfj', '2025-02-15', '2025-02-20', 'completed', '2025-02-20'),
(11, 2, NULL, 'project', 'project', '2025-02-16', '2025-02-20', 'completed', '2025-02-20'),
(12, 2, NULL, 'ok', 'ok', '2025-02-16', '2025-02-20', 'completed', '2025-02-20'),
(16, 2, NULL, 'design ui', 'design ui ', '2025-06-10', '2025-06-11', 'completed', '2025-06-11'),
(17, 2, NULL, 'create catalog', 'catalog', '2025-02-20', '2025-06-11', 'completed', NULL),
(18, 2, NULL, 'complete backend', 'complete backend for ems', '2025-02-02', '2025-06-11', 'completed', '2025-06-12');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `profile_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`profile_id`, `employee_id`, `id`, `username`, `password`, `role`, `last_login`) VALUES
(1, 1, NULL, 'hi', '$2y$10$A2E5vncRWCuza3IVrDkdhenPnD5SbbdY2ZlZ5jLYiGI7.IRg.oluq', 'Employee', '2025-02-16 08:08:08'),
(2, 2, NULL, 'sidash', '$2y$10$ICZf/y.fEvgOBWXsZUDa6ugtq7JHSTlIUynbiuq011pQmrXtxef5u', 'Employee', '2025-06-12 05:32:21'),
(3, 3, NULL, 'aayush', '$2y$10$oScXWioRa6kZMiMXrs4hMON6tb7boWyq45OBITYi7BNKAMWaVAqPy', 'Employee', '2025-03-17 09:06:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `leave_request`
--
ALTER TABLE `leave_request`
  ADD PRIMARY KEY (`leave_request_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`profile_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `leave_request`
--
ALTER TABLE `leave_request`
  MODIFY `leave_request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_request`
--
ALTER TABLE `leave_request`
  ADD CONSTRAINT `leave_request_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_request_ibfk_2` FOREIGN KEY (`id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_ibfk_2` FOREIGN KEY (`id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_profile_ibfk_2` FOREIGN KEY (`id`) REFERENCES `admins` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
