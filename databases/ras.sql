-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2025 at 04:06 PM
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
-- Database: `ras`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `designation`, `role`) VALUES
(1, 'Saif Bashar', 'saifbashar2021@gmail.com', '1234', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `coordinator`
--

CREATE TABLE `coordinator` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coordinator`
--

INSERT INTO `coordinator` (`id`, `name`, `email`, `password`, `designation`, `status`, `phone`, `created_at`) VALUES
(2, 'Forhad Kabir', 'forhad@gmail.com', '43345', 'Assistant Professor', 1, '01704938941', '2025-02-03 20:37:00'),
(3, 'Karim Ahmed', 'karim@gmail.com', '43345', 'Chairman', 1, '01704938941', '2025-02-03 20:45:46');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `semester` varchar(255) NOT NULL,
  `course_code` varchar(255) NOT NULL,
  `course_title` varchar(255) NOT NULL,
  `credit` int(11) NOT NULL,
  `mark` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`semester`, `course_code`, `course_title`, `credit`, `mark`) VALUES
('11', 'CSE1111', 'Introduction to Computer Systems', 3, 100),
('11', 'CSE1112', 'Introduction to Computer Systems Sessional', 2, 50),
('11', 'CSE1121', 'Structured Programming Language', 3, 100),
('11', 'CSE1122', 'Structured Programming Language Sessional', 2, 50),
('12', 'CSE1211', 'Basic Electrical Engineering', 3, 100),
('12', 'CSE1212', 'Basic Electrical Engineering Sessional', 2, 50),
('12', 'CSE1221', 'Object Oriented Programming With C++', 3, 100),
('12', 'CSE1222', 'Object Oriented Programming With C++ Sessional', 2, 50),
('21', 'CSE2111', 'Cyber and Intellectual Property Law', 3, 100),
('21', 'CSE2121', 'Programming with JAVA', 3, 100),
('21', 'CSE2122', 'Programming with JAVA Sessional', 2, 50),
('21', 'CSE2131', 'Basic Electronics', 3, 100),
('21', 'CSE2132', 'Basic Electronics Sessional', 2, 50),
('21', 'CSE2141', 'System Analysis and Design', 3, 100),
('22', 'CSE2211', 'Digital Electronics', 3, 100),
('22', 'CSE2212', 'Digital Electronics Sessional', 2, 50),
('22', 'CSE2221', 'Data Structure', 3, 100),
('22', 'CSE2222', 'Data Structure Sessional', 2, 50),
('22', 'CSE2231', 'Communication Engineering', 3, 100),
('22', 'CSE2232', 'Communication Engineering Sessional', 2, 50),
('22', 'CSE2241', 'Computational Methods for Engineers', 3, 100),
('22', 'CSE2251', 'Digital System Design', 3, 100),
('22', 'CSE2252', 'Digital System Design Sessional', 2, 50),
('31', 'CSE3111', 'Discrete Mathematics', 3, 100),
('31', 'CSE3121', 'Design and Analysis of Algorithm', 3, 100),
('31', 'CSE3122', 'Design and Analysis of Algorithm Sessional', 2, 50),
('31', 'CSE3131', 'Operating System and System Programming', 3, 100),
('31', 'CSE3132', 'Operating System and System Programming Sessional', 2, 50),
('31', 'CSE3141', 'Database Management System', 3, 100),
('31', 'CSE3142', 'Database Management System Sessional', 2, 50),
('31', 'CSE3151', 'Computer Architecture and Organization', 3, 100),
('31', 'CSE3160', 'Software Development Project', 2, 50),
('32', 'CSE3211', 'Digital Signal Processing', 3, 100),
('32', 'CSE3212', 'Digital Signal Processing Sessional', 2, 50),
('32', 'CSE3221', 'Computer Graphics', 3, 100),
('32', 'CSE3222', 'Computer Graphics Sessional', 2, 50),
('32', 'CSE3231', 'Computer Networking', 3, 100),
('32', 'CSE3232', 'Computer Networking Sessional', 2, 50),
('32', 'CSE3241', 'Web Engineering', 3, 100),
('32', 'CSE3242', 'Web Engineering Sessional', 2, 50),
('32', 'CSE3250', 'Software Development Project', 2, 50),
('41', 'CSE4111', 'Automata Theory and Compiler Design', 3, 100),
('41', 'CSE4112', 'Automata Theory and Compiler Design Sessional', 2, 50),
('41', 'CSE4121', 'Microprocessor and Microcontroller', 3, 100),
('41', 'CSE4122', 'Microprocessor and Microcontroller Sessional', 2, 50),
('41', 'CSE4131', 'Artificial Intelligence', 3, 100),
('41', 'CSE4132', 'Artificial Intelligence Sessional', 2, 50),
('41', 'CSE4141', 'Cryptography and Network Security', 3, 100),
('41', 'CSE4142', 'Cryptography and Network Sessional', 2, 50),
('42', 'CSE4211', 'Software Engineering', 3, 100),
('42', 'CSE4212', 'Software Engineering Sessional', 2, 50),
('42', 'CSE4221', 'Digital Image Processing', 3, 100),
('42', 'CSE4222', 'Digital Image Processing Sessional', 2, 50),
('42', 'CSE4261', 'Machine Learning', 3, 100),
('42', 'CSE4262', 'Machine Learning Sessional', 2, 50),
('42', 'CSE4270', 'Industrial Attachment', 1, 50),
('42', 'CSE4280', 'Thesis/Project (Part II)', 3, 100),
('42', 'CSE4290', 'Board Viva-voce', 2, 50),
('11', 'HUM1111', 'Bangladesh Studies', 3, 100),
('12', 'HUM1211', 'Technical and Communicative English', 3, 100),
('12', 'HUM1221', 'Accounting and Economics for Engineers', 3, 100),
('21', 'HUM2111', 'Legal Issues and Professional Ethics for Engineers', 3, 100),
('11', 'MATH1111', 'Linear Algebra and Vector Analysis', 3, 100),
('12', 'MATH1211', 'Differential Calculus, Integral Calculus and Coordinate Geometry', 3, 100),
('21', 'MATH2111', 'Fourier Analysis, Laplace Transform and Differential Equation', 3, 100),
('11', 'PHY1111', 'Physics', 3, 100),
('12', 'STAT1211', 'Statistics for Engineers', 3, 100);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_code` varchar(255) NOT NULL,
  `session` varchar(255) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `marks_obtained` int(11) DEFAULT NULL,
  `grade` char(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `student_id`, `course_code`, `session`, `semester`, `marks_obtained`, `grade`) VALUES
(285, 2010111, 'CSE1111', '2019-2020', '11', 80, '4'),
(286, 2010111, 'CSE1112', '2019-2020', '11', 37, '3.5'),
(287, 2010111, 'CSE1121', '2019-2020', '11', 85, '4'),
(288, 2010111, 'CSE1122', '2019-2020', '11', 34, '3.25'),
(289, 2010111, 'HUM1111', '2019-2020', '11', 76, '3.75'),
(290, 2010111, 'MATH1111', '2019-2020', '11', 54, '2.5'),
(291, 2010111, 'PHY1111', '2019-2020', '11', 89, '4'),
(292, 2010112, 'CSE1111', '2019-2020', '11', 87, '4'),
(293, 2010112, 'CSE1112', '2019-2020', '11', 45, '4'),
(294, 2010112, 'CSE1121', '2019-2020', '11', 76, '3.75'),
(295, 2010112, 'CSE1122', '2019-2020', '11', 30, '3'),
(296, 2010112, 'HUM1111', '2019-2020', '11', 76, '3.75'),
(297, 2010112, 'MATH1111', '2019-2020', '11', 67, '3.25'),
(298, 2010112, 'PHY1111', '2019-2020', '11', 67, '3.25');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `session` varchar(255) NOT NULL,
  `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `password`, `name`, `session`, `status`) VALUES
(2010111, '1234', 'Saif Bashar', '2019-2020', 1),
(2010112, '123', 'Arafat Khan Rahman', '2019-2020', 0),
(2010113, '123', 'Nafis Rahman', '2019-2020', 0),
(2010114, '123', 'Tanjim Ahmed', '2019-2020', 0),
(2010115, '123', 'Mahin Khan', '2019-2020', 0),
(2010116, '123', 'Farhan Islam', '2019-2020', 0),
(2010117, '123', 'Tahsin Ali', '2019-2020', 0),
(2010118, '123', 'Adib Chowdhury', '2019-2020', 0),
(2010119, '123', 'Mehedi Hasan', '2019-2020', 0),
(2010122, '123', 'Rakibul Hasan', '2019-2020', 0),
(2010123, '123', 'Zubair Khan', '2019-2020', 0),
(2010124, '123', 'Asif Rahman', '2019-2020', 0),
(2010125, '123', 'Hasibul Haque', '2019-2020', 0),
(2010126, '123', 'Shadman Sakib', '2019-2020', 0),
(2010127, '123', 'Rahat Chowdhury', '2019-2020', 0),
(2010128, '123', 'Imran Hossain', '2019-2020', 0),
(2010129, '123', 'Rashedul Amin', '2019-2020', 0),
(2010130, '123', 'Tushar Mahmud', '2019-2020', 0),
(2010131, '123', 'Anika Sultana', '2019-2020', 0),
(2010132, '123', 'Afia Jahan', '2019-2020', 0),
(2010133, '123', 'Samiha Akter', '2019-2020', 0),
(2010134, '123', 'Sabbir Ahmed', '2019-2020', 0),
(2010135, '123', 'Tanvir Islam', '2019-2020', 0),
(2010136, '123', 'Shamim Reza', '2019-2020', 0),
(2010137, '123', 'Rezwan Rahman', '2019-2020', 0),
(2010138, '123', 'Ashraful Alam', '2019-2020', 0),
(2010139, '123', 'Mim Rahman', '2019-2020', 0),
(2010140, '123', 'Farzana Ahmed', '2019-2020', 0),
(2010141, '123', 'Zihan Mahmud', '2019-2020', 0),
(2010142, '123', 'Mahmud Hasan', '2019-2020', 0),
(2110111, '123', 'Hasan Abdullah', '2020-2021', 0),
(2110112, '123', 'Mahir Chowdhury', '2020-2021', 0),
(2110113, '123', 'Tanjila Islam', '2020-2021', 0),
(2110114, '123', 'Jahidul Hasan', '2020-2021', 0),
(2110115, '123', 'Nazmul Hossain', '2020-2021', 0),
(2110116, '123', 'Sadia Akter', '2020-2021', 0),
(2110117, '123', 'Rifat Khan', '2020-2021', 0),
(2110118, '123', 'Sakib Al Hasan', '2020-2021', 0),
(2110119, '123', 'Fahima Rahman', '2020-2021', 0),
(2110120, '123', 'Arifur Rahman', '2020-2021', 0),
(2110121, '123', 'Sumaiya Akter', '2020-2021', 0),
(2110122, '123', 'Rakibul Islam', '2020-2021', 0),
(2110123, '123', 'Nusrat Jahan', '2020-2021', 0),
(2110124, '123', 'Shahriar Ahmed', '2020-2021', 0),
(2110125, '123', 'Tasnim Rahman', '2020-2021', 0),
(2110126, '123', 'Rafiqul Islam', '2020-2021', 0),
(2110127, '123', 'Saima Sultana', '2020-2021', 0),
(2110128, '123', 'Kamal Hossain', '2020-2021', 0),
(2110129, '123', 'Nazia Akter', '2020-2021', 0),
(2110130, '123', 'Rifat Ahmed', '2020-2021', 0),
(2110131, '123', 'Shahin Alam', '2020-2021', 0),
(2110132, '123', 'Taslima Begum', '2020-2021', 0),
(2210111, '123', 'Rizwan Kabir', '2021-2022', 0),
(2210112, '123', 'Shafiq Rahman', '2021-2022', 0),
(2210113, '123', 'Nadia Islam', '2021-2022', 0),
(2210114, '123', 'Arman Ahmed', '2021-2022', 0),
(2210115, '123', 'Sadia Rahman', '2021-2022', 0),
(2210116, '123', 'Faisal Mahmud', '2021-2022', 0),
(2210117, '123', 'Tania Akter', '2021-2022', 0),
(2210118, '123', 'Rafiqul Haque', '2021-2022', 0),
(2210119, '123', 'Shirin Sultana', '2021-2022', 0),
(2210120, '123', 'Kamrul Hasan', '2021-2022', 0),
(2210121, '123', 'Nazmul Islam', '2021-2022', 0),
(2210122, '123', 'Farhana Akter', '2021-2022', 0),
(2210123, '123', 'Rakibul Haque', '2021-2022', 0),
(2210124, '123', 'Sharmin Akter', '2021-2022', 0),
(2210125, '123', 'Sajidul Islam', '2021-2022', 0),
(2210126, '123', 'Tahmina Rahman', '2021-2022', 0),
(2210127, '123', 'Rifatul Islam', '2021-2022', 0),
(2210128, '123', 'Naznin Akter', '2021-2022', 0),
(2210129, '123', 'Shahidul Islam', '2021-2022', 0),
(2210130, '123', 'Tasnim Akter', '2021-2022', 0),
(2210131, '123', 'Rafiqul Islam', '2021-2022', 0),
(2210132, '123', 'Shirin Akter', '2021-2022', 0),
(2310111, '123', 'Faridul Islam', '2022-2023', 0),
(2310112, '123', 'Tahmina Akter', '2022-2023', 0),
(2310113, '123', 'Rafiqul Islam', '2022-2023', 0),
(2310114, '123', 'Nazma Akter', '2022-2023', 0),
(2310115, '123', 'Shahidul Haque', '2022-2023', 0),
(2310116, '123', 'Taslima Akter', '2022-2023', 0),
(2310117, '123', 'Rifatul Islam', '2022-2023', 0),
(2310118, '123', 'Naznin Akter', '2022-2023', 0),
(2310119, '123', 'Shahin Alam', '2022-2023', 0),
(2310120, '123', 'Tasnim Rahman', '2022-2023', 0),
(2310121, '123', 'Rakibul Islam', '2022-2023', 0),
(2310122, '123', 'Sharmin Akter', '2022-2023', 0),
(2310123, '123', 'Sajidul Islam', '2022-2023', 0),
(2310124, '123', 'Tahmina Rahman', '2022-2023', 0),
(2310125, '123', 'Rifatul Islam', '2022-2023', 0),
(2310126, '123', 'Naznin Akter', '2022-2023', 0),
(2310127, '123', 'Shahidul Islam', '2022-2023', 0),
(2310128, '123', 'Tasnim Akter', '2022-2023', 0),
(2310129, '123', 'Rafiqul Islam', '2022-2023', 0),
(2310130, '123', 'Shirin Akter', '2022-2023', 0),
(2310131, '123', 'Nazma Akter', '2022-2023', 0),
(2310132, '123', 'Shahidul Haque', '2022-2023', 0),
(2410111, '123', 'Imtiaz Hossain', '2023-2024', 0),
(2410112, '123', 'Nusrat Jahan', '2023-2024', 0),
(2410113, '123', 'Rafiqul Islam', '2023-2024', 0),
(2410114, '123', 'Nazma Akter', '2023-2024', 0),
(2410115, '123', 'Shahidul Haque', '2023-2024', 0),
(2410116, '123', 'Taslima Akter', '2023-2024', 0),
(2410117, '123', 'Rifatul Islam', '2023-2024', 0),
(2410118, '123', 'Naznin Akter', '2023-2024', 0),
(2410119, '123', 'Shahin Alam', '2023-2024', 0),
(2410120, '123', 'Tasnim Rahman', '2023-2024', 0),
(2410121, '123', 'Rakibul Islam', '2023-2024', 0),
(2410122, '123', 'Sharmin Akter', '2023-2024', 0),
(2410123, '123', 'Sajidul Islam', '2023-2024', 0),
(2410124, '123', 'Tahmina Rahman', '2023-2024', 0),
(2410125, '123', 'Rifatul Islam', '2023-2024', 0),
(2410126, '123', 'Naznin Akter', '2023-2024', 0),
(2410127, '123', 'Shahidul Islam', '2023-2024', 0),
(2410128, '123', 'Tasnim Akter', '2023-2024', 0),
(2410129, '123', 'Rafiqul Islam', '2023-2024', 0),
(2410130, '123', 'Shirin Akter', '2023-2024', 0),
(2410131, '123', 'Nazma Akter', '2023-2024', 0),
(2410132, '123', 'Shahidul Haque', '2023-2024', 0),
(20111111, '1234', 'Saif Bashar', '2019-2020', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coordinator`
--
ALTER TABLE `coordinator`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_code`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_code` (`course_code`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coordinator`
--
ALTER TABLE `coordinator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=299;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`course_code`) REFERENCES `courses` (`course_code`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
