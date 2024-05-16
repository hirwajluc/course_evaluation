-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2023 at 11:36 AM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `course_evaluation`
--

-- --------------------------------------------------------

--
-- Table structure for table `classwise_history`
--

CREATE TABLE IF NOT EXISTS `classwise_history` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(30) NOT NULL,
  `academic_year` varchar(30) NOT NULL,
  `dept_code` varchar(10) NOT NULL,
  `year` varchar(10) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `quality` varchar(100) NOT NULL,
  `answer` varchar(50) NOT NULL,
  `amount` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staffwise_history`
--

CREATE TABLE IF NOT EXISTS `staffwise_history` (
  `id` int(11) NOT NULL,
  `staff_name` varchar(30) NOT NULL,
  `academic_year` varchar(30) NOT NULL,
  `dept_code` varchar(10) NOT NULL,
  `quality` varchar(100) NOT NULL,
  `answer` varchar(50) NOT NULL,
  `amount` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_answer`
--

CREATE TABLE IF NOT EXISTS `tbl_answer` (
  `answer_id` int(10) NOT NULL,
  `answer_type` varchar(30) NOT NULL,
  `answer_score` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_answer`
--

INSERT INTO `tbl_answer` (`answer_id`, `answer_type`, `answer_score`) VALUES
(1, 'Neutral', 2),
(2, 'Agree', 3),
(3, 'Disagree', 1),
(4, 'Strongly Disagree', 0),
(5, 'Strongly Agree', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_classes`
--

CREATE TABLE IF NOT EXISTS `tbl_classes` (
  `class_id` int(11) unsigned NOT NULL,
  `class_group` int(11) NOT NULL,
  `class_level` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_classes`
--

INSERT INTO `tbl_classes` (`class_id`, `class_group`, `class_level`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 1, 2),
(4, 2, 2),
(5, 1, 3),
(6, 2, 3),
(7, 3, 3),
(8, 1, 4),
(9, 2, 4),
(10, 1, 5),
(11, 2, 5),
(12, 1, 6),
(13, 2, 6),
(14, 1, 7),
(15, 2, 7),
(16, 1, 8),
(17, 2, 8),
(18, 1, 9),
(19, 2, 9),
(20, 1, 10),
(21, 2, 10),
(22, 1, 11),
(23, 2, 11),
(24, 1, 12),
(25, 2, 12),
(26, 1, 13),
(27, 2, 13),
(28, 1, 14),
(29, 2, 14),
(30, 1, 15),
(31, 2, 15),
(32, 1, 16),
(33, 2, 16),
(34, 1, 34),
(35, 2, 34),
(36, 1, 35),
(37, 2, 35),
(38, 1, 23),
(39, 2, 23),
(40, 1, 24),
(41, 2, 24),
(42, 1, 20),
(43, 2, 20),
(44, 1, 21),
(45, 2, 21),
(46, 1, 22),
(47, 2, 22),
(48, 1, 28),
(49, 2, 28),
(50, 1, 29),
(51, 1, 30),
(52, 2, 30);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_codes`
--

CREATE TABLE IF NOT EXISTS `tbl_codes` (
  `id` int(10) unsigned NOT NULL,
  `academic_year` varchar(10) NOT NULL,
  `code` varchar(16) DEFAULT NULL,
  `used` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comments`
--

CREATE TABLE IF NOT EXISTS `tbl_comments` (
  `comment_id` int(20) NOT NULL,
  `comment_value` varchar(500) NOT NULL,
  `academic_year` varchar(10) NOT NULL,
  `year` varchar(20) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `c_group` varchar(1) DEFAULT NULL,
  `department` varchar(30) NOT NULL,
  `subject` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_department`
--

CREATE TABLE IF NOT EXISTS `tbl_department` (
  `department_id` int(10) unsigned NOT NULL,
  `department_code` varchar(10) NOT NULL,
  `department_name` varchar(50) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_department`
--

INSERT INTO `tbl_department` (`department_id`, `department_code`, `department_name`) VALUES
(2, 'CROP', 'Crop Production'),
(3, 'IDT', 'Irrigation and Drainage Technology'),
(4, 'FOP', 'Food Processing'),
(5, 'COT', 'Construction Technology'),
(6, 'HWE', 'Highway Engineering'),
(7, 'WAS', 'Water and Sanitation Technology'),
(8, 'ELT', 'Electrical Technology'),
(9, 'EAT', 'Electrical Automation Technology'),
(10, 'CUA', 'Culinary Arts'),
(11, 'FBM', 'Food and Beverage Services'),
(12, 'RDM', 'Room Division'),
(13, 'IT', 'Information Technology'),
(14, 'ECO', 'E Commerce');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_feedback`
--

CREATE TABLE IF NOT EXISTS `tbl_feedback` (
  `feedback_id` int(10) NOT NULL,
  `feedback_acc_year` varchar(20) NOT NULL,
  `feedback_dept_id` int(10) NOT NULL,
  `feedback_year` varchar(20) NOT NULL,
  `feedback_semester` varchar(20) NOT NULL,
  `feedback_group` varchar(1) DEFAULT NULL,
  `feedback_sub_id` int(10) NOT NULL,
  `feedback_quality_id` int(10) NOT NULL,
  `feedback_answer_id` int(10) NOT NULL,
  `feedback_stud_id` int(10) NOT NULL,
  `feedback_teacher_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_levels`
--

CREATE TABLE IF NOT EXISTS `tbl_levels` (
  `level_id` int(11) unsigned NOT NULL,
  `level_year` int(11) NOT NULL,
  `level_department` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_levels`
--

INSERT INTO `tbl_levels` (`level_id`, `level_year`, `level_department`) VALUES
(1, 1, 5),
(2, 2, 5),
(3, 3, 5),
(4, 1, 2),
(5, 2, 2),
(6, 3, 2),
(7, 1, 10),
(8, 2, 10),
(9, 3, 10),
(10, 1, 14),
(11, 2, 14),
(12, 1, 9),
(13, 2, 9),
(14, 1, 8),
(15, 2, 8),
(16, 3, 8),
(17, 1, 11),
(18, 2, 11),
(19, 3, 11),
(20, 1, 4),
(21, 2, 4),
(22, 3, 4),
(23, 1, 6),
(24, 2, 6),
(25, 3, 6),
(26, 1, 13),
(27, 2, 13),
(28, 1, 3),
(29, 2, 3),
(30, 3, 3),
(31, 1, 12),
(32, 2, 12),
(33, 3, 12),
(34, 1, 7),
(35, 2, 7),
(36, 3, 7),
(37, 3, 14),
(38, 3, 9),
(39, 3, 13);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quality`
--

CREATE TABLE IF NOT EXISTS `tbl_quality` (
  `quality_id` int(20) NOT NULL,
  `quality` varchar(300) NOT NULL,
  `Title` varchar(50) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_quality`
--

INSERT INTO `tbl_quality` (`quality_id`, `quality`, `Title`) VALUES
(1, 'Classroom setting', 'Learning environment'),
(2, 'Availability of learning consumables, tools and equipment', 'Learning environment'),
(3, 'Availability of PPE', 'Learning environment'),
(4, 'Clarification of Course objectives to students', 'Module/course content organization'),
(5, 'Provision of mapping of learning units, course handouts and further references', 'Module/course content organization'),
(6, 'Provision of Sufficient learning activities for each learning outcomes', 'Module/course content organization'),
(8, 'Trainerâ€™s Punctuality', 'Quality of Delivery'),
(9, 'Encouraging opinions and learnersâ€™ feedback', 'Quality of Delivery'),
(10, 'Mastery of content', 'Quality of Delivery'),
(11, 'Effective use of instructional technology', 'Quality of Delivery'),
(12, 'Encouraging active participation and independent practice', 'Quality of Delivery'),
(13, 'Effective use of communication skills', 'Quality of Delivery'),
(14, 'Formative assessments, CATs and Summative assessment provided on time', 'Assessment and feedback'),
(15, 'Provision of feedback to students(work, test, exams, responding studentsâ€™ question) and establish conclusion', 'Assessment and feedback');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subject`
--

CREATE TABLE IF NOT EXISTS `tbl_subject` (
  `subject_id` int(10) unsigned NOT NULL,
  `subject_ac_year` varchar(10) NOT NULL,
  `subject_name` varchar(50) DEFAULT NULL,
  `subject_code` varchar(20) NOT NULL,
  `subject_year` varchar(20) NOT NULL,
  `subject_semester` int(10) NOT NULL,
  `subject_group` varchar(1) DEFAULT NULL,
  `subject_department_id` int(10) NOT NULL,
  `subject_teacher_id` int(10) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=186 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_subject`
--

INSERT INTO `tbl_subject` (`subject_id`, `subject_ac_year`, `subject_name`, `subject_code`, `subject_year`, `subject_semester`, `subject_group`, `subject_department_id`, `subject_teacher_id`) VALUES
(8, '2022-2023', 'Analyze indeterminate Statically structures', 'CSTIS601', '2', 1, '1', 5, 38),
(9, '2022-2023', 'statically determinate structures', 'CSTDS601', '1', 2, '1', 5, 38),
(10, '2022-2023', 'statically indeterminate structures', 'CSTIS601', '2', 2, '1', 6, 38),
(11, '2022-2023', 'soil stabilization ', 'CSTSS601', '2', 1, '1', 5, 26),
(12, '2022-2023', 'construction Materials', 'CSTCM601', '1', 2, '1', 5, 26),
(13, '2022-2023', 'Basic road geometric design ', 'HWTRGD601', '2', 1, '1', 6, 31),
(14, '2022-2023', 'Technical drawings  ', 'HWTRD601  ', '1', 1, '1', 6, 31),
(15, '2022-2023', 'AutoCAD Civil 3D', 'HWTCAD601', '1', 2, '1', 6, 31),
(16, '2022-2023', 'Soil mechanics  ', 'HWTSM601 ', '1', 1, '1', 6, 31),
(17, '2022-2023', 'Masonry works', 'CSTMW601', '1', 2, '1', 5, 28),
(18, '2022-2023', 'Occupation and learning process ', 'CCMOL601 ', '1', 1, '1', 6, 28),
(19, '2022-2023', 'laboratory test on road construction materials', 'HWTLTRM601', '1', 2, '1', 6, 28),
(20, '2022-2023', 'Occupation and learning process ', 'CCMOL602', '1', 1, '1', 7, 28),
(21, '2022-2023', 'Supervise Road topographic works     ', 'HWTRTW601 ', '1', 1, '1', 5, 36),
(22, '2022-2023', 'Use ArchiCAD Software ', 'CSTAC601', '2', 1, '1', 5, 36),
(23, '2022-2023', 'Topographic surveying ', 'WASTS601', '2', 2, '1', 7, 36),
(24, '2022-2023', 'Road topographic works', 'HWTCRT601', '2', 1, '1', 6, 36),
(25, '2022-2023', 'Sanitary Appliances Installation and Drainage Syst', 'CSTSD601', '2', 1, '1', 5, 27),
(26, '2022-2023', 'Occupational safety and health activities ', 'CCMSH601', '1', 1, '1', 7, 27),
(27, '2022-2023', 'Integrate workplace', 'CSTIA601', '2', 2, '1', 5, 27),
(28, '2022-2023', 'Maintenance of water network ', 'WASMW601', '2', 1, '1', 7, 27),
(29, '2022-2023', ' Occupation and learning process  ', 'CCMOL601 ', '1', 1, '1', 5, 39),
(30, '2022-2023', 'Advanced Plumbing technology', 'WSPT601', '1', 1, '1', 7, 39),
(31, '2022-2023', 'water supply installation', 'CSTWS601', '2', 2, '1', 5, 39),
(32, '2022-2023', 'Finishing activities', 'CSTFA601', '2', 1, '1', 5, 39),
(33, '2022-2023', 'Engineering  Ethics', 'GENBC601', '1', 1, '1', 7, 35),
(34, '2022-2023', 'Engineering drawings', 'CSTMT601', '1', 1, '1', 7, 35),
(35, '2022-2023', 'Metal and timber scaffolding', 'CSTMT601', '2', 2, '1', 5, 35),
(36, '2022-2023', 'Engineering ethics ', 'CCMEE601 ', '2', 1, '1', 5, 35),
(37, '2022-2023', 'Carpentry timber works', 'CSTTS601', '2', 1, '1', 5, 40),
(38, '2022-2023', 'Analyze the drawings', 'HWTARD601', '2', 2, '1', 6, 40),
(39, '2022-2023', 'Technical drawing', 'HWTTD601 ', '1', 1, '1', 6, 40),
(40, '2022-2023', 'Apply Technical Drawing ', 'CSTTD601', '1', 1, '1', 5, 33),
(41, '2022-2023', 'Apply engineering drawings', 'QUSED601', '1', 1, '1', 7, 33),
(42, '2022-2023', 'Carpentry material handtools and machines', 'CSTMH601', '1', 2, '1', 5, 33),
(43, '2022-2023', 'Integrate workplace', 'CCMIA601', '2', 2, '1', 6, 33),
(44, '2022-2023', 'Occupational safety and health activities    ', 'CCMSH601 ', '1', 1, '1', 6, 33),
(45, '2022-2023', 'workplace environmental impact', 'CCMEM701', '3', 1, '1', 5, 40),
(46, '2022-2023', 'workplace environmental impact', 'CCMEM701', '3', 1, '1', 5, 39),
(47, '2022-2023', 'Geotechnical engineering', 'CSTGE701', '3', 1, '1', 5, 26),
(48, '2022-2023', 'Timber structures ', 'CSTTS701', '3', 1, '1', 5, 38),
(49, '2022-2023', 'Steel structures', 'CSTSS701', '3', 1, '1', 5, 38),
(50, '2022-2023', 'Analyze indeterminate Statically structures', 'CSTIS601', '2', 1, '2', 5, 38),
(51, '2022-2023', 'statically determinate structures', 'CSTDS601', '1', 2, '2', 5, 38),
(52, '2022-2023', 'statically indeterminate structures', 'CSTIS601', '2', 2, '2', 6, 38),
(53, '2022-2023', 'soil stabilization ', 'CSTSS601', '2', 1, '2', 5, 26),
(54, '2022-2023', 'construction Materials', 'CSTCM601', '1', 2, '2', 5, 26),
(55, '2022-2023', 'Basic road geometric design ', 'HWTRGD601', '2', 1, '2', 6, 31),
(56, '2022-2023', 'Technical drawings  ', 'HWTRD601  ', '1', 1, '2', 6, 31),
(57, '2022-2023', 'AutoCAD Civil 3D', 'HWTCAD601', '1', 2, '2', 6, 31),
(58, '2022-2023', 'Soil mechanics  ', 'HWTSM601 ', '1', 1, '2', 6, 31),
(59, '2022-2023', 'Masonry works', 'CSTMW601', '1', 2, '2', 5, 28),
(60, '2022-2023', 'Occupation and learning process ', 'CCMOL601 ', '1', 1, '2', 6, 28),
(61, '2022-2023', 'laboratory test on road construction materials', 'HWTLTRM601', '1', 2, '2', 6, 28),
(62, '2022-2023', 'Occupation and learning process ', 'CCMOL602', '1', 1, '2', 7, 28),
(63, '2022-2023', 'Supervise Road topographic works     ', 'HWTRTW601 ', '1', 1, '2', 5, 36),
(64, '2022-2023', 'Use ArchiCAD Software ', 'CSTAC601', '2', 1, '2', 5, 36),
(65, '2022-2023', 'Topographic surveying ', 'WASTS601', '2', 2, '2', 7, 36),
(66, '2022-2023', 'Road topographic works', 'HWTCRT601', '2', 1, '2', 6, 36),
(67, '2022-2023', 'Sanitary Appliances Installation and Drainage Syst', 'CSTSD601', '2', 1, '2', 5, 27),
(68, '2022-2023', 'Occupational safety and health activities ', 'CCMSH601', '1', 1, '2', 7, 27),
(69, '2022-2023', 'Integrate workplace', 'CSTIA601', '2', 2, '2', 5, 27),
(70, '2022-2023', 'Maintenance of water network ', 'WASMW601', '2', 1, '2', 7, 27),
(71, '2022-2023', ' Occupation and learning process  ', 'CCMOL601 ', '1', 1, '2', 5, 39),
(72, '2022-2023', 'Advanced Plumbing technology', 'WSPT601', '1', 1, '2', 7, 39),
(73, '2022-2023', 'water supply installation', 'CSTWS601', '2', 2, '2', 5, 39),
(74, '2022-2023', 'Finishing activities', 'CSTFA601', '2', 1, '2', 5, 39),
(75, '2022-2023', 'Engineering  Ethics', 'GENBC601', '1', 1, '2', 7, 35),
(76, '2022-2023', 'Engineering drawings', 'CSTMT601', '1', 1, '2', 7, 35),
(77, '2022-2023', 'Metal and timber scaffolding', 'CSTMT601', '2', 2, '2', 5, 35),
(78, '2022-2023', 'Engineering ethics ', 'CCMEE601 ', '2', 1, '2', 5, 35),
(79, '2022-2023', 'Carpentry timber works', 'CSTTS601', '2', 1, '2', 5, 40),
(80, '2022-2023', 'Analyze the drawings', 'HWTARD601', '2', 2, '2', 6, 40),
(81, '2022-2023', 'Technical drawing', 'HWTTD601 ', '1', 1, '2', 6, 40),
(82, '2022-2023', 'Apply Technical Drawing ', 'CSTTD601', '1', 1, '2', 5, 33),
(83, '2022-2023', 'Apply engineering drawings', 'QUSED601', '1', 1, '2', 7, 33),
(84, '2022-2023', 'Carpentry material handtools and machines', 'CSTMH601', '1', 2, '2', 5, 33),
(85, '2022-2023', 'Integrate workplace', 'CCMIA601', '2', 2, '2', 6, 33),
(86, '2022-2023', 'Occupational safety and health activities    ', 'CCMSH601 ', '1', 1, '2', 6, 33),
(87, '2022-2023', 'workplace environmental impact', 'CCMEM701', '3', 1, '2', 5, 40),
(88, '2022-2023', 'workplace environmental impact', 'CCMEM701', '3', 1, '2', 5, 39),
(89, '2022-2023', 'Geotechnical engineering', 'CSTGE701', '3', 1, '2', 5, 26),
(90, '2022-2023', 'Timber structures ', 'CSTTS701', '3', 1, '2', 5, 38),
(91, '2022-2023', 'Steel structures', 'CSTSS701', '3', 1, '2', 5, 38),
(92, '2022-2023', 'workplace environmental impact', 'CCMEM701', '3', 1, '3', 5, 40),
(93, '2022-2023', 'workplace environmental impact', 'CCMEM701', '3', 1, '3', 5, 39),
(94, '2022-2023', 'Geotechnical engineering', 'CSTGE701', '3', 1, '3', 5, 26),
(95, '2022-2023', 'Timber structures ', 'CSTTS701', '3', 1, '3', 5, 38),
(96, '2022-2023', 'Steel structures', 'CSTSS701', '3', 1, '3', 5, 38),
(97, '2022-2023', 'Introduction to Plant Biotechnology', 'CRPBI601', '2', 1, '1', 2, 15),
(98, '2022-2023', 'Manage a greenhouse', 'CRPGM602', '2', 1, '1', 2, 15),
(99, '2022-2023', 'Climate change risk management strategies', 'CRPCM701', '3', 2, '1', 2, 15),
(100, '2022-2023', 'Microbiology', 'CRPMB601', '1', 1, '1', 2, 15),
(101, '2022-2023', 'Plant Physiology', 'CRPPP601', '1', 1, '1', 2, 15),
(102, '2022-2023', 'Introduction to soil science and survey', 'CRPSS601', '1', 1, '1', 2, 16),
(103, '2022-2023', 'Horticulture practices', 'CRPHP601', '2', 1, '1', 2, 18),
(104, '2022-2023', 'Nursery Management', 'CRPNM 601', '1', 2, '1', 2, 18),
(105, '2022-2023', 'Agriculture value chain analysis ', 'CRPVS701', '3', 1, '1', 2, 18),
(106, '2022-2023', 'Agriculture Extension Coordination', 'CRPAE701', '1', 2, '1', 2, 18),
(107, '2022-2023', 'Agricultural input commercialization', 'CRPAC601', '2', 2, '1', 2, 24),
(108, '2022-2023', 'Basics of surveying and mapping', 'CRPSM601', '2', 1, '1', 2, 24),
(109, '2022-2023', 'Introduction to ecology', 'CRPIE601', '1', 1, '1', 2, 24),
(110, '2022-2023', 'Landscape development', 'CRPAW601', '2', 1, '1', 2, 24),
(111, '2022-2023', 'Crop production practices', 'CRPCP601', '1', 2, '1', 2, 12),
(112, '2022-2023', 'Irrigation and drainage', 'CRPID601', '1', 2, '1', 2, 9),
(113, '2022-2023', 'Introduction to Plant Biotechnology', 'CRPBI601', '2', 1, '2', 2, 15),
(114, '2022-2023', 'Manage a greenhouse', 'CRPGM602', '2', 1, '2', 2, 15),
(115, '2022-2023', 'Climate change risk management strategies', 'CRPCM701', '3', 2, '2', 2, 15),
(116, '2022-2023', 'Microbiology', 'CRPMB601', '1', 1, '2', 2, 15),
(117, '2022-2023', 'Plant Physiology', 'CRPPP601', '1', 1, '2', 2, 15),
(118, '2022-2023', 'Introduction to soil science and survey', 'CRPSS601', '1', 1, '2', 2, 16),
(119, '2022-2023', 'Horticulture practices', 'CRPHP601', '2', 1, '2', 2, 18),
(120, '2022-2023', 'Nursery Management', 'CRPNM 601', '1', 2, '2', 2, 18),
(121, '2022-2023', 'Agriculture value chain analysis ', 'CRPVS701', '3', 1, '2', 2, 18),
(122, '2022-2023', 'Agriculture Extension Coordination', 'CRPAE701', '1', 2, '2', 2, 18),
(123, '2022-2023', 'Agricultural input commercialization', 'CRPAC601', '2', 2, '2', 2, 24),
(124, '2022-2023', 'Basics of surveying and mapping', 'CRPSM601', '2', 1, '2', 2, 24),
(125, '2022-2023', 'Introduction to ecology', 'CRPIE601', '1', 1, '2', 2, 24),
(126, '2022-2023', 'Landscape development', 'CRPAW601', '2', 1, '2', 2, 24),
(127, '2022-2023', 'Crop production practices', 'CRPCP601', '1', 2, '2', 2, 12),
(128, '2022-2023', 'Irrigation and drainage', 'CRPID601', '1', 2, '2', 2, 9),
(129, '2022-2023', ' Hydrological principles', 'IDTPH601', '1', 1, '1', 3, 22),
(130, '2022-2023', 'Design micro-water harvesting structure', 'IDTMH601', '2', 1, '1', 3, 22),
(131, '2022-2023', 'Install micro water harvesting structure', 'IDTIM601', '1', 2, '1', 3, 22),
(132, '2022-2023', 'Apply soil mechanics', 'IDTSM601', '1', 2, '1', 3, 22),
(133, '2022-2023', 'Install small scale irrigation system', 'IDTII601', '2', 1, '1', 3, 22),
(134, '2022-2023', 'Supervise surface irrigation maintenance', 'IDTMI601', '2', 1, '1', 3, 23),
(135, '2022-2023', 'Design of surface irrigation system ', 'IDTDS601', '2', 1, '1', 3, 23),
(136, '2022-2023', 'Design surface drainage system', 'IDTSD601', '2', 1, '1', 3, 23),
(137, '2022-2023', ' Supervise irrigation development activities ', 'IDTID701', '3', 2, '1', 3, 23),
(138, '2022-2023', 'Maintain drainage system', 'IDTMD601', '2', 1, '1', 3, 23),
(139, '2022-2023', 'Determination of hydraulic parameters for Irrigati', 'IDTHP601', '1', 1, '1', 3, 23),
(140, '2022-2023', 'Occupation and learning process', 'CCMOL001', '1', 1, '1', 3, 6),
(141, '2022-2023', 'Provide irrigation equipment sales and services', 'IDTES701', '3', 2, '1', 3, 6),
(142, '2022-2023', 'Manage irrigation extension activities', 'IDTME701', '3', 1, '1', 3, 6),
(143, '2022-2023', 'Develop irrigation system maintenance and monitori', 'IDTIM701', '3', 2, '1', 3, 6),
(144, '2022-2023', 'Troubleshoot irrigation and drainage system', 'IDTTI601', '2', 1, '1', 3, 6),
(145, '2022-2023', 'Monitor irrigation and drainage system', 'IDTID601', '2', 2, '1', 3, 6),
(146, '2022-2023', 'Perform crop production practices', 'CRPCP601', '1', 2, '1', 2, 12),
(147, '2022-2023', 'Final year reseach project ', 'IDTFP601', '3', 1, '1', 3, 12),
(148, '2022-2023', 'Apply basic cropping practices ', 'IDTCP601', '1', 1, '1', 3, 12),
(149, '2022-2023', 'Establish Drainage system', 'IDTED601', '1', 2, '1', 3, 9),
(150, '2022-2023', 'Engineering Ethics', 'CCMEE601', '1', 1, '1', 3, 9),
(151, '2022-2023', 'Irrigation and drainage', 'CRPID601', '1', 2, '1', 2, 9),
(152, '2022-2023', 'Maintain Irrigation and Drainage tools and equipme', 'IDTTE601', '1', 2, '1', 3, 9),
(153, '2022-2023', 'Planning Small Scale Irrigation System', 'IDTSI601', '1', 2, '1', 3, 17),
(154, '2022-2023', 'Supervision of Pressurized Irrigation Systems Main', ' IDTPI601', '2', 2, '1', 3, 17),
(155, '2022-2023', 'Operation of Irrigation System', 'IDTOI601', '2', 1, '1', 3, 17),
(156, '2022-2023', 'Evaluate irrigation system performance', 'IDTIP701', '3', 2, '1', 3, 17),
(157, '2022-2023', 'Design of irrigation system ', 'IDTDI701', '3', 1, '1', 3, 17),
(158, '2022-2023', 'Design of Pressurized Irrigation systems at small ', 'IDTPS601', '2', 1, '1', 3, 17),
(159, '2022-2023', 'Fundemental Electronics', 'ETTFE601', '1', 1, '1', 8, 34),
(160, '2022-2023', ' Industrial attachment ', 'CCMEM60', '3', 2, '1', 3, 34),
(161, '2022-2023', 'Basic concepts of electricity and automation', 'IDTEA601', '1', 1, '1', 3, 34),
(162, '2022-2023', ' Hydrological principles', 'IDTPH601', '1', 1, '2', 3, 22),
(163, '2022-2023', 'Install micro water harvesting structure', 'IDTIM601', '1', 2, '2', 3, 22),
(164, '2022-2023', 'Apply soil mechanics', 'IDTSM601', '1', 2, '2', 3, 22),
(165, '2022-2023', ' Supervise irrigation development activities ', 'IDTID701', '3', 2, '2', 3, 23),
(166, '2022-2023', 'Determination of hydraulic parameters for Irrigati', 'IDTHP601', '1', 1, '2', 3, 23),
(167, '2022-2023', 'Occupation and learning process', 'CCMOL001', '1', 1, '2', 3, 6),
(168, '2022-2023', 'Provide irrigation equipment sales and services', 'IDTES701', '3', 2, '2', 3, 6),
(169, '2022-2023', 'Manage irrigation extension activities', 'IDTME701', '3', 1, '2', 3, 6),
(170, '2022-2023', 'Develop irrigation system maintenance and monitori', 'IDTIM701', '3', 2, '2', 3, 6),
(171, '2022-2023', 'Perform crop production practices', 'CRPCP601', '1', 2, '2', 2, 12),
(172, '2022-2023', 'Final year reseach project ', 'IDTFP601', '3', 1, '2', 3, 12),
(173, '2022-2023', 'Apply basic cropping practices ', 'IDTCP601', '1', 1, '2', 3, 12),
(174, '2022-2023', 'Establish Drainage system', 'IDTED601', '1', 2, '2', 3, 9),
(175, '2022-2023', 'Engineering Ethics', 'CCMEE601', '1', 1, '2', 3, 9),
(176, '2022-2023', 'Irrigation and drainage', 'CRPID601', '1', 2, '2', 2, 9),
(177, '2022-2023', 'Maintain Irrigation and Drainage tools and equipme', 'IDTTE601', '1', 2, '2', 3, 9),
(178, '2022-2023', 'Planning Small Scale Irrigation System', 'IDTSI601', '1', 2, '2', 3, 17),
(179, '2022-2023', 'Evaluate irrigation system performance', 'IDTIP701', '3', 2, '2', 3, 17),
(180, '2022-2023', 'Design of irrigation system ', 'IDTDI701', '3', 1, '2', 3, 17),
(181, '2022-2023', 'Fundemental Electronics', 'ETTFE601', '1', 1, '2', 8, 34),
(182, '2022-2023', ' Industrial attachment ', 'CCMEM60', '3', 2, '2', 3, 34),
(183, '2022-2023', 'Basic concepts of electricity and automation', 'IDTEA601', '1', 1, '2', 3, 34);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teacher`
--

CREATE TABLE IF NOT EXISTS `tbl_teacher` (
  `teacher_id` int(10) unsigned NOT NULL,
  `teacher_first_name` varchar(30) NOT NULL,
  `teacher_last_name` varchar(30) NOT NULL,
  `teacher_emp_code` varchar(20) DEFAULT NULL,
  `teacher_department_id` int(20) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=87 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_teacher`
--

INSERT INTO `tbl_teacher` (`teacher_id`, `teacher_first_name`, `teacher_last_name`, `teacher_emp_code`, `teacher_department_id`) VALUES
(1, 'NIYONSHIMA', 'Protais', NULL, 1),
(2, 'ISHIMWE', 'Gabrielle', NULL, 2),
(3, 'MUKUNZI', 'Daniel', NULL, 2),
(4, 'UWIZEYIMANA', 'Marie Josee', NULL, 2),
(5, 'UZABAKIRIHO', 'Thierry', NULL, 2),
(6, 'KAMALI', 'Jean de Dieu', NULL, 2),
(7, 'MUSANABAGANWA', 'Stephanie', NULL, 2),
(8, 'RUHUMURIZA', 'Patrick', NULL, 2),
(9, 'RUKUNDO', 'Jean Pierre', NULL, 2),
(10, 'BYUKUSENGE', 'Epaphrodite', NULL, 2),
(11, 'HAVUGIMANA Jean', 'Damascene Salvator', NULL, 2),
(12, 'MAJUGA', 'Jean Claude Noel', NULL, 2),
(13, 'MAZIMPAKA', 'Diocles', NULL, 2),
(14, 'MUGISHA', 'Janvier', NULL, 2),
(15, 'MUNYESHYAKA', 'Ildephonse', NULL, 2),
(16, 'NGENDO', 'Clement', NULL, 2),
(17, 'NIRAGIRE', 'Theophile', NULL, 2),
(18, 'NIYONSENGA', 'Evergiste', NULL, 2),
(19, 'NTIRENGANYA', 'Noe Cyprien', NULL, 2),
(20, 'TUYAMBAZE', 'Africain', NULL, 2),
(22, 'TUYISHIME', 'Herve Christian', NULL, 2),
(23, 'UWERA', 'Clotilde', NULL, 2),
(24, 'IRADUKUNDA', 'Angelique', NULL, 2),
(25, 'UMUHOZA', 'Edgard', NULL, 3),
(26, 'HAFASHIMANA', 'Felix', NULL, 3),
(27, 'IRAGENA', 'Aphrodice', NULL, 3),
(28, 'NKUNDABAGENZI', 'Jeremie', NULL, 3),
(29, 'NZEYIMANA', 'Theogene', NULL, 3),
(30, 'UWIZEYE', 'Josette', NULL, 3),
(31, 'GAPFIZI', 'Pierre', NULL, 3),
(32, 'MUNYANGANIZI', 'Gaspard', NULL, 3),
(33, 'MUPENZI', 'Desailly', NULL, 3),
(34, 'NGABITSINZE', 'Jean Bosco', NULL, 3),
(35, 'NGABOYIMANA', 'Thierry', NULL, 3),
(36, 'NIZERIMANA', 'Emmanuel', NULL, 3),
(37, 'NIZEYIMANA', 'Ladislas', NULL, 3),
(38, 'NSABIMANA', 'Innocent', NULL, 3),
(39, 'TUYISENGE', 'Alphonse', NULL, 3),
(40, 'TWAGIRAYEZU', 'Jean Paul', NULL, 3),
(41, 'HAKIZIMANA', 'Boniface', NULL, 4),
(42, 'MANIRIHO', 'Sylvestre', NULL, 4),
(43, 'MISAGO', 'John Fredy', NULL, 4),
(44, 'NDAMYUMUGABE', 'Moise', NULL, 4),
(45, 'NDAYISENGA', 'Emmanuel', NULL, 4),
(46, 'AMAHIRWE', 'Jean Claude', NULL, 4),
(47, 'DUSHIMIMANA', 'Gilbert', NULL, 4),
(48, 'ISHIMWE', 'Viviane', NULL, 4),
(49, 'MUNYANEZA', 'Odifax', NULL, 4),
(50, 'NKUNDINEZA', 'Beloved', NULL, 4),
(51, 'SAMVURA', 'Jean de Dieu', NULL, 4),
(52, 'AYIBUGOYI', 'Jean Paul', NULL, 4),
(53, 'HABIMANA', 'Jean de Dieu', NULL, 1),
(54, 'HAHIRWUWIZERA', 'Chrispine', NULL, 1),
(55, 'HAKIZIMANA', 'Emmanuel', NULL, 1),
(56, 'KABANDA', 'Jean de Dieu', NULL, 1),
(57, 'KAREKEZI', 'Dieudonnee', NULL, 1),
(58, 'MUKADISI', 'Florence', NULL, 1),
(59, 'NIYIGABA', 'Emmanuel', NULL, 1),
(60, 'NKUNDIMANA', 'Jean Pierre', NULL, 1),
(61, 'MUKESHIMANA', 'Eric', NULL, 1),
(62, 'SIBOMANA', 'Papi', NULL, 1),
(63, 'UMUHIRE', 'Jerome', NULL, 1),
(64, 'UMUTONI', 'Clemence', NULL, 5),
(65, 'SIBOMANA', 'Jean Bosco', NULL, 5),
(66, 'HABIMANA', 'Jean Claude', NULL, 5),
(67, 'HABUMUREMYI', 'Faustin', NULL, 5),
(68, 'MBUNGIRA', 'Jean', NULL, 5),
(69, 'MUGABO', 'Clement', NULL, 5),
(70, 'MUNYANZIZA', 'Eugene', NULL, 5),
(71, 'MUYISHIME', 'Christine', NULL, 5),
(72, 'NIYIGENA', 'SHEMA Eric', NULL, 5),
(73, 'SEMARORA', 'Eric', NULL, 5),
(74, 'TURATSINZE', 'Pacifique', NULL, 5),
(75, 'TWIZERIMANA', 'Theogene', NULL, 5),
(76, 'UWAMAHORO', 'Euphrasie Raissa', NULL, 5),
(77, 'IRABARUTA', 'Emmanuel', NULL, 6),
(78, 'MBABAZI', 'Mary', NULL, 6),
(79, 'HITAYEZU', 'Emile', NULL, 6),
(80, 'MBONIGABA', 'Javan', NULL, 6),
(81, 'MUNYANEZA', 'Alphonse', NULL, 6),
(82, 'HODARI', 'Audace', NULL, 6),
(83, 'KUBWUMUKIZA', 'Seth', NULL, 6),
(84, 'NIYITEGEKA', 'Jean Joas', NULL, 6),
(85, 'UWINGABIRE', 'Brice Raissa', NULL, 6),
(86, 'NSANZIMANA', 'Patrick', NULL, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_t_departments`
--

CREATE TABLE IF NOT EXISTS `tbl_t_departments` (
  `dpt_id` int(10) unsigned NOT NULL,
  `dpt_code` varchar(10) NOT NULL,
  `dpt_name` varchar(50) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_t_departments`
--

INSERT INTO `tbl_t_departments` (`dpt_id`, `dpt_code`, `dpt_name`) VALUES
(1, 'GC', 'General Courses'),
(2, 'AE', 'Agricultural Engineering'),
(3, 'CE', 'Civil Engineering'),
(4, 'EEE', 'Electrical and Electronics Engineering'),
(5, 'HM', 'Hospitality Management'),
(6, 'ICT', 'Information and Communication Technology');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `u_id` int(10) unsigned NOT NULL,
  `u_fname` varchar(30) DEFAULT NULL,
  `u_lname` varchar(30) DEFAULT NULL,
  `u_gender` varchar(10) NOT NULL,
  `u_uname` varchar(30) DEFAULT NULL,
  `u_pass` varchar(64) DEFAULT NULL,
  `u_utype` varchar(20) DEFAULT NULL,
  `u_phone` varchar(15) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`u_id`, `u_fname`, `u_lname`, `u_gender`, `u_uname`, `u_pass`, `u_utype`, `u_phone`) VALUES
(1, 'Blaise', 'YONKURU', 'Male', 'admin', '6debe888562d1671b4c16e715394c520', 'admin', '0783821750'),
(10, 'Quality', 'Assurance', 'Female', 'quality', 'd66636b253cb346dbb6240e30def3618', 'quality', '0788888888'),
(11, 'Admin', 'Musanze', 'Male', 'mAdmin', '86e63cf26e27b0f0f0cbae3412c40f80', 'coladmin', '0783827594');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classwise_history`
--
ALTER TABLE `classwise_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staffwise_history`
--
ALTER TABLE `staffwise_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_answer`
--
ALTER TABLE `tbl_answer`
  ADD PRIMARY KEY (`answer_id`);

--
-- Indexes for table `tbl_classes`
--
ALTER TABLE `tbl_classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `tbl_codes`
--
ALTER TABLE `tbl_codes`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `code` (`code`), ADD UNIQUE KEY `code_2` (`code`);

--
-- Indexes for table `tbl_comments`
--
ALTER TABLE `tbl_comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `tbl_department`
--
ALTER TABLE `tbl_department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `tbl_levels`
--
ALTER TABLE `tbl_levels`
  ADD PRIMARY KEY (`level_id`);

--
-- Indexes for table `tbl_quality`
--
ALTER TABLE `tbl_quality`
  ADD PRIMARY KEY (`quality_id`);

--
-- Indexes for table `tbl_subject`
--
ALTER TABLE `tbl_subject`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `tbl_teacher`
--
ALTER TABLE `tbl_teacher`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indexes for table `tbl_t_departments`
--
ALTER TABLE `tbl_t_departments`
  ADD PRIMARY KEY (`dpt_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classwise_history`
--
ALTER TABLE `classwise_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `staffwise_history`
--
ALTER TABLE `staffwise_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_answer`
--
ALTER TABLE `tbl_answer`
  MODIFY `answer_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbl_classes`
--
ALTER TABLE `tbl_classes`
  MODIFY `class_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `tbl_codes`
--
ALTER TABLE `tbl_codes`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_comments`
--
ALTER TABLE `tbl_comments`
  MODIFY `comment_id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_department`
--
ALTER TABLE `tbl_department`
  MODIFY `department_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  MODIFY `feedback_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_levels`
--
ALTER TABLE `tbl_levels`
  MODIFY `level_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `tbl_quality`
--
ALTER TABLE `tbl_quality`
  MODIFY `quality_id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `tbl_subject`
--
ALTER TABLE `tbl_subject`
  MODIFY `subject_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=186;
--
-- AUTO_INCREMENT for table `tbl_teacher`
--
ALTER TABLE `tbl_teacher`
  MODIFY `teacher_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=87;
--
-- AUTO_INCREMENT for table `tbl_t_departments`
--
ALTER TABLE `tbl_t_departments`
  MODIFY `dpt_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `u_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
