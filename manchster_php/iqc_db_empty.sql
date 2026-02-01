-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2026 at 05:49 AM
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
-- Database: `iqc_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `atps_electronic_systems`
--

CREATE TABLE `atps_electronic_systems` (
  `platform_id` int(11) NOT NULL,
  `platform_name` text DEFAULT NULL,
  `platform_purpose` text DEFAULT NULL,
  `atp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atps_form_init`
--

CREATE TABLE `atps_form_init` (
  `form_id` int(11) NOT NULL,
  `atp_id` int(11) NOT NULL,
  `added_date` datetime DEFAULT NULL,
  `est_name` text DEFAULT NULL,
  `est_name_ar` text DEFAULT NULL,
  `iqa_name` text DEFAULT NULL,
  `email_address` text DEFAULT NULL,
  `registration_no` text DEFAULT NULL,
  `registration_expiry` date DEFAULT NULL,
  `delivery_plan` text DEFAULT NULL,
  `org_chart` text DEFAULT NULL,
  `site_plan` text DEFAULT NULL,
  `sed_form` text DEFAULT NULL,
  `atp_logo` text DEFAULT NULL,
  `atp_category_id` int(11) DEFAULT NULL,
  `emirate_id` int(11) DEFAULT NULL,
  `area_name` text DEFAULT NULL,
  `street_name` text DEFAULT NULL,
  `building_name` text DEFAULT NULL,
  `submitted_date` datetime DEFAULT NULL,
  `is_submitted` int(1) NOT NULL DEFAULT 0,
  `form_status` text DEFAULT 'pending_submission',
  `rc_comment` text DEFAULT NULL,
  `is_renew` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atps_info_request`
--

CREATE TABLE `atps_info_request` (
  `request_id` int(11) NOT NULL,
  `request_date` datetime NOT NULL,
  `response_date` datetime DEFAULT NULL,
  `request_department` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `request_status` varchar(20) NOT NULL DEFAULT 'pending_submission',
  `atp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atps_info_request_evs`
--

CREATE TABLE `atps_info_request_evs` (
  `evidence_id` int(11) NOT NULL,
  `required_evidence` text DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `required_attachment` text DEFAULT NULL,
  `request_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atps_learners_statistics`
--

CREATE TABLE `atps_learners_statistics` (
  `statistic_id` int(11) NOT NULL,
  `qualification_id` int(11) NOT NULL,
  `y1_value` int(10) NOT NULL,
  `y2_value` int(10) NOT NULL,
  `y3_value` int(10) NOT NULL,
  `y4_value` int(10) NOT NULL,
  `statistic_type` varchar(15) NOT NULL COMMENT 'registered, awarded',
  `atp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atps_list`
--

CREATE TABLE `atps_list` (
  `atp_id` int(11) NOT NULL,
  `atp_logo` varchar(70) DEFAULT 'no-img.png',
  `atp_ref` varchar(255) NOT NULL,
  `atp_name` varchar(255) NOT NULL,
  `atp_name_ar` text DEFAULT NULL,
  `contact_name` varchar(255) NOT NULL,
  `atp_email` varchar(255) NOT NULL,
  `atp_phone` varchar(255) NOT NULL,
  `emirate_id` int(11) NOT NULL DEFAULT 1,
  `area_name` varchar(100) DEFAULT NULL,
  `street_name` varchar(100) DEFAULT NULL,
  `building_name` varchar(100) DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `atp_category_id` int(11) NOT NULL,
  `atp_type_id` int(11) NOT NULL,
  `atp_status_id` int(11) NOT NULL DEFAULT 1,
  `phase_id` int(11) NOT NULL DEFAULT 1,
  `is_phase_ok` int(1) NOT NULL DEFAULT 1,
  `todo_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atps_list_categories`
--

CREATE TABLE `atps_list_categories` (
  `atp_category_id` int(11) NOT NULL,
  `atp_category_name` varchar(255) DEFAULT NULL,
  `atp_category_name_ar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atps_list_categories`
--

INSERT INTO `atps_list_categories` (`atp_category_id`, `atp_category_name`, `atp_category_name_ar`) VALUES
(1, 'Government', 'حكومي'),
(2, 'Semi. Government', 'شبه جكومي'),
(3, 'Private', 'قطاع خاص');

-- --------------------------------------------------------

--
-- Table structure for table `atps_list_contacts`
--

CREATE TABLE `atps_list_contacts` (
  `contact_id` int(11) NOT NULL,
  `contact_name` text NOT NULL,
  `contact_phone` text NOT NULL,
  `contact_email` text NOT NULL,
  `contact_designation` text DEFAULT NULL,
  `atp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atps_list_faculties`
--

CREATE TABLE `atps_list_faculties` (
  `faculty_id` int(11) NOT NULL,
  `faculty_name` text DEFAULT NULL,
  `faculty_type_id` int(11) DEFAULT NULL,
  `educational_qualifications` text DEFAULT NULL,
  `years_experience` text DEFAULT NULL,
  `certificate_name` text DEFAULT NULL,
  `faculty_cv` text DEFAULT NULL,
  `faculty_certificate` text DEFAULT NULL,
  `atp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atps_list_faculties_types`
--

CREATE TABLE `atps_list_faculties_types` (
  `faculty_type_id` int(11) NOT NULL,
  `faculty_type_name` varchar(150) NOT NULL,
  `faculty_type_name_ar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atps_list_faculties_types`
--

INSERT INTO `atps_list_faculties_types` (`faculty_type_id`, `faculty_type_name`, `faculty_type_name_ar`) VALUES
(1, 'Trainer', 'Trainer'),
(2, 'Assessor', 'Assessor'),
(3, 'Internal Quality Assure', 'Internal Quality Assure');

-- --------------------------------------------------------

--
-- Table structure for table `atps_list_logs`
--

CREATE TABLE `atps_list_logs` (
  `log_id` int(11) NOT NULL,
  `atp_id` int(11) NOT NULL,
  `log_action` text DEFAULT NULL,
  `log_date` datetime DEFAULT NULL,
  `logger_type` varchar(255) NOT NULL,
  `log_dept` varchar(20) NOT NULL,
  `logged_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atps_list_pass`
--

CREATE TABLE `atps_list_pass` (
  `pass_id` int(11) NOT NULL,
  `pass_value` varchar(70) NOT NULL,
  `atp_id` int(11) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atps_list_qualifications`
--

CREATE TABLE `atps_list_qualifications` (
  `qualification_id` int(11) NOT NULL,
  `qualification_type` text DEFAULT NULL,
  `qualification_name` text DEFAULT NULL,
  `qualification_category` text DEFAULT NULL,
  `emirates_level` text DEFAULT NULL,
  `qulaification_credits` text DEFAULT NULL,
  `mode_of_delivery` text DEFAULT NULL,
  `atp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atps_list_qualifications_faculties`
--

CREATE TABLE `atps_list_qualifications_faculties` (
  `record_id` int(11) NOT NULL,
  `qualification_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `atp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atps_list_requests`
--

CREATE TABLE `atps_list_requests` (
  `request_id` int(11) NOT NULL,
  `request_name` text DEFAULT NULL,
  `request_name_ar` text DEFAULT NULL,
  `added_date` datetime DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `is_finished` int(1) NOT NULL DEFAULT 0,
  `atp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atps_list_status`
--

CREATE TABLE `atps_list_status` (
  `atp_status_id` int(11) NOT NULL,
  `atp_status_name` varchar(255) DEFAULT NULL,
  `atp_status_name_ar` text DEFAULT NULL,
  `atp_status_description` text DEFAULT NULL,
  `atp_status_description_ar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atps_list_status`
--

INSERT INTO `atps_list_status` (`atp_status_id`, `atp_status_name`, `atp_status_name_ar`, `atp_status_description`, `atp_status_description_ar`) VALUES
(1, 'pending email', 'بانتظار إرسال البريد الإلكتروني', 'Pending registration link to be sent to the ATP', 'بانتظار إرسال رابط التسجيل عبر البريد الإلكتروني'),
(2, 'pending', 'بانتظار تعبئة نموذج التسجيل الأولي', 'Pending Atp to fill initial registration form', 'بانتظار تعبئة نموذج التسجيل الأولي'),
(3, 'Accredited', 'Accredited', 'Accredited', 'Accredited');

-- --------------------------------------------------------

--
-- Table structure for table `atps_list_types`
--

CREATE TABLE `atps_list_types` (
  `atp_type_id` int(11) NOT NULL,
  `atp_type_name` text DEFAULT NULL,
  `atp_type_name_ar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atps_list_types`
--

INSERT INTO `atps_list_types` (`atp_type_id`, `atp_type_name`, `atp_type_name_ar`) VALUES
(1, 'Open', 'Open ATP'),
(2, 'Restricted', 'Closed ATP'),
(3, 'Confidential', 'Closed ATP');

-- --------------------------------------------------------

--
-- Table structure for table `atps_prog_register_req`
--

CREATE TABLE `atps_prog_register_req` (
  `form_id` int(11) NOT NULL,
  `atp_id` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `submitted_date` datetime NOT NULL,
  `is_submitted` int(11) NOT NULL,
  `form_status` text NOT NULL,
  `rc_comment` text DEFAULT NULL,
  `is_renew` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `token_id` int(11) NOT NULL,
  `token_value` varchar(80) DEFAULT NULL,
  `token_date` datetime DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `token_idder` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `a_registration_phases`
--

CREATE TABLE `a_registration_phases` (
  `phase_id` int(11) NOT NULL,
  `phase_title` text DEFAULT NULL,
  `phase_description` text DEFAULT NULL,
  `phase_timeline` text DEFAULT NULL,
  `table_check` text DEFAULT NULL,
  `qualification_order` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `a_registration_phases`
--

INSERT INTO `a_registration_phases` (`phase_id`, `phase_title`, `phase_description`, `phase_timeline`, `table_check`, `qualification_order`) VALUES
(1, 'Initial Request for Registration', 'Check and update that all information entered is accurate.', '2 working days to proceed to the next level of verification.', 'atps_form_init', 1),
(2, 'Program Registration', 'Complete all the required fields and upload the necessary documents as per the ATP registration guidelines along with the T&C', '2 working days to proceed to the next level of verification.', NULL, 2),
(3, 'EQA', 'All documents submitted will be reviewed and evaluated. Arrangements will be coordinated for your EQA visit and a visit planner will be sent to support your accreditation preparation. EQA reports will be prepared.', 'will be shared within 12 working days to proceed to the next stage.', NULL, 6),
(4, 'Get Accredited', 'An official accreditation letter and IQC registration certificate will be provided to successful training providers.', 'This process will be completed within  5 working days once the qualification registration process is completed.', NULL, 7);

-- --------------------------------------------------------

--
-- Table structure for table `a_registration_phases_todos`
--

CREATE TABLE `a_registration_phases_todos` (
  `todo_id` int(11) NOT NULL,
  `todo_title` text DEFAULT NULL,
  `todo_description` text DEFAULT NULL,
  `is_submit` int(1) NOT NULL DEFAULT 0,
  `todo_link` text NOT NULL,
  `table_check` varchar(50) DEFAULT NULL,
  `col_check` varchar(50) DEFAULT NULL,
  `phase_id` int(11) NOT NULL,
  `is_hidden` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `a_registration_phases_todos`
--

INSERT INTO `a_registration_phases_todos` (`todo_id`, `todo_title`, `todo_description`, `is_submit`, `todo_link`, `table_check`, `col_check`, `phase_id`, `is_hidden`) VALUES
(1, 'Initial Form', NULL, 0, '../../accreditation/initial_request/?form=initial_form', 'atps_form_init', NULL, 1, 0),
(3, 'Targeted Qualifications', NULL, 0, '../../accreditation/initial_request/?form=targeted_qualifications', 'atps_list_qualifications', NULL, 1, 0),
(4, 'Faculty Details', NULL, 0, '../../accreditation/initial_request/?form=faculty_details', 'atps_list_faculties', NULL, 1, 0),
(5, 'Learners Statistics', NULL, 0, '../../accreditation/initial_request/?form=learners_statistics', 'atps_learners_statistics', NULL, 1, 0),
(6, 'Electronic Systems & Platforms', NULL, 0, '../../accreditation/initial_request/?form=electronic_systems', 'atps_electronic_systems', NULL, 1, 0),
(7, 'Attachments', NULL, 0, '../../accreditation/initial_request/?form=attachments', 'atps_form_init', 'delivery_plan', 1, 0),
(8, 'Submit', NULL, 1, '../../accreditation/initial_request/?form=submit', NULL, NULL, 1, 0),
(10, 'Faculty Details', NULL, 0, '../../accreditation/program_registration/?form=faculty_details', 'atps_list_faculties', NULL, 2, 0),
(11, 'Qualifications Mapping', NULL, 0, '../../accreditation/program_registration/?form=qualification_mapping', 'atps_list_qualifications_faculties', NULL, 2, 0),
(12, 'Submission', NULL, 1, '../../accreditation/program_registration/?form=submit', NULL, NULL, 2, 0),
(13, 'CVs Submissions', NULL, 0, '../app_init/faculty_details/?stage=1', NULL, NULL, 3, 0),
(14, 'Submission', NULL, 1, '../app_init/faculty_details/?stage=2', NULL, NULL, 3, 0),
(17, 'Organization Profile', NULL, 0, '../app_init/self_assess/?stage=2', NULL, NULL, 4, 0),
(18, 'Key Performance Indicators', NULL, 0, '../app_init/self_assess/?stage=3', NULL, NULL, 4, 0),
(19, 'Prospective ATP Evidence against the ATPQS', NULL, 0, '../app_init/sed_compliance/', NULL, NULL, 4, 0),
(20, 'Quality Improvement Plan (QIP)', NULL, 0, '../app_init/self_assess/?stage=5', NULL, NULL, 4, 0),
(21, 'Submission', NULL, 1, '../app_init/self_assess/?stage=8', NULL, NULL, 4, 0),
(23, 'Select Visit Date', NULL, 0, '../app_init/eqa_visit/?stage=1', NULL, NULL, 5, 0),
(24, 'Submission', NULL, 1, '../app_init/eqa_visit/?stage=2', NULL, NULL, 5, 0),
(25, 'Qualifications Registration', NULL, 0, '../app_init/qualifications_register/?stage=1', NULL, NULL, 6, 0),
(26, 'Qualifications Details', NULL, 0, '../app_init/qualifications_register/?stage=2', NULL, NULL, 6, 0),
(27, 'Faculty Details', NULL, 0, '../app_init/qualifications_register/?stage=3', NULL, NULL, 6, 0),
(28, 'Qualifications Evidence', NULL, 0, '../app_init/qualifications_register/?stage=4', NULL, NULL, 6, 0),
(29, 'Submission', NULL, 1, '../app_init/qualifications_register/?stage=5', NULL, NULL, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `employees_list`
--

CREATE TABLE `employees_list` (
  `employee_id` int(11) NOT NULL,
  `employee_no` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `second_name` varchar(50) DEFAULT NULL,
  `third_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `employee_dob` date DEFAULT NULL,
  `employee_code` varchar(30) NOT NULL,
  `employee_type` varchar(20) NOT NULL DEFAULT 'local' COMMENT 'local, hire',
  `title_id` int(11) NOT NULL DEFAULT 3,
  `gender_id` int(11) NOT NULL DEFAULT 1,
  `nationality_id` int(11) NOT NULL DEFAULT 224,
  `timezone_id` int(11) NOT NULL DEFAULT 263,
  `employee_email` varchar(50) NOT NULL,
  `employee_picture` varchar(65) NOT NULL DEFAULT 'user.png',
  `department_id` int(11) NOT NULL DEFAULT 1,
  `designation_id` int(11) NOT NULL,
  `certificate_id` int(11) NOT NULL DEFAULT 1,
  `employee_join_date` date DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `leaves_open_balance` int(4) NOT NULL DEFAULT 0,
  `allowed_permission_hours` int(2) NOT NULL DEFAULT 8,
  `permission_hours_balance` int(2) NOT NULL DEFAULT 0,
  `is_deleted` int(1) NOT NULL DEFAULT 0,
  `is_hidden` int(1) NOT NULL DEFAULT 0,
  `is_group` int(1) NOT NULL DEFAULT 0,
  `is_committee` int(1) NOT NULL DEFAULT 0,
  `is_new` int(1) NOT NULL DEFAULT 1,
  `is_pass` int(1) NOT NULL DEFAULT 0,
  `emp_status_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `employees_list`
--

INSERT INTO `employees_list` (`employee_id`, `employee_no`, `first_name`, `second_name`, `third_name`, `last_name`, `employee_dob`, `employee_code`, `employee_type`, `title_id`, `gender_id`, `nationality_id`, `timezone_id`, `employee_email`, `employee_picture`, `department_id`, `designation_id`, `certificate_id`, `employee_join_date`, `company_name`, `leaves_open_balance`, `allowed_permission_hours`, `permission_hours_balance`, `is_deleted`, `is_hidden`, `is_group`, `is_committee`, `is_new`, `is_pass`, `emp_status_id`) VALUES
(1, NULL, 'IT Root', NULL, NULL, 'Admin', NULL, 'IT', 'local', 3, 1, 224, 263, 'it@root.com', 'user.png', 4, 4, 1, NULL, NULL, 0, 8, 0, 0, 1, 0, 0, 0, 0, 3),
(34, '123emp', 'emp', NULL, NULL, 'portal', NULL, 'EP', 'local', 3, 1, 224, 263, 'emp', 'user.png', 11, 7, 1, NULL, NULL, 0, 8, 7, 0, 0, 0, 0, 1, 1, 1),
(35, '132hr', 'hr', NULL, NULL, 'hr', NULL, 'HH', 'local', 3, 1, 224, 263, 'hrhr', 'user.png', 11, 7, 1, NULL, NULL, 0, 8, 0, 0, 0, 1, 0, 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `employees_list_creds`
--

CREATE TABLE `employees_list_creds` (
  `employee_credential_id` int(11) NOT NULL,
  `passport_issue_date` date DEFAULT NULL,
  `passport_expiry_date` date DEFAULT NULL,
  `passport_no` varchar(200) DEFAULT NULL,
  `visa_issue_date` date DEFAULT NULL,
  `visa_expiry_date` date DEFAULT NULL,
  `visa_no` varchar(500) DEFAULT NULL,
  `eid_issue_date` date DEFAULT NULL,
  `eid_expiry_date` date DEFAULT NULL,
  `eid_no` varchar(500) DEFAULT NULL,
  `labour_issue_date` date DEFAULT NULL,
  `labour_expiry_date` date DEFAULT NULL,
  `labour_no` varchar(200) DEFAULT NULL,
  `license_issue_date` date DEFAULT NULL,
  `license_expiry_date` date DEFAULT NULL,
  `license_no` varchar(500) DEFAULT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `employees_list_creds`
--

INSERT INTO `employees_list_creds` (`employee_credential_id`, `passport_issue_date`, `passport_expiry_date`, `passport_no`, `visa_issue_date`, `visa_expiry_date`, `visa_no`, `eid_issue_date`, `eid_expiry_date`, `eid_no`, `labour_issue_date`, `labour_expiry_date`, `labour_no`, `license_issue_date`, `license_expiry_date`, `license_no`, `employee_id`) VALUES
(7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34),
(8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35);

-- --------------------------------------------------------

--
-- Table structure for table `employees_list_departments`
--

CREATE TABLE `employees_list_departments` (
  `department_id` int(11) NOT NULL,
  `main_department_id` int(11) NOT NULL DEFAULT 0,
  `department_name` varchar(50) DEFAULT NULL,
  `department_code` varchar(20) DEFAULT NULL,
  `user_type` varchar(10) NOT NULL DEFAULT 'emp',
  `line_manager_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees_list_departments`
--

INSERT INTO `employees_list_departments` (`department_id`, `main_department_id`, `department_name`, `department_code`, `user_type`, `line_manager_id`) VALUES
(1, 0, 'IQC', 'IQC', 'emp', 35),
(4, 1, 'Information Technology', 'it', 'it', 35),
(10, 1, 'hr', 'hr', 'emp', 35),
(11, 1, 'Strategy', 'RC', 'emp', 34),
(12, 10, 'HR admin', '0', 'hr', 35),
(13, 1, 'test', 'test', 'emp', 35),
(14, 1, 'another Dept', '4334', 'emp', 35),
(15, 12, 'HR SUB ADMIN', '333', 'emp', 35),
(16, 15, 'HR sub sub', '3344', 'emp', 35),
(17, 1, 'dept03', '2323', 'emp', 35),
(18, 1, 'dept66', '233232', 'emp', 35),
(19, 11, 'sub emp', '333', 'emp', 35),
(20, 19, 'sub sub emp', '2332', 'emp', 35),
(21, 4, 'sub IT', '233223', 'emp', 35),
(22, 1, 'Strategy', 'STR', 'emp', 34);

-- --------------------------------------------------------

--
-- Table structure for table `employees_list_designations`
--

CREATE TABLE `employees_list_designations` (
  `designation_id` int(11) NOT NULL,
  `designation_name` varchar(50) DEFAULT NULL,
  `designation_code` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees_list_designations`
--

INSERT INTO `employees_list_designations` (`designation_id`, `designation_name`, `designation_code`) VALUES
(4, 'IT Admin', 'it'),
(6, 'hr admin', 'hr'),
(7, 'emp', 'emp');

-- --------------------------------------------------------

--
-- Table structure for table `employees_list_pass`
--

CREATE TABLE `employees_list_pass` (
  `pass_id` int(11) NOT NULL,
  `pass_value` varchar(70) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees_list_pass`
--

INSERT INTO `employees_list_pass` (`pass_id`, `pass_value`, `employee_id`, `is_active`) VALUES
(1, '$2y$10$vyDJsXaSPt03tXcY8z/lBuLHIEyTFxbHkA7eB2L1uA36d.VaXzs/e', 1, 1),
(13, '$2y$12$SgIRUg2tcMihdKwSFuIxM.UWXUR2gKQh6Vvo5pkVYsvchhV2Zir4G', 34, 0),
(14, '$2y$12$wDzYZXPAINUGXUFQVzDRcOJeATLDWGO3MvUibRJ8QNA9.K1tkltd.', 35, 1),
(16, '$2y$12$vyEAuUmt/kQLrGC4TNmRvez7l7kRfbjpP6kFYpLqF27z.5zf6IUqq', 34, 0),
(17, '$2y$12$VXpQP3rnxaQDe7QYpzsWPeH19EVkKTcxVM351yGPRARyQoWUFzr.m', 34, 0),
(22, '$2y$12$m2p.LNY6THXGAA6NyMqqZOCSYzEMAxnSJ1vW0T2ltHK.BVdjSNw3i', 34, 1);

-- --------------------------------------------------------

--
-- Table structure for table `employees_list_services`
--

CREATE TABLE `employees_list_services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(200) NOT NULL,
  `service_name_ar` varchar(200) NOT NULL,
  `service_title` varchar(200) DEFAULT NULL,
  `service_title_ar` varchar(200) DEFAULT NULL,
  `service_icon` varchar(50) NOT NULL,
  `service_link` text DEFAULT NULL,
  `order_no` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees_list_services`
--

INSERT INTO `employees_list_services` (`service_id`, `service_name`, `service_name_ar`, `service_title`, `service_title_ar`, `service_icon`, `service_link`, `order_no`) VALUES
(10001, 'Training Providers', 'ATPs', 'Training Providers Management', 'Training Providers Management', 'fa-solid fa-folder', 'ext/atps/list/', 1),
(10002, 'Strategic Plans', 'Strategy', 'Strategy Management', NULL, 'fa-solid fa-folder', 'ext/strategies/list/', 1),
(10003, 'Operational Planning', 'Operational Planning', 'Operational Planning', NULL, 'fa-solid fa-folder-open', 'ext/strategies/projects_list/', 1),
(10004, 'Self Studies', 'Self Studies', 'Self Studies', NULL, 'fa-solid fa-file', 'ext/strategies/self_studies/', 1),
(10005, 'EQA', 'EQA', 'EQA', 'EQA', 'fa-solid fa-folder-open', 'ext/eqa/list/', 1),
(10006, 'Learners Affairs', 'Learners Affairs', 'Learners Affairs', 'Learners Affairs', 'fa-solid fa-users', 'ext/la/list/', 1),
(10007, 'Claim Certificate', 'Claim Certificate', 'Claim Certificate', 'Claim Certificate', 'fa-solid fa-certificate', 'ext/cc/list/', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employees_list_staus`
--

CREATE TABLE `employees_list_staus` (
  `staus_id` int(11) NOT NULL,
  `staus_name` varchar(50) NOT NULL,
  `staus_color` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees_list_staus`
--

INSERT INTO `employees_list_staus` (`staus_id`, `staus_name`, `staus_color`) VALUES
(1, 'Available', '#05D057'),
(2, 'Busy', '#FF8A04'),
(3, 'Don\'t Disturb', '#C60B0B'),
(4, 'Appear Offline', '#7a8386');

-- --------------------------------------------------------

--
-- Table structure for table `employees_notifications`
--

CREATE TABLE `employees_notifications` (
  `notification_id` int(11) NOT NULL,
  `notification_date` datetime NOT NULL,
  `notification_text` text DEFAULT NULL,
  `related_page` varchar(100) DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  `is_seen` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_services`
--

CREATE TABLE `employees_services` (
  `record_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees_services`
--

INSERT INTO `employees_services` (`record_id`, `employee_id`, `service_id`, `added_by`, `added_date`) VALUES
(12, 34, 10003, 1, '2026-01-06 07:13:00'),
(13, 34, 10002, 1, '2026-01-06 07:13:00'),
(14, 34, 10004, 1, '2026-01-06 07:16:00'),
(15, 34, 10001, 1, '2026-01-06 07:16:00');

-- --------------------------------------------------------

--
-- Table structure for table `feedback_forms`
--

CREATE TABLE `feedback_forms` (
  `form_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback_forms`
--

INSERT INTO `feedback_forms` (`form_id`, `employee_id`, `added_date`) VALUES
(6, 34, '2025-10-21 20:21:00');

-- --------------------------------------------------------

--
-- Table structure for table `feedback_forms_answers`
--

CREATE TABLE `feedback_forms_answers` (
  `record_id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `a1` text DEFAULT NULL,
  `a2` text DEFAULT NULL,
  `a3` text DEFAULT NULL,
  `a4` text DEFAULT NULL,
  `a5` text DEFAULT NULL,
  `a6` text DEFAULT NULL,
  `a7` text DEFAULT NULL,
  `a8` text DEFAULT NULL,
  `a9` text DEFAULT NULL,
  `a10` text DEFAULT NULL,
  `a11` text DEFAULT NULL,
  `a12` text DEFAULT NULL,
  `a13` text DEFAULT NULL,
  `a14` text DEFAULT NULL,
  `a15` text DEFAULT NULL,
  `a16` text DEFAULT NULL,
  `a17` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback_forms_answers`
--

INSERT INTO `feedback_forms_answers` (`record_id`, `form_id`, `a1`, `a2`, `a3`, `a4`, `a5`, `a6`, `a7`, `a8`, `a9`, `a10`, `a11`, `a12`, `a13`, `a14`, `a15`, `a16`, `a17`) VALUES
(5, 6, 'Excellent', 'Average', 'Good', 'No', '2', 'Excellent', '2', 'Good', '2', 'Average', '2', 'Excellent', '2', 'Average', '2', '2', '22');

-- --------------------------------------------------------

--
-- Table structure for table `hr_approvals`
--

CREATE TABLE `hr_approvals` (
  `approval_id` int(11) NOT NULL,
  `related_table` varchar(100) NOT NULL,
  `related_id` int(11) NOT NULL,
  `sent_date` datetime NOT NULL,
  `sent_to_id` int(11) NOT NULL,
  `log_remark` text DEFAULT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_attendance`
--

CREATE TABLE `hr_attendance` (
  `attendance_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `checkin_date` date DEFAULT NULL,
  `checkin_time` time DEFAULT NULL,
  `attendance_remarks` text DEFAULT NULL,
  `added_date` datetime DEFAULT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hr_attendance`
--

INSERT INTO `hr_attendance` (`attendance_id`, `employee_id`, `checkin_date`, `checkin_time`, `attendance_remarks`, `added_date`, `added_by`) VALUES
(1, 1, '2025-10-17', '09:51:00', 'AUTO_CHECK_IN', '2025-10-17 00:00:00', 1),
(2, 34, '2025-10-17', '10:07:00', 'AUTO_CHECK_IN', '2025-10-17 00:00:00', 34),
(3, 34, '2025-10-21', '20:01:00', 'AUTO_CHECK_IN', '2025-10-21 00:00:00', 34),
(4, 1, '2025-10-29', '17:38:00', 'AUTO_CHECK_IN', '2025-10-29 17:38:00', 1),
(5, 34, '2025-10-29', '18:25:00', 'AUTO_CHECK_IN', '2025-10-29 18:25:00', 34),
(6, 1, '2025-10-30', '02:57:00', 'AUTO_CHECK_IN', '2025-10-30 02:57:00', 1),
(7, 34, '2025-10-30', '14:00:00', 'AUTO_CHECK_IN', '2025-10-30 14:00:00', 34),
(8, 35, '2025-10-30', '17:04:00', 'AUTO_CHECK_IN', '2025-10-30 17:04:00', 35),
(9, 34, '2025-10-31', '03:43:00', 'AUTO_CHECK_IN', '2025-10-31 03:43:00', 34),
(10, 35, '2025-10-31', '09:07:00', 'AUTO_CHECK_IN', '2025-10-31 09:07:00', 35),
(11, 1, '2025-10-31', '11:05:00', 'AUTO_CHECK_IN', '2025-10-31 11:05:00', 1),
(12, 34, '2025-11-11', '20:09:00', 'AUTO_CHECK_IN', '2025-11-11 20:09:00', 34),
(13, 34, '2025-11-12', '07:18:00', 'AUTO_CHECK_IN', '2025-11-12 07:18:00', 34),
(14, 1, '2025-11-12', '10:19:00', 'AUTO_CHECK_IN', '2025-11-12 10:19:00', 1),
(15, 34, '2025-11-13', '10:18:00', 'AUTO_CHECK_IN', '2025-11-13 10:18:00', 34),
(16, 34, '2025-11-18', '13:20:00', 'AUTO_CHECK_IN', '2025-11-18 13:20:00', 34),
(17, 34, '2025-11-19', '06:03:00', 'AUTO_CHECK_IN', '2025-11-19 06:03:00', 34),
(18, 1, '2025-11-19', '07:13:00', 'AUTO_CHECK_IN', '2025-11-19 07:13:00', 1),
(19, 35, '2025-11-19', '07:14:00', 'AUTO_CHECK_IN', '2025-11-19 07:14:00', 35),
(20, 34, '2025-11-21', '00:28:00', 'AUTO_CHECK_IN', '2025-11-21 00:28:00', 34),
(21, 34, '2025-11-24', '06:03:00', 'AUTO_CHECK_IN', '2025-11-24 06:03:00', 34),
(22, 1, '2025-11-24', '11:23:00', 'AUTO_CHECK_IN', '2025-11-24 11:23:00', 1),
(23, 34, '2025-12-03', '20:24:00', 'AUTO_CHECK_IN', '2025-12-03 20:24:00', 34),
(24, 1, '2025-12-03', '20:24:00', 'AUTO_CHECK_IN', '2025-12-03 20:24:00', 1),
(25, 1, '2025-12-04', '03:41:00', 'AUTO_CHECK_IN', '2025-12-04 03:41:00', 1),
(26, 34, '2025-12-04', '03:42:00', 'AUTO_CHECK_IN', '2025-12-04 03:42:00', 34),
(27, 35, '2025-12-04', '08:51:00', 'AUTO_CHECK_IN', '2025-12-04 08:51:00', 35),
(28, 39, '2025-12-04', '08:52:00', 'AUTO_CHECK_IN', '2025-12-04 08:52:00', 39),
(29, 34, '2025-12-05', '11:15:00', 'AUTO_CHECK_IN', '2025-12-05 11:15:00', 34),
(30, 34, '2025-12-08', '08:08:00', 'AUTO_CHECK_IN', '2025-12-08 08:08:00', 34),
(31, 34, '2026-01-05', '14:13:00', 'AUTO_CHECK_IN', '2026-01-05 14:13:00', 34),
(32, 1, '2026-01-05', '15:02:00', 'AUTO_CHECK_IN', '2026-01-05 15:02:00', 1),
(33, 1, '2026-01-06', '06:09:00', 'AUTO_CHECK_IN', '2026-01-06 06:09:00', 1),
(34, 34, '2026-01-06', '06:09:00', 'AUTO_CHECK_IN', '2026-01-06 06:09:00', 34),
(35, 1, '2026-01-07', '10:59:00', 'AUTO_CHECK_IN', '2026-01-07 10:59:00', 1),
(36, 35, '2026-01-07', '11:06:00', 'AUTO_CHECK_IN', '2026-01-07 11:06:00', 35),
(37, 34, '2026-01-07', '11:13:00', 'AUTO_CHECK_IN', '2026-01-07 11:13:00', 34),
(38, 34, '2026-01-08', '20:43:00', 'AUTO_CHECK_IN', '2026-01-08 20:43:00', 34),
(39, 34, '2026-01-09', '00:37:00', 'AUTO_CHECK_IN', '2026-01-09 00:37:00', 34),
(40, 34, '2026-01-14', '19:29:00', 'AUTO_CHECK_IN', '2026-01-14 19:29:00', 34),
(41, 34, '2026-01-15', '09:21:00', 'AUTO_CHECK_IN', '2026-01-15 09:21:00', 34),
(42, 35, '2026-01-15', '10:28:00', 'AUTO_CHECK_IN', '2026-01-15 10:28:00', 35),
(43, 1, '2026-01-15', '10:37:00', 'AUTO_CHECK_IN', '2026-01-15 10:37:00', 1),
(44, 34, '2026-01-20', '01:47:00', 'AUTO_CHECK_IN', '2026-01-20 01:47:00', 34),
(45, 1, '2026-01-20', '07:04:00', 'AUTO_CHECK_IN', '2026-01-20 07:04:00', 1),
(46, 35, '2026-01-20', '08:48:00', 'AUTO_CHECK_IN', '2026-01-20 08:48:00', 35);

-- --------------------------------------------------------

--
-- Table structure for table `hr_certificates`
--

CREATE TABLE `hr_certificates` (
  `certificate_id` int(11) NOT NULL,
  `certificate_name` text DEFAULT NULL,
  `certificate_name_ar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hr_certificates`
--

INSERT INTO `hr_certificates` (`certificate_id`, `certificate_name`, `certificate_name_ar`) VALUES
(1, 'Diploma', 'Diploma'),
(2, 'Bachelor', 'Bachelor'),
(3, 'Master', 'Master');

-- --------------------------------------------------------

--
-- Table structure for table `hr_disp_actions`
--

CREATE TABLE `hr_disp_actions` (
  `da_id` int(11) NOT NULL,
  `da_warning_id` int(11) NOT NULL,
  `da_remark` text DEFAULT NULL,
  `da_type_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `da_status_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_disp_actions_status`
--

CREATE TABLE `hr_disp_actions_status` (
  `da_status_id` int(11) NOT NULL,
  `da_status_name` varchar(100) NOT NULL DEFAULT 'na',
  `da_status_name_ar` varchar(100) NOT NULL DEFAULT 'na'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `hr_disp_actions_status`
--

INSERT INTO `hr_disp_actions_status` (`da_status_id`, `da_status_name`, `da_status_name_ar`) VALUES
(1, 'Pending', 'Pending'),
(3, 'Approved', 'Approved'),
(4, 'Rejected', 'Rejected');

-- --------------------------------------------------------

--
-- Table structure for table `hr_disp_actions_types`
--

CREATE TABLE `hr_disp_actions_types` (
  `da_type_id` int(11) NOT NULL,
  `da_type_code` varchar(100) NOT NULL DEFAULT 'na',
  `da_type_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `hr_disp_actions_types`
--

INSERT INTO `hr_disp_actions_types` (`da_type_id`, `da_type_code`, `da_type_text`) VALUES
(1, 'A01', 'Being late for the work up to 15 minutes without valid justification'),
(2, 'A02', 'Being late for the work up to 16 - 30 minutes without valid justification'),
(3, 'A03', 'Being late for the work in excess of 30 minutes without valid justification'),
(4, 'A04', 'UNWARRANTED ABSENCE FROM WORK WITHOUT VALID REASON'),
(5, 'A05', 'IN APPROPRIATE BEHAVIOR'),
(6, 'B03', 'NEGLIGENCE'),
(7, 'C06', 'Inappropriate behavior  disrespect or disobediencce'),
(8, 'B09', 'FAILURE TO CARRIED OUT WORK RELATED  INSTRUCTIONS  RULES AND REGULATION'),
(9, 'C09', 'FAILURE TO OBSERVE HEALTH  SAFETY AND ENVIRONMENT REGULATIONS'),
(10, 'C11', 'FAILURE TO OBSERVE THE SITE SECURITY REGULATIONS'),
(11, 'C16', 'POSSESION  CONSUMPTIONS OR UNDER THE INFLUENCE OF ALCOHOL OR ILLEGAL DRUGS ON COMPANY PREMISES'),
(12, 'C21', 'COMMITING UNSANITARY ACTS'),
(13, '00', 'OTHERS');

-- --------------------------------------------------------

--
-- Table structure for table `hr_disp_actions_warnings`
--

CREATE TABLE `hr_disp_actions_warnings` (
  `da_warning_id` int(11) NOT NULL,
  `da_warning_name` varchar(100) NOT NULL DEFAULT 'na',
  `da_warning_name_ar` varchar(100) NOT NULL DEFAULT 'na'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `hr_disp_actions_warnings`
--

INSERT INTO `hr_disp_actions_warnings` (`da_warning_id`, `da_warning_name`, `da_warning_name_ar`) VALUES
(1, 'No Warning', 'Pending'),
(3, 'First Warning', 'Approved'),
(4, 'Second Warning', 'Rejected'),
(5, 'Third Warning', 'Rejected'),
(6, 'Final Warning', 'Rejected');

-- --------------------------------------------------------

--
-- Table structure for table `hr_documents`
--

CREATE TABLE `hr_documents` (
  `document_id` int(11) NOT NULL,
  `document_title` text NOT NULL,
  `document_description` text NOT NULL,
  `added_date` date DEFAULT NULL,
  `document_attachment` varchar(70) NOT NULL DEFAULT 'no-img.png',
  `document_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_documents_types`
--

CREATE TABLE `hr_documents_types` (
  `document_type_id` int(11) NOT NULL,
  `document_type_name` varchar(200) NOT NULL,
  `document_type_icon` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hr_documents_types`
--

INSERT INTO `hr_documents_types` (`document_type_id`, `document_type_name`, `document_type_icon`) VALUES
(1, 'Policies', 'fa-solid fa-file'),
(2, 'Procedures', 'fa-regular fa-file-lines'),
(3, 'Annoucements', 'fa-solid fa-bullhorn');

-- --------------------------------------------------------

--
-- Table structure for table `hr_employees_leaves`
--

CREATE TABLE `hr_employees_leaves` (
  `leave_id` int(11) NOT NULL,
  `submission_date` date NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `leave_type_id` int(11) NOT NULL DEFAULT 0,
  `total_days` int(5) NOT NULL DEFAULT 0,
  `leave_attachment` varchar(70) NOT NULL DEFAULT 'no-img.png',
  `leave_remarks` text NOT NULL,
  `employee_id` int(11) NOT NULL,
  `leave_status_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_employees_leave_status`
--

CREATE TABLE `hr_employees_leave_status` (
  `leave_status_id` int(11) NOT NULL,
  `leave_status_name` varchar(100) NOT NULL DEFAULT 'na',
  `leave_status_name_ar` varchar(100) NOT NULL DEFAULT 'na'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `hr_employees_leave_status`
--

INSERT INTO `hr_employees_leave_status` (`leave_status_id`, `leave_status_name`, `leave_status_name_ar`) VALUES
(1, 'Pending', 'Pending'),
(2, 'Pending Approval', 'Pending Approval'),
(3, 'Approved', 'Approved'),
(4, 'Rejected', 'Rejected'),
(6, 'Pending user action', 'Pending user action');

-- --------------------------------------------------------

--
-- Table structure for table `hr_employees_leave_types`
--

CREATE TABLE `hr_employees_leave_types` (
  `leave_type_id` int(11) NOT NULL,
  `leave_type_name` varchar(100) NOT NULL DEFAULT 'na',
  `leave_type_name_ar` varchar(100) NOT NULL DEFAULT 'na'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `hr_employees_leave_types`
--

INSERT INTO `hr_employees_leave_types` (`leave_type_id`, `leave_type_name`, `leave_type_name_ar`) VALUES
(1, 'sick leave', 'sick leave'),
(3, 'credentials leave', 'sick leave'),
(4, 'Annual Leave', 'sick leave');

-- --------------------------------------------------------

--
-- Table structure for table `hr_employees_permissions`
--

CREATE TABLE `hr_employees_permissions` (
  `permission_id` int(11) NOT NULL,
  `submission_date` date NOT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time NOT NULL,
  `total_hours` int(2) NOT NULL DEFAULT 0,
  `permission_remarks` text NOT NULL,
  `employee_id` int(11) NOT NULL,
  `permission_status_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_employees_permissions_status`
--

CREATE TABLE `hr_employees_permissions_status` (
  `permission_status_id` int(11) NOT NULL,
  `permission_status_name` varchar(100) NOT NULL DEFAULT 'na',
  `permission_status_name_ar` varchar(100) NOT NULL DEFAULT 'na'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `hr_employees_permissions_status`
--

INSERT INTO `hr_employees_permissions_status` (`permission_status_id`, `permission_status_name`, `permission_status_name_ar`) VALUES
(1, 'Pending', 'Pending'),
(2, 'Pending Approval', 'Pending Approval'),
(3, 'Approved', 'Approved'),
(4, 'Rejected', 'Rejected');

-- --------------------------------------------------------

--
-- Table structure for table `hr_exit_interviews`
--

CREATE TABLE `hr_exit_interviews` (
  `interview_id` int(11) NOT NULL,
  `interview_date` date NOT NULL,
  `interview_remarks` text DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  `current_department_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_exit_interviews_answers`
--

CREATE TABLE `hr_exit_interviews_answers` (
  `answer_id` int(11) NOT NULL,
  `answer_text` text DEFAULT NULL,
  `question_id` int(11) NOT NULL,
  `interview_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_exit_interviews_questions`
--

CREATE TABLE `hr_exit_interviews_questions` (
  `question_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_text_ar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hr_exit_interviews_questions`
--

INSERT INTO `hr_exit_interviews_questions` (`question_id`, `question_text`, `question_text_ar`) VALUES
(1, 'Why are you leaving?', ''),
(2, 'were you satisfied with the job?', '');

-- --------------------------------------------------------

--
-- Table structure for table `hr_performance`
--

CREATE TABLE `hr_performance` (
  `performance_id` int(11) NOT NULL,
  `performance_object` text DEFAULT NULL,
  `performance_kpi` text DEFAULT NULL,
  `performance_remark` text DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `local_routes_atp_reg`
--

CREATE TABLE `local_routes_atp_reg` (
  `route_id` int(11) NOT NULL,
  `route_name` text NOT NULL,
  `route_src` text DEFAULT NULL,
  `route_family` varchar(30) DEFAULT NULL,
  `route_path` text DEFAULT NULL,
  `route_type` varchar(20) NOT NULL,
  `route_pointer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `local_routes_atp_reg`
--

INSERT INTO `local_routes_atp_reg` (`route_id`, `route_name`, `route_src`, `route_family`, `route_path`, `route_type`, `route_pointer`) VALUES
(1, 'Dashboard Page', 'dashboard/', NULL, 'index.php', 'page', '../'),
(2, 'Logout Page', 'logout/', NULL, 'logout.php', 'page', '../'),
(3, 'Settings Page', 'settings/all/', NULL, 'settings_all.php', 'page', '../../'),
(8, 'New Page API', 'add_init_app_list', 'atps', 'serv_new.php', 'API', '../'),
(11, 'get_select_data', 'get_select_data', 'select', 'select_control.php', 'select', '../'),
(14, 'Get initial form data', 'get_init_data', 'init_form', 'serv_get.php', 'API', '../'),
(15, 'Add initial form data', 'add_init_data', 'init_form', 'serv_new.php', 'API', '../'),
(16, 'get atp contacts list', 'get_atp_contacts', 'init_form', 'serv_contacts.php', 'API', '../'),
(17, 'add atp contacts list', 'add_contacts_list', 'init_form', 'serv_contacts_new.php', 'API', '../'),
(18, 'remove atp contacts list', 'remove_atp_contact', 'init_form', 'serv_contacts_del.php', 'API', '../'),
(19, 'submit initial form', 'submit_init_form', 'init_form', 'serv_submit.php', 'API', '../'),
(20, 'Registration_Request', 'app_init/req_reg_form/', 'atps', 'req_reg_new.php', 'page', '../../'),
(21, 'Initial_Registration_Request', 'app_init/init_register_request/', 'atps', 'init_register_request.php', 'page', '../../'),
(23, 'get atp locations list', 'get_atp_locations', 'init_form', 'serv_locations.php', 'API', '../'),
(24, 'add atp location list', 'add_locations_list', 'init_form', 'serv_locations_new.php', 'API', '../'),
(25, 'remove atp locations list', 'remove_atp_location', 'init_form', 'serv_locations_del.php', 'API', '../'),
(26, 'Self Assessment Form', 'app_init/self_assess/', 'atps', 'self_assess.php', 'page', '../../'),
(30, 'save atp learner enrolled', 'save_learner_enrolled', 'init_form', 'serv_save_le.php', 'API', '../'),
(31, 'get atp LE', 'get_learner_enrolled', 'init_form', 'serv_list_le.php', 'API', '../'),
(32, 'get atp faculty list', 'get_atp_facultys', 'init_form', 'faculty/serv_list.php', 'API', '../../'),
(35, 'get atp  eligibility criteria', 'get_eligibility_criteria', 'init_form', 'eligibility_criteria/serv_list.php', 'API', '../../'),
(36, 'save atp eligibility criteria', 'save_eligibility_criteria', 'init_form', 'eligibility_criteria/serv_new.php', 'API', '../../'),
(38, 'faculty details Form', 'app_init/faculty_details/', 'atps', 'faculty_details.php', 'page', '../../'),
(39, 'submit faculty details', 'submit_faculty_details', 'init_form', 'faculty/serv_submit.php', 'API', '../../'),
(40, 'get atp Qualifications list SED', 'get_atp_qualifications_sed', 'init_form', 'qualifications_sed/serv_list.php', 'API', '../../'),
(41, 'add atp qualification list SED', 'add_sed_qualifications', 'init_form', 'qualifications_sed/serv_new.php', 'API', '../../'),
(42, 'remove atp qualification list SED', 'remove_atp_qualification_sed', 'init_form', 'qualifications_sed/serv_del.php', 'API', '../../'),
(43, 'get atp SED', 'get_sed_data', 'init_form', 'sed/serv_data.php', 'API', '../../'),
(44, 'save atp SED', 'save_sed_form', 'init_form', 'sed/serv_new.php', 'API', '../../'),
(45, 'Self Assessment Compliance', 'app_init/sed_compliance/', 'atps', 'self_assess_compliance.php', 'page', '../../'),
(46, 'answer SED question', 'answer_sed_cat', 'init_form', 'sed/serv_answer.php', 'API', '../../'),
(47, 'Submit SED form', 'submit_sed_form', 'init_form', 'sed/serv_submit.php', 'API', '../../'),
(48, 'EQA Visit', 'app_init/eqa_visit/', 'atps', 'eqa_visit.php', 'page', '../../'),
(49, 'Submit EQA visit', 'submit_eqa_visit', 'init_form', 'eqa/serv_submit.php', 'API', '../../'),
(50, 'Submit KPI form', 'save_kpi_form', 'init_form', 'kpi/serv_new.php', 'API', '../../'),
(51, 'get atp KPI', 'get_kpi_data', 'init_form', 'kpi/serv_data.php', 'API', '../../'),
(52, 'remove atp KPI', 'remove_atp_kpi', 'init_form', 'kpi/serv_del.php', 'API', '../../'),
(53, 'get SED question answer', 'get_answer_sed', 'init_form', 'sed/serv_get_answer.php', 'API', '../../'),
(54, 'save atp EQA visit date', 'save_eqa_visit', 'init_form', 'eqa/serv_new.php', 'API', '../../'),
(55, 'qualification registration', 'app_init/qualifications_register/', 'atps', 'qualifications_register.php', 'page', '../../'),
(56, 'save atp qualification details', 'save_qualification_details', 'init_form', 'qualifications/serv_update_details.php', 'API', '../../'),
(57, 'add atp qualification evidence', 'add_qualification_evidence', 'init_form', 'qualifications_evidence/serv_new.php', 'API', '../../'),
(58, 'get atp qualification evidence', 'get_qualification_evidence', 'init_form', 'qualifications_evidence/serv_list.php', 'API', '../../'),
(59, 'remove atp qualification evidence', 'remove_qualification_evidence', 'init_form', 'qualifications_evidence/serv_del.php', 'API', '../../'),
(60, 'submit atp qualification evidence', 'submit_qualification_register', 'init_form', 'qualifications_evidence/serv_submit.php', 'API', '../../'),
(61, 'Save faculty details CVs and Certs', 'save_faculty_details', 'init_form', 'faculty/serv_cvs.php', 'API', '../../'),
(62, 'save atp qualification registration data', 'save_qualification_reg_data', 'init_form', 'qualifications/serv_update_reg.php', 'API', '../../'),
(63, 'info request page', 'info_req/fill/', 'atps', 'info_req.php', 'page', '../../'),
(64, 'save atp info req', 'save_info_request', 'init_form', 'info_req/serv_save.php', 'API', '../../'),
(65, 'Save QIP', 'save_qip_form', 'init_form', 'qip/serv_save.php', 'API', '../../'),
(66, 'get atp faculty details', 'get_faculty_details', 'init_form', 'faculty/serv_get.php', 'API', '../../'),
(67, '-------------------', 'accreditation/new/', 'atps', 'new_accreditation.php', 'page', '../../'),
(68, 'New Accreditation main page', 'accreditation/new/', 'atps', 'new_accreditation.php', 'page', '../../'),
(69, 'initial request', 'accreditation/initial_request/', 'atps', 'initial_request.php', 'page', '../../'),
(70, 'save initial form', 'save_initial_form', 'initial_form', 'serv_update.php', 'API', '../../'),
(71, 'Get initial form data', 'get_initial_form', 'initial_form', 'serv_get.php', 'API', '../'),
(72, 'Get qualification list', 'get_qualifications_list', 'qualifications', 'serv_list.php', 'API', '../'),
(73, 'add atp qualification list', 'add_qualifications_list', 'qualifications', 'serv_new.php', 'API', '../'),
(74, 'remove atp qualification list', 'remove_atp_qualification', 'qualifications', 'serv_del.php', 'API', '../'),
(75, 'update Qualifications list', 'update_atp_qualifications', 'qualifications', 'serv_update.php', 'API', '../../'),
(77, 'Get Faculty list', 'get_faculty_list', 'faculty', 'serv_list.php', 'API', '../'),
(78, 'add atp faculty list', 'add_faculty_list', 'faculty', 'serv_new.php', 'API', '../'),
(79, 'remove atp faculty list', 'remove_atp_faculty', 'faculty', 'serv_del.php', 'API', '../'),
(80, 'update faculty list', 'update_atp_faculty', 'faculty', 'serv_update.php', 'API', '../../'),
(81, 'Get statistic list', 'get_statistic_list', 'statistic', 'serv_list.php', 'API', '../'),
(82, 'add atp statistic list', 'add_statistic_list', 'statistic', 'serv_new.php', 'API', '../'),
(83, 'remove atp statistic list', 'remove_atp_statistic', 'statistic', 'serv_del.php', 'API', '../'),
(84, 'Get platform list', 'get_platform_list', 'platform', 'serv_list.php', 'API', '../'),
(85, 'add atp platform list', 'add_platform_list', 'platform', 'serv_new.php', 'API', '../'),
(86, 'update platform list', 'update_atp_platform', 'platform', 'serv_update.php', 'API', '../../'),
(87, 'remove atp platform list', 'remove_atp_platform', 'platform', 'serv_del.php', 'API', '../'),
(88, 'submit initial request  form', 'submit_init_request_form', 'initial_form', 'serv_submit.php', 'API', '../../'),
(89, 'program_registration', 'accreditation/program_registration/', 'atps', 'program_registration.php', 'page', '../../'),
(90, 'update faculty list attachments', 'update_atp_faculty_files', 'faculty', 'serv_attachments.php', 'API', '../../'),
(91, 'Map Qualifications list', 'map_atp_qualifications', 'qualifications', 'serv_map.php', 'API', '../../'),
(92, 'submit program registration  form', 'submit_program_reg_form', 'program_registration', 'serv_submit.php', 'API', '../../');

-- --------------------------------------------------------

--
-- Table structure for table `local_routes_default`
--

CREATE TABLE `local_routes_default` (
  `route_id` int(11) NOT NULL,
  `route_name` text NOT NULL,
  `route_src` text DEFAULT NULL,
  `route_family` varchar(30) DEFAULT NULL,
  `route_path` text DEFAULT NULL,
  `route_type` varchar(20) NOT NULL,
  `route_pointer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `local_routes_default`
--

INSERT INTO `local_routes_default` (`route_id`, `route_name`, `route_src`, `route_family`, `route_path`, `route_type`, `route_pointer`) VALUES
(1, 'Dashboard Page', 'dashboard/', NULL, 'index.php', 'page', '../'),
(2, 'Logout Page', 'logout/', NULL, 'logout.php', 'page', '../'),
(3, 'Settings Page', 'settings_lists/', NULL, 'settings.php', 'page', '../'),
(4, 'Profile Page', 'profile/', NULL, 'profile.php', 'page', '../'),
(5, 'Groups Page', 'groups/', NULL, 'groups.php', 'page', '../'),
(6, 'Calendar Page', 'calendar/', NULL, 'calendar.php', 'page', '../'),
(7, 'Messages Page', 'messages/', NULL, 'messages.php', 'page', '../'),
(8, 'Tasks Page', 'tasks/', NULL, 'tasks_list.php', 'page', '../'),
(9, 'logs API', 'get_sys_logs', 'sys_logs', 'serv_logs.php', 'API', '../'),
(37, 'Group new API', 'add_group_list', 'groups', 'serv_new.php', 'API', '../'),
(38, 'Group list API', 'get_group_list', 'groups', 'serv_list.php', 'API', '../'),
(39, 'Get Group Data', 'get_group_data', 'groups', 'serv_view.php', 'API', '../'),
(40, 'Upload file to Group API', 'upload_group_list', 'groups', 'serv_file_new.php', 'API', '../'),
(41, 'add member to Group API', 'add_group_member', 'groups', 'serv_member_new.php', 'API', '../'),
(44, 'View Page', 'tasks/view/', 'tasks', 'tasks_view.php', 'page', '../../'),
(45, 'List Page API', 'get_tasks_list', 'tasks', 'serv_list.php', 'API', '../'),
(46, 'New Page API', 'add_tasks_list', 'tasks', 'serv_new.php', 'API', '../'),
(47, 'View Page API', 'update_tasks_list', 'tasks', 'serv_update.php', 'API', '../'),
(49, 'List Page', 'tickets/list/', 'tickets', 'tickets_list.php', 'page', '../../'),
(51, 'View Page', 'tickets/view/', 'tickets', 'tickets_view.php', 'page', '../../'),
(52, 'List Page API', 'get_tickets_list', 'tickets', 'serv_list.php', 'API', '../'),
(54, 'New Page API', 'add_tickets_list', 'tickets', 'serv_new.php', 'API', '../'),
(55, 'update Page API', 'update_tickets_list', 'tickets', 'serv_update.php', 'API', '../'),
(56, 'Upload new file version to Group API', 'version_group_list', 'groups', 'serv_file_version.php', 'API', '../'),
(57, 'Messages new API', 'add_messages_list', 'messages', 'serv_new.php', 'API', '../'),
(58, 'messages list API', 'get_messages_list', 'messages', 'serv_list.php', 'API', '../'),
(59, 'messages chats list API', 'get_messages_chats', 'messages', 'serv_list_chats.php', 'API', '../'),
(60, 'Messages chats new API', 'send_messages_list', 'messages', 'send_new.php', 'API', '../');

-- --------------------------------------------------------

--
-- Table structure for table `local_routes_external`
--

CREATE TABLE `local_routes_external` (
  `route_id` int(11) NOT NULL,
  `route_name` text NOT NULL,
  `route_src` text DEFAULT NULL,
  `route_family` varchar(30) DEFAULT NULL,
  `route_path` text DEFAULT NULL,
  `route_type` varchar(20) NOT NULL,
  `route_pointer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `local_routes_external`
--

INSERT INTO `local_routes_external` (`route_id`, `route_name`, `route_src`, `route_family`, `route_path`, `route_type`, `route_pointer`) VALUES
(1, 'atps List Page', 'ext/atps/list/', 'atps', 'ext/atps/atps_list.php', 'page', '../../../'),
(2, 'atps View Page', 'ext/atps/view/', 'atps', 'ext/atps/atps_view.php', 'page', '../../../'),
(3, 'atps New Page', 'ext/atps/new/', 'atps', 'ext/atps/atps_new.php', 'page', '../../../'),
(4, 'atps List Page API', 'ext/get_atps_list', 'atps', 'serv_list.php', 'API', '../../'),
(5, 'atps New Page API', 'ext/add_atps_list', 'atps', 'serv_new.php', 'API', '../../'),
(6, 'atps Update Page API', 'ext/update_atps_list', 'atps', 'serv_update.php', 'API', '../../'),
(7, 'atps send email API', 'ext/send_atp_init_email', 'atps', 'serv_init_email.php', 'API', '../../'),
(8, 'atps View init Page', 'ext/atps/view/init/', 'atps', 'ext/atps/forms/init_form.php', 'page', '../../../../'),
(67, 'Strategy Task Assign Page', 'ext/strategies/task_assign/', 'strategies', 'ext/strategies/strategies_task_assign.php', 'page', '../../../'),
(68, 'Strategy List Page', 'ext/strategies/list/', 'strategies', 'ext/strategies/strategies_list.php', 'page', '../../../'),
(69, 'Strategy View Page', 'ext/strategies/view/', 'strategies', 'ext/strategies/strategies_view.php', 'page', '../../../'),
(70, 'Strategy New Page', 'ext/strategies/new/', 'strategies', 'ext/strategies/strategies_new.php', 'page', '../../../'),
(110, 'strategies New Page API', 'ext/add_strategies_list', 'strategies', 'serv_new.php', 'API', '../../'),
(111, 'Strategies priority New Page API', 'ext/add_strategies_theme', 'strategies', 'serv_themes_new.php', 'API', '../../'),
(112, 'goal priority New Page API', 'ext/add_strategies_objectives', 'strategies', 'serv_objectives_new.php', 'API', '../../'),
(113, 'Tactics New Page API', 'ext/add_strategies_kpis', 'strategies', 'serv_kpis_new.php', 'API', '../../'),
(114, 'Strategies priority Update Page API', 'ext/update_themes_list', 'strategies', 'serv_themes_update.php', 'API', '../../'),
(115, 'Strategies items Delete Page API', 'ext/delete_strategy_item', 'strategies', 'serv_items_delete.php', 'API', '../../'),
(116, 'Strategies priority get Page API', 'ext/get_themes_list', 'strategies', 'serv_themes_get.php', 'API', '../../'),
(117, 'Milestone New Page API', 'ext/add_strategies_milestone', 'strategies', 'serv_milestones_new.php', 'API', '../../'),
(118, 'local map New Page API', 'ext/add_local_map', 'strategies', 'serv_local_map_new.php', 'API', '../../'),
(119, 'local map List Page API', 'ext/get_local_maps', 'strategies', 'serv_local_map_list.php', 'API', '../../'),
(120, 'External map New Page API', 'ext/add_external_map', 'strategies', 'serv_external_map_new.php', 'API', '../../'),
(121, 'external map List Page API', 'ext/get_external_maps', 'strategies', 'serv_external_map_list.php', 'API', '../../'),
(122, 'Publish plan Page API', 'ext/publish_strategy_plan', 'strategies', 'serv_publish.php', 'API', '../../'),
(124, 'assign mapping task API', 'ext/assign_mapping_task', 'strategies', 'serv_assign_task.php', 'API', '../../'),
(125, 'ATP forms approval', 'ext/approve_atp_form', 'atps', 'serv_forms_control.php', 'API', '../../'),
(126, 'Operational Planning List Page', 'ext/strategies/projects_list/', 'strategies', 'ext/strategies_ops/projects_list.php', 'page', '../../../'),
(127, 'Ops Project New Page', 'ext/strategies/projects_new/', 'strategies', 'ext/strategies_ops/projects_new.php', 'page', '../../../'),
(128, 'Ops Projects New Page API', 'ext/add_ops_projects_list', 'strategies_ops', 'serv_new.php', 'API', '../../'),
(129, 'Ops Project View Page', 'ext/strategies/view_project/', 'strategies', 'ext/strategies_ops/projects_view.php', 'page', '../../../'),
(130, 'Link KPI to Projects New Page API', 'ext/link_project_kpi', 'strategies_ops', 'serv_new_kpi_link.php', 'API', '../../'),
(131, 'get KPIs Projects list Page API', 'ext/get_linked_kpis', 'strategies_ops', 'serv_list_kpi_link.php', 'API', '../../'),
(132, 'Projects items Delete Page API', 'ext/delete_project_item', 'strategies_ops', 'serv_items_delete.php', 'API', '../../'),
(133, 'Ops Projects milestone New Page API', 'ext/add_project_milestone', 'strategies_ops', 'serv_new_milestone.php', 'API', '../../'),
(134, 'Ops Projects milestone List Page API', 'ext/get_project_milestones', 'strategies_ops', 'serv_list_milestone.php', 'API', '../../'),
(135, 'Self Studies List Page', 'ext/strategies/self_studies/', 'strategies', 'ext/strategies_self_studies/self_studies_list.php', 'page', '../../../'),
(136, 'Self Studies New Page', 'ext/strategies/self_studies_new/', 'strategies', 'ext/strategies_self_studies/self_studies_new.php', 'page', '../../../'),
(137, 'self study New API', 'ext/add_self_study_list', 'strategies_self_studies', 'serv_new.php', 'API', '../../'),
(138, 'Self Studies View Page', 'ext/strategies/self_studies_view/', 'strategies', 'ext/strategies_self_studies/self_studies_view.php', 'page', '../../../'),
(139, 'self study Update Page API', 'ext/update_self_study_list', 'strategies_self_studies', 'serv_update.php', 'API', '../../'),
(140, 'self study New Page API', 'ext/add_page_self_study', 'strategies_self_studies', 'serv_new_page.php', 'API', '../../'),
(141, 'self study update Page API', 'ext/update_page_self_study', 'strategies_self_studies', 'serv_update_page.php', 'API', '../../'),
(142, 'self study delete Page API', 'ext/delete_page_self_study', 'strategies_self_studies', 'serv_delete_page.php', 'API', '../../'),
(143, 'atps View info req Page', 'ext/atps/view/info_req/', 'atps', 'ext/atps/forms/info_req.php', 'page', '../../../../');

-- --------------------------------------------------------

--
-- Table structure for table `local_routes_hr`
--

CREATE TABLE `local_routes_hr` (
  `route_id` int(11) NOT NULL,
  `route_name` text NOT NULL,
  `route_src` text DEFAULT NULL,
  `route_family` varchar(30) DEFAULT NULL,
  `route_path` text DEFAULT NULL,
  `route_type` varchar(20) NOT NULL,
  `route_pointer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `local_routes_hr`
--

INSERT INTO `local_routes_hr` (`route_id`, `route_name`, `route_src`, `route_family`, `route_path`, `route_type`, `route_pointer`) VALUES
(1, 'Dashboard Page', 'dashboard/', NULL, 'index.php', 'page', '../'),
(2, 'Logout Page', 'logout/', NULL, 'logout.php', 'page', '../'),
(3, 'Settings Page', 'settings/', NULL, 'settings.php', 'page', '../'),
(4, 'Profile Page', 'profile/', NULL, 'profile.php', 'page', '../'),
(5, 'Groups Page', 'groups/', NULL, 'groups.php', 'page', '../'),
(6, 'Calendar Page', 'calendar/', NULL, 'calendar.php', 'page', '../'),
(7, 'Messages Page', 'messages/', NULL, 'messages.php', 'page', '../'),
(8, 'Tasks Page', 'tasks/', NULL, 'tasks_list.php', 'page', '../'),
(9, 'logs API', 'get_sys_logs', 'sys_logs', 'serv_logs.php', 'API', '../'),
(37, 'Group new API', 'add_group_list', 'groups', 'serv_new.php', 'API', '../'),
(38, 'Group list API', 'get_group_list', 'groups', 'serv_list.php', 'API', '../'),
(39, 'Get Group Data', 'get_group_data', 'groups', 'serv_view.php', 'API', '../'),
(40, 'Upload file to Group API', 'upload_group_list', 'groups', 'serv_file_new.php', 'API', '../'),
(41, 'add member to Group API', 'add_group_member', 'groups', 'serv_member_new.php', 'API', '../'),
(44, 'View Page', 'tasks/view/', 'tasks', 'tasks_view.php', 'page', '../../'),
(45, 'List Page API', 'get_tasks_list', 'tasks', 'serv_list.php', 'API', '../'),
(46, 'New Page API', 'add_tasks_list', 'tasks', 'serv_new.php', 'API', '../'),
(47, 'View Page API', 'update_tasks_list', 'tasks', 'serv_update.php', 'API', '../'),
(49, 'List Page', 'tickets/list/', 'tickets', 'tickets_list.php', 'page', '../../'),
(51, 'View Page', 'tickets/view/', 'tickets', 'tickets_view.php', 'page', '../../'),
(52, 'List Page API', 'get_tickets_list', 'tickets', 'serv_list.php', 'API', '../'),
(54, 'New Page API', 'add_tickets_list', 'tickets', 'serv_new.php', 'API', '../'),
(56, 'Upload new file version to Group API', 'version_group_list', 'groups', 'serv_file_version.php', 'API', '../'),
(57, 'Messages new API', 'add_messages_list', 'messages', 'serv_new.php', 'API', '../'),
(58, 'messages list API', 'get_messages_list', 'messages', 'serv_list.php', 'API', '../'),
(59, 'messages chats list API', 'get_messages_chats', 'messages', 'serv_list_chats.php', 'API', '../'),
(60, 'Messages chats new API', 'send_messages_list', 'messages', 'send_new.php', 'API', '../'),
(89, 'Depts List Page', 'departments/list/', 'departments', 'departments_list.php', 'page', '../../'),
(90, 'departments List Page API', 'get_departments_list', 'departments', 'serv_list.php', 'API', '../'),
(91, 'departments New Page API', 'add_departments_list', 'departments', 'serv_new.php', 'API', '../'),
(92, 'Update departments Page API', 'update_departments_list', 'departments', 'serv_update.php', 'API', '../'),
(93, 'designations List Page', 'designations/list/', 'designations', 'designations_list.php', 'page', '../../'),
(94, 'designations List Page API', 'get_designations_list', 'designations', 'serv_list.php', 'API', '../'),
(95, 'designations New Page API', 'add_designations_list', 'designations', 'serv_new.php', 'API', '../'),
(96, 'Update designations Page API', 'update_designations_list', 'designations', 'serv_update.php', 'API', '../'),
(97, 'employees List Page', 'employees/list/', 'employees', 'employees_list.php', 'page', '../../'),
(98, 'employees List Page API', 'get_employees_list', 'employees', 'serv_list.php', 'API', '../'),
(99, 'employees New Page API', 'add_employees_list', 'employees', 'serv_new.php', 'API', '../'),
(100, 'Update employees Page API', 'update_employees_list', 'employees', 'serv_update.php', 'API', '../'),
(101, 'employee View Page', 'employees/view/', 'employees', 'employees_view.php', 'page', '../../'),
(102, 'Update Creds employees Page API', 'creds_employees_list', 'employees', 'serv_update_creds.php', 'API', '../'),
(103, 'Requests List Page', 'requests/list/', 'requests', 'requests_list.php', 'page', '../../'),
(104, 'hr_leaves List Page', 'hr_leaves/list/', 'hr_leaves', 'hr_leaves_list.php', 'page', '../../'),
(105, 'hr_leaves List Page API', 'get_hr_leaves_list', 'hr_leaves', 'serv_list.php', 'API', '../'),
(106, 'hr_leaves New Page API', 'add_hr_leaves_list', 'hr_leaves', 'serv_new.php', 'API', '../'),
(107, 'Update hr_leaves Page API', 'update_hr_leaves_list', 'hr_leaves', 'serv_update.php', 'API', '../'),
(108, 'hr_leaves_types List Page', 'hr_leaves_types/list/', 'hr_leaves_types', 'hr_leaves_types_list.php', 'page', '../../'),
(109, 'hr_leaves_types List Page API', 'get_hr_leaves_types_list', 'hr_leaves_types', 'serv_list.php', 'API', '../'),
(110, 'hr_leaves_types New Page API', 'add_hr_leaves_types_list', 'hr_leaves_types', 'serv_new.php', 'API', '../'),
(111, 'Update hr_leaves_types Page API', 'update_hr_leaves_types_list', 'hr_leaves_types', 'serv_update.php', 'API', '../'),
(112, 'hr_documents List Page', 'hr_documents/list/', 'hr_documents', 'hr_documents_list.php', 'page', '../../'),
(113, 'hr_documents List Page API', 'get_hr_documents_list', 'hr_documents', 'serv_list.php', 'API', '../'),
(114, 'hr_documents New Page API', 'add_hr_documents_list', 'hr_documents', 'serv_new.php', 'API', '../'),
(115, 'Depts chart List Page', 'chart/list/', 'departments', 'departments_chart.php', 'page', '../../'),
(116, 'hr_permissions List Page', 'hr_permissions/list/', 'hr_permissions', 'hr_permissions_list.php', 'page', '../../'),
(117, 'hr_permissions List Page API', 'get_hr_permissions_list', 'hr_permissions', 'serv_list.php', 'API', '../'),
(118, 'hr_permissions New Page API', 'add_hr_permissions_list', 'hr_permissions', 'serv_new.php', 'API', '../'),
(119, 'Update hr_permissions Page API', 'update_hr_permissions_list', 'hr_permissions', 'serv_update.php', 'API', '../'),
(120, 'hr_da List Page', 'hr_da/list/', 'hr_da', 'hr_da_list.php', 'page', '../../'),
(121, 'hr_da List Page API', 'get_hr_da_list', 'hr_da', 'serv_list.php', 'API', '../'),
(122, 'hr_da New Page API', 'add_hr_da_list', 'hr_da', 'serv_new.php', 'API', '../'),
(123, 'Update hr_da Page API', 'update_hr_da_list', 'hr_da', 'serv_update.php', 'API', '../'),
(124, 'hr_attendance List Page', 'hr_attendance/list/', 'hr_attendance', 'hr_attendance_list.php', 'page', '../../'),
(125, 'hr_attendance List Page API', 'get_hr_attendance_list', 'hr_attendance', 'serv_list.php', 'API', '../'),
(126, 'hr_attendance New Page API', 'add_hr_attendance_list', 'hr_attendance', 'serv_new.php', 'API', '../'),
(127, 'Update hr_attendance Page API', 'update_hr_attendance_list', 'hr_attendance', 'serv_update.php', 'API', '../'),
(128, 'hr_exit_interviews List Page', 'hr_exit_interviews/list/', 'hr_exit_interviews', 'hr_exit_interviews_list.php', 'page', '../../'),
(129, 'hr_exit_interviews List Page API', 'get_hr_exit_interviews_list', 'hr_exit_interviews', 'serv_list.php', 'API', '../'),
(130, 'hr_exit_interviews New Page API', 'add_hr_exit_interviews_list', 'hr_exit_interviews', 'serv_new.php', 'API', '../'),
(131, 'Update hr_exit_interviews Page API', 'update_hr_exit_interviews_list', 'hr_exit_interviews', 'serv_update.php', 'API', '../'),
(132, 'hr_performance List Page', 'hr_performance/list/', 'hr_performance', 'hr_performance_list.php', 'page', '../../'),
(133, 'hr_performance List Page API', 'get_hr_performance_list', 'hr_performance', 'serv_list.php', 'API', '../'),
(134, 'hr_performance New Page API', 'add_hr_performance_list', 'hr_performance', 'serv_new.php', 'API', '../'),
(135, 'Update hr_performance Page API', 'update_hr_performance_list', 'hr_performance', 'serv_update.php', 'API', '../'),
(136, 'Upload file to CHat API', 'upload_messages_list', 'messages', 'serv_file_new.php', 'API', '../'),
(137, 'Upload new file version to Chat API', 'version_messages_list', 'messages', 'serv_file_version.php', 'API', '../'),
(138, 'get chat files list API', 'files_messages_list', 'messages', 'serv_file_list.php', 'API', '../'),
(139, 'Feedback Page', 'feedback/', NULL, 'feedback.php', 'page', '../'),
(140, 'submit_feedback_form', 'submit_feedback_form', 'feedback', 'serv_new.php', 'API', '../'),
(141, 'users update Page API', 'update_users_list', 'users', 'serv_update.php', 'API', '../'),
(142, 'get_unseen_notifications_list', 'get_unseen_notifications_list', 'unseen', 'serv_list.php', 'API', '../'),
(143, 'Notifications List Page', 'notifications/list/', 'notifications', 'notifications_list.php', 'page', '../../'),
(144, 'Notifications List Page API', 'get_notifications_list', 'notifications', 'serv_list.php', 'API', '../'),
(145, 'Strategy List Page', 'strategies/list/', 'strategies', 'strategies_list.php', 'page', '../../'),
(146, 'Strategy View Page', 'strategies/view/', 'strategies', 'strategies_view.php', 'page', '../../'),
(147, 'USER STATUS API', 'update_user_status', 'users', 'serv_status.php', 'API', '../'),
(148, 'Update Settings API', 'save_settings', 'settings', 'serv_update.php', 'API', '../');

-- --------------------------------------------------------

--
-- Table structure for table `local_routes_it`
--

CREATE TABLE `local_routes_it` (
  `route_id` int(11) NOT NULL,
  `route_name` text NOT NULL,
  `route_src` text DEFAULT NULL,
  `route_family` varchar(30) DEFAULT NULL,
  `route_path` text DEFAULT NULL,
  `route_type` varchar(20) NOT NULL,
  `route_pointer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `local_routes_it`
--

INSERT INTO `local_routes_it` (`route_id`, `route_name`, `route_src`, `route_family`, `route_path`, `route_type`, `route_pointer`) VALUES
(1, 'Dashboard Page', 'dashboard/', NULL, 'index.php', 'page', '../'),
(2, 'Logout Page', 'logout/', NULL, 'logout.php', 'page', '../'),
(3, 'Settings Page', 'settings_lists/', NULL, 'settings.php', 'page', '../'),
(4, 'Profile Page', 'profile/', NULL, 'profile.php', 'page', '../'),
(5, 'Groups Page', 'groups/', NULL, 'groups.php', 'page', '../'),
(6, 'Calendar Page', 'calendar/', NULL, 'calendar.php', 'page', '../'),
(7, 'Messages Page', 'messages/', NULL, 'messages.php', 'page', '../'),
(8, 'Tasks Page', 'tasks/', NULL, 'tasks_list.php', 'page', '../'),
(9, 'logs API', 'get_sys_logs', 'sys_logs', 'serv_logs.php', 'API', '../'),
(37, 'Group new API', 'add_group_list', 'groups', 'serv_new.php', 'API', '../'),
(38, 'Group list API', 'get_group_list', 'groups', 'serv_list.php', 'API', '../'),
(39, 'Get Group Data', 'get_group_data', 'groups', 'serv_view.php', 'API', '../'),
(40, 'Upload file to Group API', 'upload_group_list', 'groups', 'serv_file_new.php', 'API', '../'),
(41, 'add member to Group API', 'add_group_member', 'groups', 'serv_member_new.php', 'API', '../'),
(44, 'View Page', 'tasks/view/', 'tasks', 'tasks_view.php', 'page', '../../'),
(45, 'List Page API', 'get_tasks_list', 'tasks', 'serv_list.php', 'API', '../'),
(46, 'New Page API', 'add_tasks_list', 'tasks', 'serv_new.php', 'API', '../'),
(47, 'View Page API', 'update_tasks_list', 'tasks', 'serv_update.php', 'API', '../'),
(49, 'List Page', 'tickets/list/', 'tickets', 'tickets_list.php', 'page', '../../'),
(51, 'View Page', 'tickets/view/', 'tickets', 'tickets_view.php', 'page', '../../'),
(52, 'List Page API', 'get_tickets_list', 'tickets', 'serv_list.php', 'API', '../'),
(54, 'New Page API', 'add_tickets_list', 'tickets', 'serv_new.php', 'API', '../'),
(55, 'update Page API', 'update_tickets_list', 'tickets', 'serv_update.php', 'API', '../'),
(56, 'Upload new file version to Group API', 'version_group_list', 'groups', 'serv_file_version.php', 'API', '../'),
(57, 'Messages new API', 'add_messages_list', 'messages', 'serv_new.php', 'API', '../'),
(58, 'messages list API', 'get_messages_list', 'messages', 'serv_list.php', 'API', '../'),
(59, 'messages chats list API', 'get_messages_chats', 'messages', 'serv_list_chats.php', 'API', '../'),
(60, 'Messages chats new API', 'send_messages_list', 'messages', 'send_new.php', 'API', '../'),
(61, 'Assets new API', 'add_assets_list', 'assets', 'serv_new.php', 'API', '../'),
(62, 'Assets list API', 'get_assets_list', 'assets', 'serv_list.php', 'API', '../'),
(63, 'Assets List Page', 'assets/list/', 'tickets', 'assets_list.php', 'page', '../../'),
(64, 'Update Assets Page API', 'update_assets_list', 'assets', 'serv_update.php', 'API', '../'),
(65, 'Users List Page', 'users/list/', 'users', 'users_list.php', 'page', '../../'),
(66, 'users View Page', 'users/view/', 'users', 'users_view.php', 'page', '../../'),
(67, 'users List Page API', 'get_users_list', 'users', 'serv_list.php', 'API', '../'),
(69, 'users New Page API', 'add_users_list', 'users', 'serv_new.php', 'API', '../'),
(70, 'users update Page API', 'update_users_list', 'users', 'serv_update.php', 'API', '../'),
(71, 'View assets Page', 'assets/view/', 'assets', 'assets_view.php', 'page', '../../'),
(73, 'Assets Assigns API', 'get_assets_assigns', 'assets', 'serv_assigns.php', 'API', '../'),
(74, 'TC List Page API', 'get_tc_list', 'settings/tc', 'serv_list.php', 'API', '../'),
(75, 'TC New API', 'add_tc_list', 'settings/tc', 'serv_new.php', 'API', '../'),
(76, 'TC Update API', 'update_tc_list', 'settings/tc', 'serv_update.php', 'API', '../'),
(77, 'ts List Page API', 'get_ts_list', 'settings/ts', 'serv_list.php', 'API', '../'),
(78, 'ts New API', 'add_ts_list', 'settings/ts', 'serv_new.php', 'API', '../'),
(79, 'ts Update API', 'update_ts_list', 'settings/ts', 'serv_update.php', 'API', '../'),
(80, 'pp List Page API', 'get_pp_list', 'settings/pp', 'serv_list.php', 'API', '../'),
(81, 'pp New API', 'add_pp_list', 'settings/pp', 'serv_new.php', 'API', '../'),
(82, 'pp Update API', 'update_pp_list', 'settings/pp', 'serv_update.php', 'API', '../'),
(83, 'ac List Page API', 'get_ac_list', 'settings/ac', 'serv_list.php', 'API', '../'),
(84, 'ac New API', 'add_ac_list', 'settings/ac', 'serv_new.php', 'API', '../'),
(85, 'ac Update API', 'update_ac_list', 'settings/ac', 'serv_update.php', 'API', '../'),
(86, 'as List Page API', 'get_as_list', 'settings/as', 'serv_list.php', 'API', '../'),
(87, 'as New API', 'add_as_list', 'settings/as', 'serv_new.php', 'API', '../'),
(88, 'as Update API', 'update_as_list', 'settings/as', 'serv_update.php', 'API', '../'),
(89, 'Depts List Page', 'departments/list/', 'departments', 'departments_list.php', 'page', '../../'),
(90, 'departments List Page API', 'get_departments_list', 'departments', 'serv_list.php', 'API', '../'),
(91, 'departments New Page API', 'add_departments_list', 'departments', 'serv_new.php', 'API', '../'),
(92, 'Update departments Page API', 'update_departments_list', 'departments', 'serv_update.php', 'API', '../'),
(93, 'designations List Page', 'designations/list/', 'designations', 'designations_list.php', 'page', '../../'),
(94, 'designations List Page API', 'get_designations_list', 'designations', 'serv_list.php', 'API', '../'),
(95, 'designations New Page API', 'add_designations_list', 'designations', 'serv_new.php', 'API', '../'),
(96, 'Update designations Page API', 'update_designations_list', 'designations', 'serv_update.php', 'API', '../'),
(97, 'Depts chart List Page', 'chart/list/', 'departments', 'departments_chart.php', 'page', '../../'),
(98, 'Feedback Page', 'feedback/', NULL, 'feedback.php', 'page', '../'),
(99, 'get_unseen_notifications_list', 'get_unseen_notifications_list', 'unseen', 'serv_list.php', 'API', '../'),
(100, 'Notifications List Page', 'notifications/list/', 'notifications', 'notifications_list.php', 'page', '../../'),
(101, 'Notifications List Page API', 'get_notifications_list', 'notifications', 'serv_list.php', 'API', '../'),
(102, 'Strategy List Page', 'strategies/list/', 'strategies', 'strategies_list.php', 'page', '../../'),
(103, 'Strategy View Page', 'strategies/view/', 'strategies', 'strategies_view.php', 'page', '../../'),
(104, 'USER STATUS API', 'update_user_status', 'users', 'serv_status.php', 'API', '../');

-- --------------------------------------------------------

--
-- Table structure for table `local_routes_rc`
--

CREATE TABLE `local_routes_rc` (
  `route_id` int(11) NOT NULL,
  `route_name` text NOT NULL,
  `route_src` text DEFAULT NULL,
  `route_family` varchar(30) DEFAULT NULL,
  `route_path` text DEFAULT NULL,
  `route_type` varchar(20) NOT NULL,
  `route_pointer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `local_routes_rc`
--

INSERT INTO `local_routes_rc` (`route_id`, `route_name`, `route_src`, `route_family`, `route_path`, `route_type`, `route_pointer`) VALUES
(1, 'Dashboard Page', 'dashboard/', NULL, 'index.php', 'page', '../'),
(2, 'Logout Page', 'logout/', NULL, 'logout.php', 'page', '../'),
(3, 'Settings Page', 'settings/', NULL, 'settings.php', 'page', '../'),
(4, 'Profile Page', 'profile/', NULL, 'profile.php', 'page', '../'),
(5, 'Groups Page', 'groups/', NULL, 'groups.php', 'page', '../'),
(6, 'Calendar Page', 'calendar/', NULL, 'calendar.php', 'page', '../'),
(7, 'Messages Page', 'messages/', NULL, 'messages.php', 'page', '../'),
(8, 'Tasks Page', 'tasks/', NULL, 'tasks_list.php', 'page', '../'),
(9, 'logs API', 'get_sys_logs', 'sys_logs', 'serv_logs.php', 'API', '../'),
(10, 'switcher Page', 'switch_sys_mode/', NULL, 'a_switch.php', 'page', '../'),
(37, 'Group new API', 'add_group_list', 'groups', 'serv_new.php', 'API', '../'),
(38, 'Group list API', 'get_group_list', 'groups', 'serv_list.php', 'API', '../'),
(39, 'Get Group Data', 'get_group_data', 'groups', 'serv_view.php', 'API', '../'),
(40, 'Upload file to Group API', 'upload_group_list', 'groups', 'serv_file_new.php', 'API', '../'),
(41, 'add member to Group API', 'add_group_member', 'groups', 'serv_member_new.php', 'API', '../'),
(44, 'View Page', 'tasks/view/', 'tasks', 'tasks_view.php', 'page', '../../'),
(45, 'List Page API', 'get_tasks_list', 'tasks', 'serv_list.php', 'API', '../'),
(46, 'New Page API', 'add_tasks_list', 'tasks', 'serv_new.php', 'API', '../'),
(47, 'View Page API', 'update_tasks_list', 'tasks', 'serv_update.php', 'API', '../'),
(49, 'List Page', 'tickets/list/', 'tickets', 'tickets_list.php', 'page', '../../'),
(51, 'View Page', 'tickets/view/', 'tickets', 'tickets_view.php', 'page', '../../'),
(52, 'List Page API', 'get_tickets_list', 'tickets', 'serv_list.php', 'API', '../'),
(54, 'New Page API', 'add_tickets_list', 'tickets', 'serv_new.php', 'API', '../'),
(56, 'Upload new file version to Group API', 'version_group_list', 'groups', 'serv_file_version.php', 'API', '../'),
(57, 'Messages new API', 'add_messages_list', 'messages', 'serv_new.php', 'API', '../'),
(58, 'messages list API', 'get_messages_list', 'messages', 'serv_list.php', 'API', '../'),
(59, 'messages chats list API', 'get_messages_chats', 'messages', 'serv_list_chats.php', 'API', '../'),
(60, 'Messages chats new API', 'send_messages_list', 'messages', 'send_new.php', 'API', '../'),
(89, 'Depts List Page', 'departments/list/', 'departments', 'departments_list.php', 'page', '../../'),
(90, 'departments List Page API', 'get_departments_list', 'departments', 'serv_list.php', 'API', '../'),
(91, 'departments New Page API', 'add_departments_list', 'departments', 'serv_new.php', 'API', '../'),
(92, 'Update departments Page API', 'update_departments_list', 'departments', 'serv_update.php', 'API', '../'),
(93, 'designations List Page', 'designations/list/', 'designations', 'designations_list.php', 'page', '../../'),
(94, 'designations List Page API', 'get_designations_list', 'designations', 'serv_list.php', 'API', '../'),
(95, 'designations New Page API', 'add_designations_list', 'designations', 'serv_new.php', 'API', '../'),
(96, 'Update designations Page API', 'update_designations_list', 'designations', 'serv_update.php', 'API', '../'),
(97, 'employees List Page', 'employees/list/', 'employees', 'employees_list.php', 'page', '../../'),
(98, 'employees List Page API', 'get_employees_list', 'employees', 'serv_list.php', 'API', '../'),
(99, 'employees New Page API', 'add_employees_list', 'employees', 'serv_new.php', 'API', '../'),
(100, 'Update employees Page API', 'update_employees_list', 'employees', 'serv_update.php', 'API', '../'),
(101, 'employee View Page', 'employees/view/', 'employees', 'employees_view.php', 'page', '../../'),
(102, 'Update Creds employees Page API', 'creds_employees_list', 'employees', 'serv_update_creds.php', 'API', '../'),
(103, 'Requests List Page', 'requests/list/', 'requests', 'requests_list.php', 'page', '../../'),
(104, 'hr_leaves List Page', 'hr_leaves/list/', 'hr_leaves', 'hr_leaves_list.php', 'page', '../../'),
(105, 'hr_leaves List Page API', 'get_hr_leaves_list', 'hr_leaves', 'serv_list.php', 'API', '../'),
(106, 'hr_leaves New Page API', 'add_hr_leaves_list', 'hr_leaves', 'serv_new.php', 'API', '../'),
(107, 'Update hr_leaves Page API', 'update_hr_leaves_list', 'hr_leaves', 'serv_update.php', 'API', '../'),
(108, 'hr_leaves_types List Page', 'hr_leaves_types/list/', 'hr_leaves_types', 'hr_leaves_types_list.php', 'page', '../../'),
(109, 'hr_leaves_types List Page API', 'get_hr_leaves_types_list', 'hr_leaves_types', 'serv_list.php', 'API', '../'),
(110, 'hr_leaves_types New Page API', 'add_hr_leaves_types_list', 'hr_leaves_types', 'serv_new.php', 'API', '../'),
(111, 'Update hr_leaves_types Page API', 'update_hr_leaves_types_list', 'hr_leaves_types', 'serv_update.php', 'API', '../'),
(112, 'hr_documents List Page', 'hr_documents/list/', 'hr_documents', 'hr_documents_list.php', 'page', '../../'),
(113, 'hr_documents List Page API', 'get_hr_documents_list', 'hr_documents', 'serv_list.php', 'API', '../'),
(114, 'hr_documents New Page API', 'add_hr_documents_list', 'hr_documents', 'serv_new.php', 'API', '../'),
(115, 'Depts chart List Page', 'chart/list/', 'departments', 'departments_chart.php', 'page', '../../'),
(116, 'hr_permissions List Page', 'hr_permissions/list/', 'hr_permissions', 'hr_permissions_list.php', 'page', '../../'),
(117, 'hr_permissions List Page API', 'get_hr_permissions_list', 'hr_permissions', 'serv_list.php', 'API', '../'),
(118, 'hr_permissions New Page API', 'add_hr_permissions_list', 'hr_permissions', 'serv_new.php', 'API', '../'),
(119, 'Update hr_permissions Page API', 'update_hr_permissions_list', 'hr_permissions', 'serv_update.php', 'API', '../'),
(120, 'hr_da List Page', 'hr_da/list/', 'hr_da', 'hr_da_list.php', 'page', '../../'),
(121, 'hr_da List Page API', 'get_hr_da_list', 'hr_da', 'serv_list.php', 'API', '../'),
(122, 'hr_da New Page API', 'add_hr_da_list', 'hr_da', 'serv_new.php', 'API', '../'),
(123, 'Update hr_da Page API', 'update_hr_da_list', 'hr_da', 'serv_update.php', 'API', '../'),
(124, 'hr_attendance List Page', 'hr_attendance/list/', 'hr_attendance', 'hr_attendance_list.php', 'page', '../../'),
(125, 'hr_attendance List Page API', 'get_hr_attendance_list', 'hr_attendance', 'serv_list.php', 'API', '../'),
(126, 'hr_attendance New Page API', 'add_hr_attendance_list', 'hr_attendance', 'serv_new.php', 'API', '../'),
(127, 'Update hr_attendance Page API', 'update_hr_attendance_list', 'hr_attendance', 'serv_update.php', 'API', '../'),
(128, 'hr_exit_interviews List Page', 'hr_exit_interviews/list/', 'hr_exit_interviews', 'hr_exit_interviews_list.php', 'page', '../../'),
(129, 'hr_exit_interviews List Page API', 'get_hr_exit_interviews_list', 'hr_exit_interviews', 'serv_list.php', 'API', '../'),
(130, 'hr_exit_interviews New Page API', 'add_hr_exit_interviews_list', 'hr_exit_interviews', 'serv_new.php', 'API', '../'),
(131, 'Update hr_exit_interviews Page API', 'update_hr_exit_interviews_list', 'hr_exit_interviews', 'serv_update.php', 'API', '../'),
(132, 'hr_performance List Page', 'hr_performance/list/', 'hr_performance', 'hr_performance_list.php', 'page', '../../'),
(133, 'hr_performance List Page API', 'get_hr_performance_list', 'hr_performance', 'serv_list.php', 'API', '../'),
(134, 'hr_performance New Page API', 'add_hr_performance_list', 'hr_performance', 'serv_new.php', 'API', '../'),
(135, 'Update hr_performance Page API', 'update_hr_performance_list', 'hr_performance', 'serv_update.php', 'API', '../'),
(136, 'Upload file to CHat API', 'upload_messages_list', 'messages', 'serv_file_new.php', 'API', '../'),
(137, 'Upload new file version to Chat API', 'version_messages_list', 'messages', 'serv_file_version.php', 'API', '../'),
(138, 'get chat files list API', 'files_messages_list', 'messages', 'serv_file_list.php', 'API', '../'),
(139, 'Feedback Page', 'feedback/', NULL, 'feedback.php', 'page', '../'),
(140, 'submit_feedback_form', 'submit_feedback_form', 'feedback', 'serv_new.php', 'API', '../'),
(141, 'users update Page API', 'update_users_list', 'users', 'serv_update.php', 'API', '../'),
(142, 'get_unseen_notifications_list', 'get_unseen_notifications_list', 'unseen', 'serv_list.php', 'API', '../'),
(143, 'Notifications List Page', 'notifications/list/', 'notifications', 'notifications_list.php', 'page', '../../'),
(144, 'Notifications List Page API', 'get_notifications_list', 'notifications', 'serv_list.php', 'API', '../'),
(145, 'Strategy List Page', 'strategies/list/', 'strategies', 'strategies_list.php', 'page', '../../'),
(146, 'Strategy View Page', 'strategies/view/', 'strategies', 'strategies_view.php', 'page', '../../'),
(147, 'USER STATUS API', 'update_user_status', 'users', 'serv_status.php', 'API', '../'),
(148, 'List Page', 'ss/list/', 'ss', 'ss_list.php', 'page', '../../'),
(149, 'View Page', 'ss/view/', 'ss', 'ss_view.php', 'page', '../../'),
(150, 'List Page API', 'get_ss_list', 'ss', 'serv_list.php', 'API', '../'),
(151, 'New Page API', 'add_ss_list', 'ss', 'serv_new.php', 'API', '../'),
(152, 'Update Page API', 'update_ss_list', 'ss', 'serv_update.php', 'API', '../'),
(153, 'communications List Page', 'communications/list/', 'communications', 'communications_list.php', 'page', '../../'),
(154, 'communications View Page', 'communications/view/', 'communications', 'communications_view.php', 'page', '../../'),
(155, 'communications List Page API', 'get_communications_list', 'communications', 'serv_list.php', 'API', '../'),
(156, 'communications New Page API', 'add_communications_list', 'communications', 'serv_new.php', 'API', '../'),
(157, 'communications Update Page API', 'update_communications_list', 'communications', 'serv_update.php', 'API', '../'),
(158, 'Strategy New Page', 'strategies/new/', 'strategies', 'strategies_new.php', 'page', '../../'),
(159, 'strategies New Page API', 'add_strategies_list', 'strategies', 'serv_new.php', 'API', '../'),
(160, 'Strategies priority New Page API', 'add_strategies_priority', 'strategies', 'serv_priorities_new.php', 'API', '../'),
(161, 'goal priority New Page API', 'add_strategies_goals', 'strategies', 'serv_goals_new.php', 'API', '../'),
(162, 'Tactics New Page API', 'add_strategies_tactics', 'strategies', 'serv_tactics_new.php', 'API', '../'),
(163, 'Strategies priority Update Page API', 'update_priorities_list', 'strategies', 'serv_priorities_update.php', 'API', '../'),
(164, 'Strategies items Delete Page API', 'delete_strategy_item', 'strategies', 'serv_items_delete.php', 'API', '../'),
(165, 'Strategies priority get Page API', 'get_priorities_list', 'strategies', 'serv_priorities_get.php', 'API', '../'),
(166, 'Milestone New Page API', 'add_strategies_milestone', 'strategies', 'serv_milestones_new.php', 'API', '../'),
(168, 'ATP Applications', 'get_atps_apps', 'atps', 'serv_apps.php', 'API', '../'),
(169, 'get_select_data', 'get_select_data', 'select', 'select_control.php', 'select', '../../'),
(170, 'Update Notification Page API', 'read_notification_list', 'notifications', 'serv_as_read.php', 'API', '../'),
(171, 'Update Settings API', 'save_settings', 'settings', 'serv_update.php', 'API', '../'),
(172, 'Report system error', 'report_sys_error', 'settings', 'serv_error.php', 'API', '../');

-- --------------------------------------------------------

--
-- Table structure for table `local_routes_root`
--

CREATE TABLE `local_routes_root` (
  `route_id` int(11) NOT NULL,
  `route_name` text NOT NULL,
  `route_src` text DEFAULT NULL,
  `route_family` varchar(30) DEFAULT NULL,
  `route_path` text DEFAULT NULL,
  `route_type` varchar(20) NOT NULL,
  `route_pointer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `local_routes_root`
--

INSERT INTO `local_routes_root` (`route_id`, `route_name`, `route_src`, `route_family`, `route_path`, `route_type`, `route_pointer`) VALUES
(1, 'Dashboard Page', 'dashboard/', NULL, 'index.php', 'page', '../'),
(2, 'Logout Page', 'logout/', NULL, 'logout.php', 'page', '../'),
(3, 'Settings Page', 'settings_lists/', NULL, 'settings.php', 'page', '../'),
(4, 'Profile Page', 'profile/', NULL, 'profile.php', 'page', '../'),
(5, 'Groups Page', 'groups/', NULL, 'groups.php', 'page', '../'),
(6, 'Calendar Page', 'calendar/', NULL, 'calendar.php', 'page', '../'),
(7, 'Messages Page', 'messages/', NULL, 'messages.php', 'page', '../'),
(8, 'Tasks Page', 'tasks/', NULL, 'tasks_list.php', 'page', '../'),
(9, 'logs API', 'get_sys_logs', 'sys_logs', 'serv_logs.php', 'API', '../'),
(37, 'Group new API', 'add_group_list', 'groups', 'serv_new.php', 'API', '../'),
(38, 'Group list API', 'get_group_list', 'groups', 'serv_list.php', 'API', '../'),
(39, 'Get Group Data', 'get_group_data', 'groups', 'serv_view.php', 'API', '../'),
(40, 'Upload file to Group API', 'upload_group_list', 'groups', 'serv_file_new.php', 'API', '../'),
(41, 'add member to Group API', 'add_group_member', 'groups', 'serv_member_new.php', 'API', '../'),
(44, 'View Page', 'tasks/view/', 'tasks', 'tasks_view.php', 'page', '../../'),
(45, 'List Page API', 'get_tasks_list', 'tasks', 'serv_list.php', 'API', '../'),
(46, 'New Page API', 'add_tasks_list', 'tasks', 'serv_new.php', 'API', '../'),
(47, 'View Page API', 'update_tasks_list', 'tasks', 'serv_update.php', 'API', '../'),
(49, 'List Page', 'tickets/list/', 'tickets', 'tickets_list.php', 'page', '../../'),
(51, 'View Page', 'tickets/view/', 'tickets', 'tickets_view.php', 'page', '../../'),
(52, 'List Page API', 'get_tickets_list', 'tickets', 'serv_list.php', 'API', '../'),
(54, 'New Page API', 'add_tickets_list', 'tickets', 'serv_new.php', 'API', '../'),
(55, 'update Page API', 'update_tickets_list', 'tickets', 'serv_update.php', 'API', '../'),
(56, 'Upload new file version to Group API', 'version_group_list', 'groups', 'serv_file_version.php', 'API', '../'),
(57, 'Messages new API', 'add_messages_list', 'messages', 'serv_new.php', 'API', '../'),
(58, 'messages list API', 'get_messages_list', 'messages', 'serv_list.php', 'API', '../'),
(59, 'messages chats list API', 'get_messages_chats', 'messages', 'serv_list_chats.php', 'API', '../'),
(60, 'Messages chats new API', 'send_messages_list', 'messages', 'send_new.php', 'API', '../'),
(61, 'Assets new API', 'add_assets_list', 'assets', 'serv_new.php', 'API', '../'),
(62, 'Assets list API', 'get_assets_list', 'assets', 'serv_list.php', 'API', '../'),
(63, 'Assets List Page', 'assets/list/', 'tickets', 'assets_list.php', 'page', '../../'),
(64, 'Update Assets Page API', 'update_assets_list', 'assets', 'serv_update.php', 'API', '../'),
(65, 'Users List Page', 'users/list/', 'users', 'users_list.php', 'page', '../../'),
(66, 'users View Page', 'users/view/', 'users', 'users_view.php', 'page', '../../'),
(67, 'users List Page API', 'get_users_list', 'users', 'serv_list.php', 'API', '../'),
(69, 'users New Page API', 'add_users_list', 'users', 'serv_new.php', 'API', '../'),
(70, 'users update Page API', 'update_users_list', 'users', 'serv_update.php', 'API', '../'),
(71, 'View assets Page', 'assets/view/', 'assets', 'assets_view.php', 'page', '../../'),
(73, 'Assets Assigns API', 'get_assets_assigns', 'assets', 'serv_assigns.php', 'API', '../'),
(74, 'TC List Page API', 'get_tc_list', 'settings/tc', 'serv_list.php', 'API', '../'),
(75, 'TC New API', 'add_tc_list', 'settings/tc', 'serv_new.php', 'API', '../'),
(76, 'TC Update API', 'update_tc_list', 'settings/tc', 'serv_update.php', 'API', '../'),
(77, 'ts List Page API', 'get_ts_list', 'settings/ts', 'serv_list.php', 'API', '../'),
(78, 'ts New API', 'add_ts_list', 'settings/ts', 'serv_new.php', 'API', '../'),
(79, 'ts Update API', 'update_ts_list', 'settings/ts', 'serv_update.php', 'API', '../'),
(80, 'pp List Page API', 'get_pp_list', 'settings/pp', 'serv_list.php', 'API', '../'),
(81, 'pp New API', 'add_pp_list', 'settings/pp', 'serv_new.php', 'API', '../'),
(82, 'pp Update API', 'update_pp_list', 'settings/pp', 'serv_update.php', 'API', '../'),
(83, 'ac List Page API', 'get_ac_list', 'settings/ac', 'serv_list.php', 'API', '../'),
(84, 'ac New API', 'add_ac_list', 'settings/ac', 'serv_new.php', 'API', '../'),
(85, 'ac Update API', 'update_ac_list', 'settings/ac', 'serv_update.php', 'API', '../'),
(86, 'as List Page API', 'get_as_list', 'settings/as', 'serv_list.php', 'API', '../'),
(87, 'as New API', 'add_as_list', 'settings/as', 'serv_new.php', 'API', '../'),
(88, 'as Update API', 'update_as_list', 'settings/as', 'serv_update.php', 'API', '../'),
(89, 'Depts List Page', 'departments/list/', 'departments', 'departments_list.php', 'page', '../../'),
(90, 'departments List Page API', 'get_departments_list', 'departments', 'serv_list.php', 'API', '../'),
(91, 'departments New Page API', 'add_departments_list', 'departments', 'serv_new.php', 'API', '../'),
(92, 'Update departments Page API', 'update_departments_list', 'departments', 'serv_update.php', 'API', '../'),
(93, 'designations List Page', 'designations/list/', 'designations', 'designations_list.php', 'page', '../../'),
(94, 'designations List Page API', 'get_designations_list', 'designations', 'serv_list.php', 'API', '../'),
(95, 'designations New Page API', 'add_designations_list', 'designations', 'serv_new.php', 'API', '../'),
(96, 'Update designations Page API', 'update_designations_list', 'designations', 'serv_update.php', 'API', '../'),
(97, 'Depts chart List Page', 'chart/list/', 'departments', 'departments_chart.php', 'page', '../../'),
(98, 'Get Employee Assets Page API', 'get_employee_assets', 'employees', 'serv_assets_list.php', 'API', '../'),
(99, 'Update Employees Page API', 'update_employees_list', 'employees', 'serv_update.php', 'API', '../'),
(100, 'submit_feedback_form', 'submit_feedback_form', 'feedback', 'serv_new.php', 'API', '../'),
(101, 'get_unseen_notifications_list', 'get_unseen_notifications_list', 'unseen', 'serv_list.php', 'API', '../'),
(102, 'Notifications List Page', 'notifications/list/', 'notifications', 'notifications_list.php', 'page', '../../'),
(103, 'Notifications List Page API', 'get_notifications_list', 'notifications', 'serv_list.php', 'API', '../'),
(104, 'LT List Page API', 'get_lt_list', 'settings/lt', 'serv_list.php', 'API', '../'),
(105, 'LT New API', 'add_lt_list', 'settings/lt', 'serv_new.php', 'API', '../'),
(106, 'LT Update API', 'update_lt_list', 'settings/lt', 'serv_update.php', 'API', '../'),
(108, 'Strategy List Page', 'strategies/list/', 'strategies', 'strategies_list.php', 'page', '../../'),
(109, 'Strategy View Page', 'strategies/view/', 'strategies', 'strategies_view.php', 'page', '../../'),
(110, 'USER STATUS API', 'update_user_status', 'users', 'serv_status.php', 'API', '../'),
(111, 'ct List Page API', 'get_ct_list', 'settings/ct', 'serv_list.php', 'API', '../'),
(112, 'ct New API', 'add_ct_list', 'settings/ct', 'serv_new.php', 'API', '../'),
(113, 'ct Update API', 'update_ct_list', 'settings/ct', 'serv_update.php', 'API', '../'),
(114, 'ss List Page API', 'get_ss_list', 'settings/ss', 'serv_list.php', 'API', '../'),
(115, 'ss New API', 'add_ss_list', 'settings/ss', 'serv_new.php', 'API', '../'),
(116, 'ss Update API', 'update_ss_list', 'settings/ss', 'serv_update.php', 'API', '../'),
(117, 'Emp Services Update API', 'save_user_services', 'users', 'serv_update_service.php', 'API', '../');

-- --------------------------------------------------------

--
-- Table structure for table `m_communications_list`
--

CREATE TABLE `m_communications_list` (
  `communication_id` int(11) NOT NULL,
  `communication_code` varchar(10) NOT NULL,
  `external_party_name` varchar(100) NOT NULL,
  `communication_subject` text DEFAULT NULL,
  `communication_description` text DEFAULT NULL,
  `information_shared` text DEFAULT NULL,
  `communication_type_id` int(11) NOT NULL,
  `communication_status_id` int(11) NOT NULL,
  `requested_date` datetime DEFAULT NULL,
  `requested_by` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `is_approved_1` int(1) NOT NULL DEFAULT 0,
  `approved_1_date` datetime DEFAULT NULL,
  `approval_id_1` int(11) DEFAULT NULL,
  `approved_1_notes` text DEFAULT NULL,
  `is_approved_2` int(1) NOT NULL DEFAULT 0,
  `approved_2_date` int(11) DEFAULT NULL,
  `approval_id_2` int(11) DEFAULT NULL,
  `approved_2_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_communications_list_status`
--

CREATE TABLE `m_communications_list_status` (
  `communication_status_id` int(11) NOT NULL,
  `communication_status_name` varchar(255) NOT NULL,
  `communication_status_name_ar` varchar(255) DEFAULT NULL,
  `status_color` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_communications_list_status`
--

INSERT INTO `m_communications_list_status` (`communication_status_id`, `communication_status_name`, `communication_status_name_ar`, `status_color`) VALUES
(1, 'Pending Approval', 'Pending Approval', 'FF7E0C'),
(2, 'In Progress', 'Approved By 1', '14F4FF'),
(3, 'Approved', 'approved by 2', 'C11525'),
(4, 'Done', 'Done', '50D24D'),
(5, 'Rejected', 'Rejected', 'FFD24D');

-- --------------------------------------------------------

--
-- Table structure for table `m_communications_list_types`
--

CREATE TABLE `m_communications_list_types` (
  `communication_type_id` int(11) NOT NULL,
  `communication_type_name` varchar(255) NOT NULL,
  `communication_type_name_ar` varchar(255) DEFAULT NULL,
  `approval_id_1` int(11) NOT NULL,
  `approval_id_2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_communications_list_types`
--

INSERT INTO `m_communications_list_types` (`communication_type_id`, `communication_type_name`, `communication_type_name_ar`, `approval_id_1`, `approval_id_2`) VALUES
(1, 'Email', 'Email', 36, 39),
(2, 'Call', 'Call', 35, 35),
(3, 'Paper', 'Paper', 34, 34),
(6, 'HQ Communication', 'Internal Communication', 34, 35);

-- --------------------------------------------------------

--
-- Table structure for table `m_operational_projects`
--

CREATE TABLE `m_operational_projects` (
  `project_id` int(11) NOT NULL,
  `project_ref` varchar(100) NOT NULL,
  `project_code` varchar(200) NOT NULL,
  `project_name` text NOT NULL,
  `project_description` text DEFAULT NULL,
  `project_start_date` date NOT NULL,
  `project_end_date` date NOT NULL,
  `project_period` text DEFAULT NULL,
  `project_analysis` text DEFAULT NULL,
  `project_recommendations` text DEFAULT NULL,
  `plan_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `project_status_id` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_operational_projects_kpis`
--

CREATE TABLE `m_operational_projects_kpis` (
  `linked_kpi_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `objective_id` int(11) NOT NULL,
  `kpi_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_operational_projects_milestones`
--

CREATE TABLE `m_operational_projects_milestones` (
  `milestone_id` int(11) NOT NULL,
  `milestone_ref` varchar(100) DEFAULT NULL,
  `milestone_title` varchar(100) DEFAULT NULL,
  `milestone_description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `order_no` int(3) NOT NULL DEFAULT 0,
  `milestone_weight` int(3) NOT NULL DEFAULT 0,
  `kpi_id` int(11) NOT NULL,
  `objective_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_strategic_plans`
--

CREATE TABLE `m_strategic_plans` (
  `plan_id` int(11) NOT NULL,
  `plan_ref` varchar(50) NOT NULL,
  `plan_title` varchar(200) DEFAULT NULL,
  `plan_vision` text DEFAULT NULL,
  `plan_mission` text DEFAULT NULL,
  `plan_values` text DEFAULT NULL,
  `plan_period` varchar(150) NOT NULL DEFAULT '0',
  `plan_from` int(4) NOT NULL DEFAULT 0,
  `plan_to` int(4) NOT NULL DEFAULT 0,
  `plan_level` int(11) NOT NULL COMMENT 'department_id',
  `plan_status_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `is_published` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_strategic_plans_external_maps`
--

CREATE TABLE `m_strategic_plans_external_maps` (
  `record_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `objective_id` int(11) NOT NULL,
  `external_entity_name` varchar(200) NOT NULL,
  `map_description` text DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `added_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_strategic_plans_internal_maps`
--

CREATE TABLE `m_strategic_plans_internal_maps` (
  `local_map_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `objective_id` int(11) NOT NULL,
  `kpi_id` int(11) NOT NULL,
  `milestone_id` int(11) NOT NULL,
  `task_title` varchar(200) NOT NULL,
  `task_description` text DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_task_assigned` int(1) NOT NULL DEFAULT 0,
  `added_by` int(11) DEFAULT NULL,
  `added_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_strategic_plans_internal_maps_tasks`
--

CREATE TABLE `m_strategic_plans_internal_maps_tasks` (
  `record_id` int(11) NOT NULL,
  `local_map_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `task_progress` int(3) NOT NULL DEFAULT 0,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_strategic_plans_kpis`
--

CREATE TABLE `m_strategic_plans_kpis` (
  `kpi_id` int(11) NOT NULL,
  `kpi_ref` varchar(100) DEFAULT NULL,
  `kpi_code` varchar(50) NOT NULL,
  `data_source` text DEFAULT NULL,
  `kpi_title` varchar(100) DEFAULT NULL,
  `kpi_description` text DEFAULT NULL,
  `kpi_frequncy_id` text DEFAULT NULL,
  `kpi_formula` text DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `kpi_progress` int(3) NOT NULL,
  `order_no` int(3) NOT NULL DEFAULT 0,
  `kpi_weight` int(3) NOT NULL DEFAULT 0,
  `objective_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_strategic_plans_kpis_freqs`
--

CREATE TABLE `m_strategic_plans_kpis_freqs` (
  `kpi_frequncy_id` int(11) NOT NULL,
  `kpi_frequncy_name` varchar(255) NOT NULL,
  `kpi_frequncy_name_ar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_strategic_plans_kpis_freqs`
--

INSERT INTO `m_strategic_plans_kpis_freqs` (`kpi_frequncy_id`, `kpi_frequncy_name`, `kpi_frequncy_name_ar`) VALUES
(1, 'Monthly', 'Monthly'),
(2, 'Quarterly', 'Quarterly'),
(3, 'Biannually', 'Biannually'),
(4, 'Annually', 'Annually');

-- --------------------------------------------------------

--
-- Table structure for table `m_strategic_plans_milestones`
--

CREATE TABLE `m_strategic_plans_milestones` (
  `milestone_id` int(11) NOT NULL,
  `milestone_ref` varchar(100) DEFAULT NULL,
  `milestone_title` varchar(100) DEFAULT NULL,
  `milestone_description` text DEFAULT NULL,
  `order_no` int(3) NOT NULL DEFAULT 0,
  `milestone_weight` int(3) NOT NULL DEFAULT 0,
  `kpi_id` int(11) NOT NULL,
  `objective_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_strategic_plans_objectives`
--

CREATE TABLE `m_strategic_plans_objectives` (
  `objective_id` int(11) NOT NULL,
  `objective_ref` varchar(100) NOT NULL,
  `objective_title` varchar(100) DEFAULT NULL,
  `objective_description` text DEFAULT NULL,
  `objective_type_id` int(11) NOT NULL,
  `order_no` int(3) NOT NULL DEFAULT 0,
  `objective_weight` int(3) NOT NULL DEFAULT 0,
  `theme_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_strategic_plans_objective_types`
--

CREATE TABLE `m_strategic_plans_objective_types` (
  `objective_type_id` int(11) NOT NULL,
  `objective_type_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_strategic_plans_objective_types`
--

INSERT INTO `m_strategic_plans_objective_types` (`objective_type_id`, `objective_type_name`) VALUES
(1, 'Strategic Objective'),
(2, 'Main Objective'),
(3, 'Enabler Objective');

-- --------------------------------------------------------

--
-- Table structure for table `m_strategic_plans_themes`
--

CREATE TABLE `m_strategic_plans_themes` (
  `theme_id` int(11) NOT NULL,
  `theme_ref` varchar(20) DEFAULT NULL,
  `theme_title` varchar(100) DEFAULT NULL,
  `theme_description` text DEFAULT NULL,
  `order_no` int(3) NOT NULL DEFAULT 0,
  `theme_weight` int(3) NOT NULL DEFAULT 0,
  `plan_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_strategic_studies`
--

CREATE TABLE `m_strategic_studies` (
  `study_id` int(11) NOT NULL,
  `study_ref` varchar(100) DEFAULT NULL,
  `study_title` text DEFAULT NULL,
  `study_overview` text DEFAULT NULL,
  `study_status_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_strategic_studies_pages`
--

CREATE TABLE `m_strategic_studies_pages` (
  `page_id` int(11) NOT NULL,
  `page_title` text DEFAULT NULL,
  `page_content` text DEFAULT NULL,
  `page_type` varchar(20) NOT NULL DEFAULT 'introductry',
  `order_no` int(3) NOT NULL DEFAULT 0,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `study_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ss_list`
--

CREATE TABLE `ss_list` (
  `ss_id` int(11) NOT NULL,
  `ss_ref` varchar(50) NOT NULL,
  `ss_subject` varchar(200) DEFAULT NULL,
  `ss_description` text DEFAULT NULL,
  `ss_remarks` text DEFAULT NULL,
  `ss_attachment` varchar(70) DEFAULT NULL,
  `ss_result_attachment` varchar(70) DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT 1,
  `category_id` int(11) NOT NULL,
  `ss_added_date` datetime NOT NULL,
  `ss_end_date` datetime DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `sent_to_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ss_list_cats`
--

CREATE TABLE `ss_list_cats` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_name_ar` varchar(255) DEFAULT NULL,
  `category_color` varchar(10) DEFAULT '#000000',
  `destination_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ss_list_cats`
--

INSERT INTO `ss_list_cats` (`category_id`, `category_name`, `category_name_ar`, `category_color`, `destination_id`) VALUES
(1, 'Graphic Design', 'Software', '#000000', 0),
(2, 'Translation', 'Hardware', '#000000', 0),
(3, 'Others', 'Others', '#000000', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ss_list_status`
--

CREATE TABLE `ss_list_status` (
  `status_id` int(11) NOT NULL,
  `status_name` text DEFAULT NULL,
  `status_name_ar` text DEFAULT NULL,
  `status_color` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ss_list_status`
--

INSERT INTO `ss_list_status` (`status_id`, `status_name`, `status_name_ar`, `status_color`) VALUES
(1, 'Open', 'Open', 'FF0000'),
(2, 'In Progress', 'In Progress', 'A4A453'),
(3, 'Finished', 'Finished', '29F925');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets_list`
--

CREATE TABLE `support_tickets_list` (
  `ticket_id` int(11) NOT NULL,
  `ticket_ref` varchar(50) NOT NULL,
  `ticket_subject` varchar(200) DEFAULT NULL,
  `ticket_description` text DEFAULT NULL,
  `ticket_remarks` text DEFAULT NULL,
  `ticket_attachment` varchar(70) DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT 1,
  `priority_id` int(11) NOT NULL DEFAULT 1,
  `category_id` int(11) NOT NULL,
  `ticket_added_date` datetime NOT NULL,
  `ticket_end_date` datetime DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL DEFAULT 0,
  `assigned_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets_list_cats`
--

CREATE TABLE `support_tickets_list_cats` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_name_ar` varchar(255) DEFAULT NULL,
  `category_color` varchar(10) DEFAULT '#000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_tickets_list_cats`
--

INSERT INTO `support_tickets_list_cats` (`category_id`, `category_name`, `category_name_ar`, `category_color`) VALUES
(1, 'Software', 'Software', '#000000'),
(2, 'Hardware', 'Hardware', '#000000'),
(3, 'Others', 'Others', '#000000'),
(6, 'incident ticketk', NULL, '#000000');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets_list_status`
--

CREATE TABLE `support_tickets_list_status` (
  `status_id` int(11) NOT NULL,
  `status_name` text DEFAULT NULL,
  `status_name_ar` text DEFAULT NULL,
  `status_color` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_tickets_list_status`
--

INSERT INTO `support_tickets_list_status` (`status_id`, `status_name`, `status_name_ar`, `status_color`) VALUES
(1, 'Open', 'Open', 'FF0000'),
(2, 'In Progress', 'In Progress', 'A4A453'),
(3, 'Resolved', 'Resolved', '29F925');

-- --------------------------------------------------------

--
-- Table structure for table `sys_countries`
--

CREATE TABLE `sys_countries` (
  `country_id` int(11) NOT NULL,
  `iso` char(2) NOT NULL,
  `country_name` varchar(80) NOT NULL,
  `phonecode` int(5) NOT NULL,
  `continent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sys_countries`
--

INSERT INTO `sys_countries` (`country_id`, `iso`, `country_name`, `phonecode`, `continent_id`) VALUES
(1, 'AF', 'afghanistan', 93, 2),
(2, 'AL', 'albania', 355, 2),
(3, 'DZ', 'algeria', 213, 2),
(4, 'AS', 'american samoa', 1684, 2),
(5, 'AD', 'andorra', 376, 2),
(6, 'AO', 'angola', 244, 2),
(7, 'AI', 'anguilla', 1264, 2),
(8, 'AQ', 'antarctica', 0, 2),
(9, 'AG', 'antigua and barbuda', 1268, 2),
(10, 'AR', 'argentina', 54, 2),
(11, 'AM', 'armenia', 374, 2),
(12, 'AW', 'aruba', 297, 2),
(13, 'AU', 'australia', 61, 2),
(14, 'AT', 'austria', 43, 2),
(15, 'AZ', 'azerbaijan', 994, 2),
(16, 'BS', 'bahamas', 1242, 2),
(17, 'BH', 'bahrain', 973, 2),
(18, 'BD', 'bangladesh', 880, 2),
(19, 'BB', 'barbados', 1246, 2),
(20, 'BY', 'belarus', 375, 2),
(21, 'BE', 'belgium', 32, 2),
(22, 'BZ', 'belize', 501, 2),
(23, 'BJ', 'benin', 229, 2),
(24, 'BM', 'bermuda', 1441, 2),
(25, 'BT', 'bhutan', 975, 2),
(26, 'BO', 'bolivia', 591, 2),
(27, 'BA', 'bosnia and herzegovina', 387, 2),
(28, 'BW', 'botswana', 267, 2),
(29, 'BV', 'bouvet island', 0, 2),
(30, 'BR', 'brazil', 55, 2),
(31, 'IO', 'british indian ocean territory', 246, 2),
(32, 'BN', 'brunei darussalam', 673, 2),
(33, 'BG', 'bulgaria', 359, 2),
(34, 'BF', 'burkina faso', 226, 2),
(35, 'BI', 'burundi', 257, 2),
(36, 'KH', 'cambodia', 855, 2),
(37, 'CM', 'cameroon', 237, 2),
(38, 'CA', 'canada', 1, 2),
(39, 'CV', 'cape verde', 238, 2),
(40, 'KY', 'cayman islands', 1345, 2),
(41, 'CF', 'central african republic', 236, 2),
(42, 'TD', 'chad', 235, 2),
(43, 'CL', 'chile', 56, 2),
(44, 'CN', 'china', 86, 2),
(45, 'CX', 'christmas island', 61, 2),
(46, 'CC', 'cocos (keeling) islands', 672, 2),
(47, 'CO', 'colombia', 57, 2),
(48, 'KM', 'comoros', 269, 2),
(49, 'CG', 'congo', 242, 2),
(50, 'CD', 'congo, the democratic republic of the', 242, 2),
(51, 'CK', 'cook islands', 682, 2),
(52, 'CR', 'costa rica', 506, 2),
(53, 'CI', 'cote d\'ivoire', 225, 2),
(54, 'HR', 'croatia', 385, 2),
(55, 'CU', 'cuba', 53, 2),
(56, 'CY', 'cyprus', 357, 2),
(57, 'CZ', 'czech republic', 420, 2),
(58, 'DK', 'denmark', 45, 2),
(59, 'DJ', 'djibouti', 253, 2),
(60, 'DM', 'dominica', 1767, 2),
(61, 'DO', 'dominican republic', 1809, 2),
(62, 'EC', 'ecuador', 593, 2),
(63, 'EG', 'egypt', 20, 2),
(64, 'SV', 'el salvador', 503, 2),
(65, 'GQ', 'equatorial guinea', 240, 2),
(66, 'ER', 'eritrea', 291, 2),
(67, 'EE', 'estonia', 372, 2),
(68, 'ET', 'ethiopia', 251, 2),
(69, 'FK', 'falkland islands (malvinas)', 500, 2),
(70, 'FO', 'faroe islands', 298, 2),
(71, 'FJ', 'fiji', 679, 2),
(72, 'FI', 'finland', 358, 2),
(73, 'FR', 'france', 33, 2),
(74, 'GF', 'french guiana', 594, 2),
(75, 'PF', 'french polynesia', 689, 2),
(76, 'TF', 'french southern territories', 0, 2),
(77, 'GA', 'gabon', 241, 2),
(78, 'GM', 'gambia', 220, 2),
(79, 'GE', 'georgia', 995, 2),
(80, 'DE', 'germany', 49, 2),
(81, 'GH', 'ghana', 233, 2),
(82, 'GI', 'gibraltar', 350, 2),
(83, 'GR', 'greece', 30, 2),
(84, 'GL', 'greenland', 299, 2),
(85, 'GD', 'grenada', 1473, 2),
(86, 'GP', 'guadeloupe', 590, 2),
(87, 'GU', 'guam', 1671, 2),
(88, 'GT', 'guatemala', 502, 2),
(89, 'GN', 'guinea', 224, 2),
(90, 'GW', 'guinea-bissau', 245, 2),
(91, 'GY', 'guyana', 592, 2),
(92, 'HT', 'haiti', 509, 2),
(93, 'HM', 'heard island and mcdonald islands', 0, 2),
(94, 'VA', 'holy see (vatican city state)', 39, 2),
(95, 'HN', 'honduras', 504, 2),
(96, 'HK', 'hong kong', 852, 2),
(97, 'HU', 'hungary', 36, 2),
(98, 'IS', 'iceland', 354, 2),
(99, 'IN', 'india', 91, 2),
(100, 'ID', 'indonesia', 62, 2),
(101, 'IR', 'iran, islamic republic of', 98, 2),
(102, 'IQ', 'iraq', 964, 2),
(103, 'IE', 'ireland', 353, 2),
(105, 'IT', 'italy', 39, 2),
(106, 'JM', 'jamaica', 1876, 2),
(107, 'JP', 'japan', 81, 2),
(108, 'JO', 'jordan', 962, 2),
(109, 'KZ', 'kazakhstan', 7, 2),
(110, 'KE', 'kenya', 254, 2),
(111, 'KI', 'kiribati', 686, 2),
(112, 'KP', 'korea, democratic people\'s republic of', 850, 2),
(113, 'KR', 'korea, republic of', 82, 2),
(114, 'KW', 'kuwait', 965, 2),
(115, 'KG', 'kyrgyzstan', 996, 2),
(116, 'LA', 'lao people\'s democratic republic', 856, 2),
(117, 'LV', 'latvia', 371, 2),
(118, 'LB', 'lebanon', 961, 2),
(119, 'LS', 'lesotho', 266, 2),
(120, 'LR', 'liberia', 231, 2),
(121, 'LY', 'libyan arab jamahiriya', 218, 2),
(122, 'LI', 'liechtenstein', 423, 2),
(123, 'LT', 'lithuania', 370, 2),
(124, 'LU', 'luxembourg', 352, 2),
(125, 'MO', 'macao', 853, 2),
(126, 'MK', 'macedonia, the former yugoslav republic of', 389, 2),
(127, 'MG', 'madagascar', 261, 2),
(128, 'MW', 'malawi', 265, 2),
(129, 'MY', 'malaysia', 60, 2),
(130, 'MV', 'maldives', 960, 2),
(131, 'ML', 'mali', 223, 2),
(132, 'MT', 'malta', 356, 2),
(133, 'MH', 'marshall islands', 692, 2),
(134, 'MQ', 'martinique', 596, 2),
(135, 'MR', 'mauritania', 222, 2),
(136, 'MU', 'mauritius', 230, 2),
(137, 'YT', 'mayotte', 269, 2),
(138, 'MX', 'mexico', 52, 2),
(139, 'FM', 'micronesia, federated states of', 691, 2),
(140, 'MD', 'moldova, republic of', 373, 2),
(141, 'MC', 'monaco', 377, 2),
(142, 'MN', 'mongolia', 976, 2),
(143, 'MS', 'montserrat', 1664, 2),
(144, 'MA', 'morocco', 212, 2),
(145, 'MZ', 'mozambique', 258, 2),
(146, 'MM', 'myanmar', 95, 2),
(147, 'NA', 'namibia', 264, 2),
(148, 'NR', 'nauru', 674, 2),
(149, 'NP', 'nepal', 977, 2),
(150, 'NL', 'netherlands', 31, 2),
(151, 'AN', 'netherlands antilles', 599, 2),
(152, 'NC', 'new caledonia', 687, 2),
(153, 'NZ', 'new zealand', 64, 2),
(154, 'NI', 'nicaragua', 505, 2),
(155, 'NE', 'niger', 227, 2),
(156, 'NG', 'nigeria', 234, 2),
(157, 'NU', 'niue', 683, 2),
(158, 'NF', 'norfolk island', 672, 2),
(159, 'MP', 'northern mariana islands', 1670, 2),
(160, 'NO', 'norway', 47, 2),
(161, 'OM', 'oman', 968, 2),
(162, 'PK', 'pakistan', 92, 2),
(163, 'PW', 'palau', 680, 2),
(164, 'PS', 'palestine', 970, 2),
(165, 'PA', 'panama', 507, 2),
(166, 'PG', 'papua new guinea', 675, 2),
(167, 'PY', 'paraguay', 595, 2),
(168, 'PE', 'peru', 51, 2),
(169, 'PH', 'philippines', 63, 2),
(170, 'PN', 'pitcairn', 0, 2),
(171, 'PL', 'poland', 48, 2),
(172, 'PT', 'portugal', 351, 2),
(173, 'PR', 'puerto rico', 1787, 2),
(174, 'QA', 'qatar', 974, 2),
(175, 'RE', 'reunion', 262, 2),
(176, 'RO', 'romania', 40, 2),
(177, 'RU', 'russian federation', 70, 2),
(178, 'RW', 'rwanda', 250, 2),
(179, 'SH', 'saint helena', 290, 2),
(180, 'KN', 'saint kitts and nevis', 1869, 2),
(181, 'LC', 'saint lucia', 1758, 2),
(182, 'PM', 'saint pierre and miquelon', 508, 2),
(183, 'VC', 'saint vincent and the grenadines', 1784, 2),
(184, 'WS', 'samoa', 684, 2),
(185, 'SM', 'san marino', 378, 2),
(186, 'ST', 'sao tome and principe', 239, 2),
(187, 'SA', 'saudi arabia', 966, 2),
(188, 'SN', 'senegal', 221, 2),
(189, 'CS', 'serbia and montenegro', 381, 2),
(190, 'SC', 'seychelles', 248, 2),
(191, 'SL', 'sierra leone', 232, 2),
(192, 'SG', 'singapore', 65, 2),
(193, 'SK', 'slovakia', 421, 2),
(194, 'SI', 'slovenia', 386, 2),
(195, 'SB', 'solomon islands', 677, 2),
(196, 'SO', 'somalia', 252, 2),
(197, 'ZA', 'south africa', 27, 2),
(198, 'GS', 'south georgia and the south sandwich islands', 0, 2),
(199, 'ES', 'spain', 34, 2),
(200, 'LK', 'sri lanka', 94, 2),
(201, 'SD', 'sudan', 249, 2),
(202, 'SR', 'suriname', 597, 2),
(203, 'SJ', 'svalbard and jan mayen', 47, 2),
(204, 'SZ', 'swaziland', 268, 2),
(205, 'SE', 'sweden', 46, 2),
(206, 'CH', 'switzerland', 41, 2),
(207, 'SY', 'syrian arab republic', 963, 2),
(208, 'TW', 'taiwan, province of china', 886, 2),
(209, 'TJ', 'tajikistan', 992, 2),
(210, 'TZ', 'united republic of tanzania', 255, 2),
(211, 'TH', 'thailand', 66, 2),
(212, 'TL', 'timor-leste', 670, 2),
(213, 'TG', 'togo', 228, 2),
(214, 'TK', 'tokelau', 690, 2),
(215, 'TO', 'tonga', 676, 2),
(216, 'TT', 'trinidad and tobago', 1868, 2),
(217, 'TN', 'tunisia', 216, 2),
(218, 'TR', 'turkey', 90, 2),
(219, 'TM', 'turkmenistan', 7370, 2),
(220, 'TC', 'turks and caicos islands', 1649, 2),
(221, 'TV', 'tuvalu', 688, 2),
(222, 'UG', 'uganda', 256, 2),
(223, 'UA', 'ukraine', 380, 2),
(224, 'AE', 'united arab emirates', 971, 2),
(225, 'GB', 'united kingdom', 44, 2),
(226, 'US', 'united states', 1, 2),
(227, 'UM', 'united states minor outlying islands', 1, 2),
(228, 'UY', 'uruguay', 598, 2),
(229, 'UZ', 'uzbekistan', 998, 2),
(230, 'VU', 'vanuatu', 678, 2),
(231, 'VE', 'venezuela', 58, 2),
(232, 'VN', 'viet nam', 84, 2),
(233, 'VG', 'virgin islands, british', 1284, 2),
(234, 'VI', 'virgin islands, u.s.', 1340, 2),
(235, 'WF', 'wallis and futuna', 681, 2),
(236, 'EH', 'western sahara', 212, 2),
(237, 'YE', 'yemen', 967, 2),
(238, 'ZM', 'zambia', 260, 2),
(239, 'ZW', 'zimbabwe', 263, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sys_countries_cities`
--

CREATE TABLE `sys_countries_cities` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(200) DEFAULT NULL,
  `city_name_ar` varchar(200) DEFAULT NULL,
  `city_code` varchar(5) DEFAULT NULL,
  `country_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sys_countries_cities`
--

INSERT INTO `sys_countries_cities` (`city_id`, `city_name`, `city_name_ar`, `city_code`, `country_id`) VALUES
(1, 'Abu Dhabi', 'ابو ظبي', 'AD', 224),
(2, 'Dubai', 'دبي', 'DXB', 224),
(3, 'Sharjah', 'الشارقة', 'SHJ', 224),
(4, 'Ajman', 'عجمان', 'AJM', 224),
(5, 'Ras Alkhaymah', 'راس الخيمة', 'RAK', 224),
(6, 'Umm Al quween', 'ام القيوين', 'UQM', 224),
(7, 'Fujairah', 'الفجيرة', 'FUJ', 224);

-- --------------------------------------------------------

--
-- Table structure for table `sys_countries_cities_areas`
--

CREATE TABLE `sys_countries_cities_areas` (
  `area_id` int(11) NOT NULL,
  `area_name` varchar(200) NOT NULL,
  `area_name_ar` varchar(250) NOT NULL,
  `city_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sys_countries_cities_areas`
--

INSERT INTO `sys_countries_cities_areas` (`area_id`, `area_name`, `area_name_ar`, `city_id`) VALUES
(1, 'Tourist club', 'Tourist club', 1),
(2, 'Al mushrif', 'Al mushrif', 1),
(3, 'AL RAHA BEACH ', 'AL RAHA BEACH ', 1),
(8, 'Khalifa A city ', 'Khalifa A city ', 1),
(9, 'Mohamed Bin Zayed ( MBZ ) ', 'Mohamed Bin Zayed ( MBZ ) ', 1),
(10, 'Shakhabout City ', 'Shakhabout City ', 1),
(11, 'Bani Yas City ', 'Bani Yas City ', 1),
(12, 'Al Riyad ', 'Al Riyad ', 1),
(13, 'Al Shamikhah ', 'Al Shamikhah ', 1),
(14, 'Saadiyat Island ', 'Saadiyat Island ', 1),
(29, 'Yas Island ', 'Yas Island ', 1),
(43, 'Al Reem Island', '', 1),
(44, 'Al Reef', '', 1),
(45, 'Hydra Village', '', 1),
(46, 'Al Gadeer', '', 1),
(47, 'Salam Street', '', 1),
(48, 'Ghantout', '', 1),
(49, 'Corniche Area', '', 1),
(50, 'Al Khaleej Al Arabi street', '', 1),
(51, 'al bateen', '', 1),
(52, 'ALKarama', '', 1),
(53, 'Mag 5', '', 1),
(54, 'Zayed City', '', 1),
(55, 'Masdar City', 'Masdar City', 1),
(56, 'al raha gardens', 'al raha gardens', 1),
(58, 'Al Salam Street - Bloom Faya', 'Al Salam Street - Bloom Faya', 1),
(59, 'Zayed City', 'Zayed City', 1),
(60, 'Muzrea Community', 'Muzrea Community', 1),
(61, 'Redient', 'Redient', 1),
(62, 'faya bloom gardens', 'faya bloom gardens', 1),
(63, 'Not Used', '', 1),
(64, 'Shams Reflection - Al Reem Island', 'Shams Reflection - Al Reem Island', 1),
(65, 'al manhal', '', 1),
(66, 'Al Manhal', 'Al Manhal', 1),
(67, 'al hebiah second', 'al hebiah second', 2),
(68, 'Rabdan city', 'Rabdan city', 1),
(69, 'MAYYAS', 'MAYYAS', 1),
(70, 'Al Bahyah old Shahama', 'Al Bahyah old Shahama', 1),
(71, 'Yas golf collection', 'Yas golf collection', 1),
(72, 'Al Khalidiya', 'Al Khalidiya', 1),
(73, 'Bin Jasreen', 'Bin Jasreen', 1),
(74, 'al bateen parks', 'al bateen parks', 1),
(75, 'golf gardens', 'golf gardens', 1),
(76, 'Al Jubail', 'Al Jubail', 1),
(77, 'REDWOODS YAS ISLAND', 'REDWOODS YAS ISLAND', 1),
(78, 'al nahyan', 'al nahyan', 1),
(79, 'oasis masdar 2', 'oasis masdar 2', 1),
(80, 'Marina', 'Marina', 1),
(81, 'rabdan', 'rabdan', 1),
(82, 'Yas Island', 'Yas Island', 1),
(83, 'Mangrove place', 'Mangrove place', 1),
(84, 'Al Raha', 'Al Raha', 1),
(85, 'Al Zeina', 'Al Zeina', 1),
(86, 'alsaadah', 'alsaadah', 1),
(87, 'Masdar - Plaza', 'Masdar - Plaza', 1),
(88, 'LIWA', 'LIWA', 1),
(89, 'alrahba', 'alrahba', 1),
(90, 'Al murur', 'Al murur', 1),
(91, 'corniche', 'corniche', 1),
(92, 'AL RAHA', 'AL RAHA', 1),
(93, 'Khalidiya', 'Khalidiya', 1),
(94, 'Corniche', 'Corniche', 1),
(95, 'the bay residence', 'the bay residence', 1),
(96, 'Al Murjan Island', 'Al Murjan Island', 5),
(97, 'Madinat Zayed', 'Madinat Zayed', 1),
(98, 'Khalifa City', 'Khalifa City', 1),
(99, 'Khalifa City A', 'Khalifa City A', 1),
(100, 'West Yas', 'West Yas', 1),
(101, 'Noya Luma', 'Noya Luma', 1),
(102, 'al reem', 'al reem', 1),
(103, 'shakbout city', 'shakbout city', 1),
(104, 'Al Reeman2', 'Al Reeman2', 1),
(105, 'art house', 'art house', 1),
(106, 'bloom garden', 'bloom garden', 1),
(107, 'Al Rahayal', 'Al Rahayal', 1),
(108, 'Al Rahba', 'Al Rahba', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sys_errors`
--

CREATE TABLE `sys_errors` (
  `error_id` int(11) NOT NULL,
  `error_text` text DEFAULT NULL,
  `added_date` datetime DEFAULT NULL,
  `adder_type` varchar(255) NOT NULL,
  `added_by` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sys_lists`
--

CREATE TABLE `sys_lists` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(200) DEFAULT NULL,
  `item_category` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sys_lists`
--

INSERT INTO `sys_lists` (`item_id`, `item_name`, `item_category`) VALUES
(1, 'Male', 'gender'),
(2, 'Female', 'gender'),
(3, 'Mr.', 'title'),
(4, 'Mrs.', 'title'),
(5, 'Ms.', 'title'),
(6, 'Prof.', 'title'),
(7, 'Dr.', 'title');

-- --------------------------------------------------------

--
-- Table structure for table `sys_lists_colors`
--

CREATE TABLE `sys_lists_colors` (
  `color_id` int(11) NOT NULL,
  `color_value` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sys_lists_colors`
--

INSERT INTO `sys_lists_colors` (`color_id`, `color_value`) VALUES
(1, '#2bbbad'),
(2, '#0099cc'),
(3, '#00c851'),
(4, '#ff8800'),
(5, '#cc0000'),
(6, '#000000'),
(7, '#ffa500'),
(8, '#ff0000'),
(9, '#0000ff'),
(10, '#ffff00');

-- --------------------------------------------------------

--
-- Table structure for table `sys_list_priorities`
--

CREATE TABLE `sys_list_priorities` (
  `priority_id` int(11) NOT NULL,
  `priority_name` text DEFAULT NULL,
  `priority_name_ar` text DEFAULT NULL,
  `priority_color` varchar(10) NOT NULL DEFAULT '000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sys_list_priorities`
--

INSERT INTO `sys_list_priorities` (`priority_id`, `priority_name`, `priority_name_ar`, `priority_color`) VALUES
(1, 'Low', 'Low', '55DF16'),
(2, 'Meduim', 'Meduim', 'ff7d08'),
(3, 'High', 'High', 'ef3d46');

-- --------------------------------------------------------

--
-- Table structure for table `sys_list_status`
--

CREATE TABLE `sys_list_status` (
  `status_id` int(11) NOT NULL,
  `status_name` text DEFAULT NULL,
  `status_name_ar` text DEFAULT NULL,
  `status_color` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sys_list_status`
--

INSERT INTO `sys_list_status` (`status_id`, `status_name`, `status_name_ar`, `status_color`) VALUES
(1, 'To Do', 'To Do', '0A8A91'),
(2, 'In Progress', 'Progress', 'FF7E0C'),
(3, 'Done', 'Done', '50D24D'),
(4, 'Canceled', 'Canceled', 'C11525');

-- --------------------------------------------------------

--
-- Table structure for table `sys_logs`
--

CREATE TABLE `sys_logs` (
  `log_id` int(11) NOT NULL,
  `related_table` varchar(100) NOT NULL,
  `related_id` int(11) NOT NULL,
  `log_date` datetime NOT NULL,
  `log_action` varchar(200) NOT NULL,
  `log_remark` text DEFAULT NULL,
  `logger_type` varchar(100) NOT NULL,
  `logged_by` int(11) NOT NULL,
  `log_type` varchar(3) DEFAULT NULL COMMENT 'int, ext'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sys_settings`
--

CREATE TABLE `sys_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_name` varchar(100) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sys_settings`
--

INSERT INTO `sys_settings` (`setting_id`, `setting_name`, `setting_value`) VALUES
(1, 'Notify on new ticket', '1');

-- --------------------------------------------------------

--
-- Table structure for table `sys_timezones_list`
--

CREATE TABLE `sys_timezones_list` (
  `timezone_id` int(11) NOT NULL,
  `tmiezone_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sys_timezones_list`
--

INSERT INTO `sys_timezones_list` (`timezone_id`, `tmiezone_name`) VALUES
(1, 'Africa/Abidjan'),
(2, 'Africa/Accra'),
(3, 'Africa/Addis_Ababa'),
(4, 'Africa/Algiers'),
(5, 'Africa/Asmara'),
(6, 'Africa/Asmera'),
(7, 'Africa/Bamako'),
(8, 'Africa/Bangui'),
(9, 'Africa/Banjul'),
(10, 'Africa/Bissau'),
(11, 'Africa/Blantyre'),
(12, 'Africa/Brazzaville'),
(13, 'Africa/Bujumbura'),
(14, 'Africa/Cairo'),
(15, 'Africa/Casablanca'),
(16, 'Africa/Ceuta'),
(17, 'Africa/Conakry'),
(18, 'Africa/Dakar'),
(19, 'Africa/Dar_es_Salaam'),
(20, 'Africa/Djibouti'),
(21, 'Africa/Douala'),
(22, 'Africa/El_Aaiun'),
(23, 'Africa/Freetown'),
(24, 'Africa/Gaborone'),
(25, 'Africa/Harare'),
(26, 'Africa/Johannesburg'),
(27, 'Africa/Juba'),
(28, 'Africa/Kampala'),
(29, 'Africa/Khartoum'),
(30, 'Africa/Kigali'),
(31, 'Africa/Kinshasa'),
(32, 'Africa/Lagos'),
(33, 'Africa/Libreville'),
(34, 'Africa/Lome'),
(35, 'Africa/Luanda'),
(36, 'Africa/Lubumbashi'),
(37, 'Africa/Lusaka'),
(38, 'Africa/Malabo'),
(39, 'Africa/Maputo'),
(40, 'Africa/Maseru'),
(41, 'Africa/Mbabane'),
(42, 'Africa/Mogadishu'),
(43, 'Africa/Monrovia'),
(44, 'Africa/Nairobi'),
(45, 'Africa/Ndjamena'),
(46, 'Africa/Niamey'),
(47, 'Africa/Nouakchott'),
(48, 'Africa/Ouagadougou'),
(49, 'Africa/Porto-Novo'),
(50, 'Africa/Sao_Tome'),
(51, 'Africa/Timbuktu'),
(52, 'Africa/Tripoli'),
(53, 'Africa/Tunis'),
(54, 'Africa/Windhoek'),
(55, 'America/Adak'),
(56, 'America/Anchorage'),
(57, 'America/Anguilla'),
(58, 'America/Antigua'),
(59, 'America/Araguaina'),
(60, 'America/Argentina/Buenos_Aires'),
(61, 'America/Argentina/Catamarca'),
(62, 'America/Argentina/ComodRivadavia'),
(63, 'America/Argentina/Cordoba'),
(64, 'America/Argentina/Jujuy'),
(65, 'America/Argentina/La_Rioja'),
(66, 'America/Argentina/Mendoza'),
(67, 'America/Argentina/Rio_Gallegos'),
(68, 'America/Argentina/Salta'),
(69, 'America/Argentina/San_Juan'),
(70, 'America/Argentina/San_Luis'),
(71, 'America/Argentina/Tucuman'),
(72, 'America/Argentina/Ushuaia'),
(73, 'America/Aruba'),
(74, 'America/Asuncion'),
(75, 'America/Atikokan'),
(76, 'America/Atka'),
(77, 'America/Bahia'),
(78, 'America/Bahia_Banderas'),
(79, 'America/Barbados'),
(80, 'America/Belem'),
(81, 'America/Belize'),
(82, 'America/Blanc-Sablon'),
(83, 'America/Boa_Vista'),
(84, 'America/Bogota'),
(85, 'America/Boise'),
(86, 'America/Buenos_Aires'),
(87, 'America/Cambridge_Bay'),
(88, 'America/Campo_Grande'),
(89, 'America/Cancun'),
(90, 'America/Caracas'),
(91, 'America/Catamarca'),
(92, 'America/Cayenne'),
(93, 'America/Cayman'),
(94, 'America/Chicago'),
(95, 'America/Chihuahua'),
(96, 'America/Ciudad_Juarez'),
(97, 'America/Coral_Harbour'),
(98, 'America/Cordoba'),
(99, 'America/Costa_Rica'),
(100, 'America/Creston'),
(101, 'America/Cuiaba'),
(102, 'America/Curacao'),
(103, 'America/Danmarkshavn'),
(104, 'America/Dawson'),
(105, 'America/Dawson_Creek'),
(106, 'America/Denver'),
(107, 'America/Detroit'),
(108, 'America/Dominica'),
(109, 'America/Edmonton'),
(110, 'America/Eirunepe'),
(111, 'America/El_Salvador'),
(112, 'America/Ensenada'),
(113, 'America/Fort_Nelson'),
(114, 'America/Fort_Wayne'),
(115, 'America/Fortaleza'),
(116, 'America/Glace_Bay'),
(117, 'America/Godthab'),
(118, 'America/Goose_Bay'),
(119, 'America/Grand_Turk'),
(120, 'America/Grenada'),
(121, 'America/Guadeloupe'),
(122, 'America/Guatemala'),
(123, 'America/Guayaquil'),
(124, 'America/Guyana'),
(125, 'America/Halifax'),
(126, 'America/Havana'),
(127, 'America/Hermosillo'),
(128, 'America/Indiana/Indianapolis'),
(129, 'America/Indiana/Knox'),
(130, 'America/Indiana/Marengo'),
(131, 'America/Indiana/Petersburg'),
(132, 'America/Indiana/Tell_City'),
(133, 'America/Indiana/Vevay'),
(134, 'America/Indiana/Vincennes'),
(135, 'America/Indiana/Winamac'),
(136, 'America/Indianapolis'),
(137, 'America/Inuvik'),
(138, 'America/Iqaluit'),
(139, 'America/Jamaica'),
(140, 'America/Jujuy'),
(141, 'America/Juneau'),
(142, 'America/Kentucky/Louisville'),
(143, 'America/Kentucky/Monticello'),
(144, 'America/Knox_IN'),
(145, 'America/Kralendijk'),
(146, 'America/La_Paz'),
(147, 'America/Lima'),
(148, 'America/Los_Angeles'),
(149, 'America/Louisville'),
(150, 'America/Lower_Princes'),
(151, 'America/Maceio'),
(152, 'America/Managua'),
(153, 'America/Manaus'),
(154, 'America/Marigot'),
(155, 'America/Martinique'),
(156, 'America/Matamoros'),
(157, 'America/Mazatlan'),
(158, 'America/Mendoza'),
(159, 'America/Menominee'),
(160, 'America/Merida'),
(161, 'America/Metlakatla'),
(162, 'America/Mexico_City'),
(163, 'America/Miquelon'),
(164, 'America/Moncton'),
(165, 'America/Monterrey'),
(166, 'America/Montevideo'),
(167, 'America/Montreal'),
(168, 'America/Montserrat'),
(169, 'America/Nassau'),
(170, 'America/New_York'),
(171, 'America/Nipigon'),
(172, 'America/Nome'),
(173, 'America/Noronha'),
(174, 'America/North_Dakota/Beulah'),
(175, 'America/North_Dakota/Center'),
(176, 'America/North_Dakota/New_Salem'),
(177, 'America/Nuuk'),
(178, 'America/Ojinaga'),
(179, 'America/Panama'),
(180, 'America/Pangnirtung'),
(181, 'America/Paramaribo'),
(182, 'America/Phoenix'),
(183, 'America/Port-au-Prince'),
(184, 'America/Port_of_Spain'),
(185, 'America/Porto_Acre'),
(186, 'America/Porto_Velho'),
(187, 'America/Puerto_Rico'),
(188, 'America/Punta_Arenas'),
(189, 'America/Rainy_River'),
(190, 'America/Rankin_Inlet'),
(191, 'America/Recife'),
(192, 'America/Regina'),
(193, 'America/Resolute'),
(194, 'America/Rio_Branco'),
(195, 'America/Rosario'),
(196, 'America/Santa_Isabel'),
(197, 'America/Santarem'),
(198, 'America/Santiago'),
(199, 'America/Santo_Domingo'),
(200, 'America/Sao_Paulo'),
(201, 'America/Scoresbysund'),
(202, 'America/Shiprock'),
(203, 'America/Sitka'),
(204, 'America/St_Barthelemy'),
(205, 'America/St_Johns'),
(206, 'America/St_Kitts'),
(207, 'America/St_Lucia'),
(208, 'America/St_Thomas'),
(209, 'America/St_Vincent'),
(210, 'America/Swift_Current'),
(211, 'America/Tegucigalpa'),
(212, 'America/Thule'),
(213, 'America/Thunder_Bay'),
(214, 'America/Tijuana'),
(215, 'America/Toronto'),
(216, 'America/Tortola'),
(217, 'America/Vancouver'),
(218, 'America/Virgin'),
(219, 'America/Whitehorse'),
(220, 'America/Winnipeg'),
(221, 'America/Yakutat'),
(222, 'America/Yellowknife'),
(223, 'Antarctica/Casey'),
(224, 'Antarctica/Davis'),
(225, 'Antarctica/DumontDUrville'),
(226, 'Antarctica/Macquarie'),
(227, 'Antarctica/Mawson'),
(228, 'Antarctica/McMurdo'),
(229, 'Antarctica/Palmer'),
(230, 'Antarctica/Rothera'),
(231, 'Antarctica/South_Pole'),
(232, 'Antarctica/Syowa'),
(233, 'Antarctica/Troll'),
(234, 'Antarctica/Vostok'),
(235, 'Arctic/Longyearbyen'),
(236, 'Asia/Aden'),
(237, 'Asia/Almaty'),
(238, 'Asia/Amman'),
(239, 'Asia/Anadyr'),
(240, 'Asia/Aqtau'),
(241, 'Asia/Aqtobe'),
(242, 'Asia/Ashgabat'),
(243, 'Asia/Ashkhabad'),
(244, 'Asia/Atyrau'),
(245, 'Asia/Baghdad'),
(246, 'Asia/Bahrain'),
(247, 'Asia/Baku'),
(248, 'Asia/Bangkok'),
(249, 'Asia/Barnaul'),
(250, 'Asia/Beirut'),
(251, 'Asia/Bishkek'),
(252, 'Asia/Brunei'),
(253, 'Asia/Calcutta'),
(254, 'Asia/Chita'),
(255, 'Asia/Choibalsan'),
(256, 'Asia/Chongqing'),
(257, 'Asia/Chungking'),
(258, 'Asia/Colombo'),
(259, 'Asia/Dacca'),
(260, 'Asia/Damascus'),
(261, 'Asia/Dhaka'),
(262, 'Asia/Dili'),
(263, 'Asia/Dubai'),
(264, 'Asia/Dushanbe'),
(265, 'Asia/Famagusta'),
(266, 'Asia/Gaza'),
(267, 'Asia/Harbin'),
(268, 'Asia/Hebron'),
(269, 'Asia/Ho_Chi_Minh'),
(270, 'Asia/Hong_Kong'),
(271, 'Asia/Hovd'),
(272, 'Asia/Irkutsk'),
(273, 'Asia/Istanbul'),
(274, 'Asia/Jakarta'),
(275, 'Asia/Jayapura'),
(276, 'Asia/Jerusalem'),
(277, 'Asia/Kabul'),
(278, 'Asia/Kamchatka'),
(279, 'Asia/Karachi'),
(280, 'Asia/Kashgar'),
(281, 'Asia/Kathmandu'),
(282, 'Asia/Katmandu'),
(283, 'Asia/Khandyga'),
(284, 'Asia/Kolkata'),
(285, 'Asia/Krasnoyarsk'),
(286, 'Asia/Kuala_Lumpur'),
(287, 'Asia/Kuching'),
(288, 'Asia/Kuwait'),
(289, 'Asia/Macao'),
(290, 'Asia/Macau'),
(291, 'Asia/Magadan'),
(292, 'Asia/Makassar'),
(293, 'Asia/Manila'),
(294, 'Asia/Muscat'),
(295, 'Asia/Nicosia'),
(296, 'Asia/Novokuznetsk'),
(297, 'Asia/Novosibirsk'),
(298, 'Asia/Omsk'),
(299, 'Asia/Oral'),
(300, 'Asia/Phnom_Penh'),
(301, 'Asia/Pontianak'),
(302, 'Asia/Pyongyang'),
(303, 'Asia/Qatar'),
(304, 'Asia/Qostanay'),
(305, 'Asia/Qyzylorda'),
(306, 'Asia/Rangoon'),
(307, 'Asia/Riyadh'),
(308, 'Asia/Saigon'),
(309, 'Asia/Sakhalin'),
(310, 'Asia/Samarkand'),
(311, 'Asia/Seoul'),
(312, 'Asia/Shanghai'),
(313, 'Asia/Singapore'),
(314, 'Asia/Srednekolymsk'),
(315, 'Asia/Taipei'),
(316, 'Asia/Tashkent'),
(317, 'Asia/Tbilisi'),
(318, 'Asia/Tehran'),
(319, 'Asia/Tel_Aviv'),
(320, 'Asia/Thimbu'),
(321, 'Asia/Thimphu'),
(322, 'Asia/Tokyo'),
(323, 'Asia/Tomsk'),
(324, 'Asia/Ujung_Pandang'),
(325, 'Asia/Ulaanbaatar'),
(326, 'Asia/Ulan_Bator'),
(327, 'Asia/Urumqi'),
(328, 'Asia/Ust-Nera'),
(329, 'Asia/Vientiane'),
(330, 'Asia/Vladivostok'),
(331, 'Asia/Yakutsk'),
(332, 'Asia/Yangon'),
(333, 'Asia/Yekaterinburg'),
(334, 'Asia/Yerevan'),
(335, 'Atlantic/Azores'),
(336, 'Atlantic/Bermuda'),
(337, 'Atlantic/Canary'),
(338, 'Atlantic/Cape_Verde'),
(339, 'Atlantic/Faeroe'),
(340, 'Atlantic/Faroe'),
(341, 'Atlantic/Jan_Mayen'),
(342, 'Atlantic/Madeira'),
(343, 'Atlantic/Reykjavik'),
(344, 'Atlantic/South_Georgia'),
(345, 'Atlantic/St_Helena'),
(346, 'Atlantic/Stanley'),
(347, 'Australia/ACT'),
(348, 'Australia/Adelaide'),
(349, 'Australia/Brisbane'),
(350, 'Australia/Broken_Hill'),
(351, 'Australia/Canberra'),
(352, 'Australia/Currie'),
(353, 'Australia/Darwin'),
(354, 'Australia/Eucla'),
(355, 'Australia/Hobart'),
(356, 'Australia/LHI'),
(357, 'Australia/Lindeman'),
(358, 'Australia/Lord_Howe'),
(359, 'Australia/Melbourne'),
(360, 'Australia/North'),
(361, 'Australia/NSW'),
(362, 'Australia/Perth'),
(363, 'Australia/Queensland'),
(364, 'Australia/South'),
(365, 'Australia/Sydney'),
(366, 'Australia/Tasmania'),
(367, 'Australia/Victoria'),
(368, 'Australia/West'),
(369, 'Australia/Yancowinna'),
(370, 'Brazil/Acre'),
(371, 'Brazil/DeNoronha'),
(372, 'Brazil/East'),
(373, 'Brazil/West'),
(374, 'Canada/Atlantic'),
(375, 'Canada/Central'),
(376, 'Canada/Eastern'),
(377, 'Canada/Mountain'),
(378, 'Canada/Newfoundland'),
(379, 'Canada/Pacific'),
(380, 'Canada/Saskatchewan'),
(381, 'Canada/Yukon'),
(382, 'CET'),
(383, 'Chile/Continental'),
(384, 'Chile/EasterIsland'),
(385, 'CST6CDT'),
(386, 'Cuba'),
(387, 'EET'),
(388, 'Egypt'),
(389, 'Eire'),
(390, 'EST'),
(391, 'EST5EDT'),
(392, 'Etc/GMT'),
(393, 'Etc/GMT+0'),
(394, 'Etc/GMT+1'),
(395, 'Etc/GMT+10'),
(396, 'Etc/GMT+11'),
(397, 'Etc/GMT+12'),
(398, 'Etc/GMT+2'),
(399, 'Etc/GMT+3'),
(400, 'Etc/GMT+4'),
(401, 'Etc/GMT+5'),
(402, 'Etc/GMT+6'),
(403, 'Etc/GMT+7'),
(404, 'Etc/GMT+8'),
(405, 'Etc/GMT+9'),
(406, 'Etc/GMT-0'),
(407, 'Etc/GMT-1'),
(408, 'Etc/GMT-10'),
(409, 'Etc/GMT-11'),
(410, 'Etc/GMT-12'),
(411, 'Etc/GMT-13'),
(412, 'Etc/GMT-14'),
(413, 'Etc/GMT-2'),
(414, 'Etc/GMT-3'),
(415, 'Etc/GMT-4'),
(416, 'Etc/GMT-5'),
(417, 'Etc/GMT-6'),
(418, 'Etc/GMT-7'),
(419, 'Etc/GMT-8'),
(420, 'Etc/GMT-9'),
(421, 'Etc/GMT0'),
(422, 'Etc/Greenwich'),
(423, 'Etc/UCT'),
(424, 'Etc/Universal'),
(425, 'Etc/UTC'),
(426, 'Etc/Zulu'),
(427, 'Europe/Amsterdam'),
(428, 'Europe/Andorra'),
(429, 'Europe/Astrakhan'),
(430, 'Europe/Athens'),
(431, 'Europe/Belfast'),
(432, 'Europe/Belgrade'),
(433, 'Europe/Berlin'),
(434, 'Europe/Bratislava'),
(435, 'Europe/Brussels'),
(436, 'Europe/Bucharest'),
(437, 'Europe/Budapest'),
(438, 'Europe/Busingen'),
(439, 'Europe/Chisinau'),
(440, 'Europe/Copenhagen'),
(441, 'Europe/Dublin'),
(442, 'Europe/Gibraltar'),
(443, 'Europe/Guernsey'),
(444, 'Europe/Helsinki'),
(445, 'Europe/Isle_of_Man'),
(446, 'Europe/Istanbul'),
(447, 'Europe/Jersey'),
(448, 'Europe/Kaliningrad'),
(449, 'Europe/Kiev'),
(450, 'Europe/Kirov'),
(451, 'Europe/Kyiv'),
(452, 'Europe/Lisbon'),
(453, 'Europe/Ljubljana'),
(454, 'Europe/London'),
(455, 'Europe/Luxembourg'),
(456, 'Europe/Madrid'),
(457, 'Europe/Malta'),
(458, 'Europe/Mariehamn'),
(459, 'Europe/Minsk'),
(460, 'Europe/Monaco'),
(461, 'Europe/Moscow'),
(462, 'Europe/Nicosia'),
(463, 'Europe/Oslo'),
(464, 'Europe/Paris'),
(465, 'Europe/Podgorica'),
(466, 'Europe/Prague'),
(467, 'Europe/Riga'),
(468, 'Europe/Rome'),
(469, 'Europe/Samara'),
(470, 'Europe/San_Marino'),
(471, 'Europe/Sarajevo'),
(472, 'Europe/Saratov'),
(473, 'Europe/Simferopol'),
(474, 'Europe/Skopje'),
(475, 'Europe/Sofia'),
(476, 'Europe/Stockholm'),
(477, 'Europe/Tallinn'),
(478, 'Europe/Tirane'),
(479, 'Europe/Tiraspol'),
(480, 'Europe/Ulyanovsk'),
(481, 'Europe/Uzhgorod'),
(482, 'Europe/Vaduz'),
(483, 'Europe/Vatican'),
(484, 'Europe/Vienna'),
(485, 'Europe/Vilnius'),
(486, 'Europe/Volgograd'),
(487, 'Europe/Warsaw'),
(488, 'Europe/Zagreb'),
(489, 'Europe/Zaporozhye'),
(490, 'Europe/Zurich'),
(491, 'Factory'),
(492, 'GB'),
(493, 'GB-Eire'),
(494, 'GMT'),
(495, 'GMT+0'),
(496, 'GMT-0'),
(497, 'GMT0'),
(498, 'Greenwich'),
(499, 'Hongkong'),
(500, 'HST'),
(501, 'Iceland'),
(502, 'Indian/Antananarivo'),
(503, 'Indian/Chagos'),
(504, 'Indian/Christmas'),
(505, 'Indian/Cocos'),
(506, 'Indian/Comoro'),
(507, 'Indian/Kerguelen'),
(508, 'Indian/Mahe'),
(509, 'Indian/Maldives'),
(510, 'Indian/Mauritius'),
(511, 'Indian/Mayotte'),
(512, 'Indian/Reunion'),
(513, 'Iran'),
(514, 'Israel'),
(515, 'Jamaica'),
(516, 'Japan'),
(517, 'Kwajalein'),
(518, 'Libya'),
(519, 'MET'),
(520, 'Mexico/BajaNorte'),
(521, 'Mexico/BajaSur'),
(522, 'Mexico/General'),
(523, 'MST'),
(524, 'MST7MDT'),
(525, 'Navajo'),
(526, 'NZ'),
(527, 'NZ-CHAT'),
(528, 'Pacific/Apia'),
(529, 'Pacific/Auckland'),
(530, 'Pacific/Bougainville'),
(531, 'Pacific/Chatham'),
(532, 'Pacific/Chuuk'),
(533, 'Pacific/Easter'),
(534, 'Pacific/Efate'),
(535, 'Pacific/Enderbury'),
(536, 'Pacific/Fakaofo'),
(537, 'Pacific/Fiji'),
(538, 'Pacific/Funafuti'),
(539, 'Pacific/Galapagos'),
(540, 'Pacific/Gambier'),
(541, 'Pacific/Guadalcanal'),
(542, 'Pacific/Guam'),
(543, 'Pacific/Honolulu'),
(544, 'Pacific/Johnston'),
(545, 'Pacific/Kanton'),
(546, 'Pacific/Kiritimati'),
(547, 'Pacific/Kosrae'),
(548, 'Pacific/Kwajalein'),
(549, 'Pacific/Majuro'),
(550, 'Pacific/Marquesas'),
(551, 'Pacific/Midway'),
(552, 'Pacific/Nauru'),
(553, 'Pacific/Niue'),
(554, 'Pacific/Norfolk'),
(555, 'Pacific/Noumea'),
(556, 'Pacific/Pago_Pago'),
(557, 'Pacific/Palau'),
(558, 'Pacific/Pitcairn'),
(559, 'Pacific/Pohnpei'),
(560, 'Pacific/Ponape'),
(561, 'Pacific/Port_Moresby'),
(562, 'Pacific/Rarotonga'),
(563, 'Pacific/Saipan'),
(564, 'Pacific/Samoa'),
(565, 'Pacific/Tahiti'),
(566, 'Pacific/Tarawa'),
(567, 'Pacific/Tongatapu'),
(568, 'Pacific/Truk'),
(569, 'Pacific/Wake'),
(570, 'Pacific/Wallis'),
(571, 'Pacific/Yap'),
(572, 'Poland'),
(573, 'Portugal'),
(574, 'PRC'),
(575, 'PST8PDT'),
(576, 'ROC'),
(577, 'ROK'),
(578, 'Singapore'),
(579, 'Turkey'),
(580, 'UCT'),
(581, 'Universal'),
(582, 'US/Alaska'),
(583, 'US/Aleutian'),
(584, 'US/Arizona'),
(585, 'US/Central'),
(586, 'US/East-Indiana'),
(587, 'US/Eastern'),
(588, 'US/Hawaii'),
(589, 'US/Indiana-Starke'),
(590, 'US/Michigan'),
(591, 'US/Mountain'),
(592, 'US/Pacific'),
(593, 'US/Samoa'),
(594, 'UTC'),
(595, 'W-SU'),
(596, 'WET'),
(597, 'Zulu');

-- --------------------------------------------------------

--
-- Table structure for table `tasks_list`
--

CREATE TABLE `tasks_list` (
  `task_id` int(11) NOT NULL,
  `task_title` text DEFAULT NULL,
  `task_description` text DEFAULT NULL,
  `task_progress` int(3) NOT NULL DEFAULT 0,
  `status_id` int(11) NOT NULL DEFAULT 1,
  `priority_id` int(11) NOT NULL DEFAULT 1,
  `task_assigned_date` datetime NOT NULL,
  `task_due_date` datetime NOT NULL,
  `task_end_date` datetime DEFAULT NULL,
  `assigned_to` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `strategic_plan_id` int(11) NOT NULL DEFAULT 0,
  `local_map_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_list`
--

CREATE TABLE `users_list` (
  `record_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_email` varchar(200) DEFAULT NULL,
  `user_type` varchar(20) NOT NULL COMMENT 'root, atp, iqc',
  `int_ext` varchar(3) DEFAULT NULL,
  `user_family` varchar(30) DEFAULT NULL,
  `is_active` int(1) NOT NULL DEFAULT 0,
  `user_lang` varchar(2) NOT NULL DEFAULT 'en',
  `user_theme_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users_list`
--

INSERT INTO `users_list` (`record_id`, `user_id`, `user_email`, `user_type`, `int_ext`, `user_family`, `is_active`, `user_lang`, `user_theme_id`) VALUES
(54, 1, 'root', 'root', 'int', 'employees_list', 1, 'en', 1),
(61, 34, 'emp', 'emp', 'int', 'employees_list', 1, 'en', 1),
(62, 35, 'hrhr', 'hr', 'int', 'employees_list', 1, 'en', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_list_themes`
--

CREATE TABLE `users_list_themes` (
  `user_theme_id` int(11) NOT NULL,
  `color_primary` varchar(10) NOT NULL,
  `color_on_primary` varchar(10) NOT NULL,
  `color_secondary` varchar(10) NOT NULL,
  `color_on_secondary` varchar(10) NOT NULL,
  `color_third` varchar(10) NOT NULL,
  `color_on_third` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_list_themes`
--

INSERT INTO `users_list_themes` (`user_theme_id`, `color_primary`, `color_on_primary`, `color_secondary`, `color_on_secondary`, `color_third`, `color_on_third`) VALUES
(1, '243649', 'FFF', '4e7499', 'FFF', 'c5e2f4', '000'),
(2, '605f48', 'FFF', 'c0b9a1', 'FFF', 'b7b19e', '000'),
(3, '68593f', 'FFF', 'a08b70', 'FFF', '8c662f', 'FFF'),
(4, '51130f', 'FFF', '822f2f', 'FFF', 'bc3131', 'FFF'),
(5, '003a2c', 'FFF', '008260', 'FFF', '77a095', 'FFF'),
(6, '5b342a', 'FFF', 'ad7445', 'FFF', 'bc6c31', 'FFF');

-- --------------------------------------------------------

--
-- Table structure for table `z_assets_list`
--

CREATE TABLE `z_assets_list` (
  `asset_id` int(11) NOT NULL,
  `asset_ref` varchar(100) NOT NULL,
  `asset_name` varchar(255) NOT NULL,
  `asset_description` text DEFAULT NULL,
  `asset_sku` varchar(50) NOT NULL,
  `asset_serial` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `assigned_date` datetime NOT NULL,
  `added_date` datetime NOT NULL,
  `added_by` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `purchase_date` date DEFAULT NULL,
  `expiry_date` date NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `z_assets_list_assign`
--

CREATE TABLE `z_assets_list_assign` (
  `record_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `assigned_date` datetime NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `assign_remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `z_assets_list_cats`
--

CREATE TABLE `z_assets_list_cats` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_name_ar` varchar(255) DEFAULT NULL,
  `category_color` varchar(10) DEFAULT NULL,
  `category_alert_days` int(5) NOT NULL DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `z_assets_list_cats`
--

INSERT INTO `z_assets_list_cats` (`category_id`, `category_name`, `category_name_ar`, `category_color`, `category_alert_days`) VALUES
(1, 'Main PCs', 'Main PCs', '#000000', 30),
(2, 'Hardware', 'Hardware', '#000000', 30),
(3, 'Networking', 'Networking', '#000000', 30),
(5, 'Input Devices', NULL, NULL, 90);

-- --------------------------------------------------------

--
-- Table structure for table `z_assets_list_status`
--

CREATE TABLE `z_assets_list_status` (
  `status_id` int(11) NOT NULL,
  `status_name` text DEFAULT NULL,
  `status_name_ar` text DEFAULT NULL,
  `status_color` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `z_assets_list_status`
--

INSERT INTO `z_assets_list_status` (`status_id`, `status_name`, `status_name_ar`, `status_color`) VALUES
(1, 'In Stock', 'In Stock', 'FF0000'),
(2, 'In Use', 'In Use', 'F2F224'),
(3, 'Retired', 'Retired', '29F925');

-- --------------------------------------------------------

--
-- Table structure for table `z_groups_list`
--

CREATE TABLE `z_groups_list` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(50) NOT NULL,
  `group_desc` text DEFAULT NULL,
  `group_color_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `is_commity` int(1) NOT NULL DEFAULT 0,
  `is_deleted` int(1) NOT NULL DEFAULT 0,
  `is_archieve` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `z_groups_list_files`
--

CREATE TABLE `z_groups_list_files` (
  `file_id` int(11) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `file_path` varchar(100) NOT NULL,
  `main_file_id` int(11) DEFAULT NULL,
  `file_version` int(5) NOT NULL DEFAULT 1,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `z_groups_list_members`
--

CREATE TABLE `z_groups_list_members` (
  `record_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `group_role_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `z_groups_list_posts`
--

CREATE TABLE `z_groups_list_posts` (
  `post_id` int(11) NOT NULL,
  `post_text` text DEFAULT NULL,
  `post_type` varchar(20) NOT NULL COMMENT 'text, document, image',
  `post_attachment_id` int(11) NOT NULL DEFAULT 0,
  `group_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `z_groups_list_roles`
--

CREATE TABLE `z_groups_list_roles` (
  `group_role_id` int(11) NOT NULL,
  `group_role_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `z_groups_list_roles`
--

INSERT INTO `z_groups_list_roles` (`group_role_id`, `group_role_name`) VALUES
(1, 'Admin'),
(2, 'Member');

-- --------------------------------------------------------

--
-- Table structure for table `z_messages_list`
--

CREATE TABLE `z_messages_list` (
  `chat_id` int(11) NOT NULL,
  `a_id` int(11) NOT NULL,
  `b_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `is_archieve` int(1) NOT NULL DEFAULT 0,
  `is_deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `z_messages_list_files`
--

CREATE TABLE `z_messages_list_files` (
  `file_id` int(11) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `file_path` varchar(100) NOT NULL,
  `main_file_id` int(11) DEFAULT NULL,
  `file_version` int(5) NOT NULL DEFAULT 1,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `chat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `z_messages_list_posts`
--

CREATE TABLE `z_messages_list_posts` (
  `post_id` int(11) NOT NULL,
  `post_text` text DEFAULT NULL,
  `post_type` varchar(20) NOT NULL COMMENT 'text, document, image',
  `post_attachment_id` int(11) NOT NULL DEFAULT 0,
  `chat_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `is_read` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `atps_electronic_systems`
--
ALTER TABLE `atps_electronic_systems`
  ADD PRIMARY KEY (`platform_id`);

--
-- Indexes for table `atps_form_init`
--
ALTER TABLE `atps_form_init`
  ADD PRIMARY KEY (`form_id`),
  ADD KEY `atp_id` (`atp_id`);

--
-- Indexes for table `atps_info_request`
--
ALTER TABLE `atps_info_request`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `atps_info_request_evs`
--
ALTER TABLE `atps_info_request_evs`
  ADD PRIMARY KEY (`evidence_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `atps_learners_statistics`
--
ALTER TABLE `atps_learners_statistics`
  ADD PRIMARY KEY (`statistic_id`);

--
-- Indexes for table `atps_list`
--
ALTER TABLE `atps_list`
  ADD PRIMARY KEY (`atp_id`);

--
-- Indexes for table `atps_list_categories`
--
ALTER TABLE `atps_list_categories`
  ADD PRIMARY KEY (`atp_category_id`);

--
-- Indexes for table `atps_list_contacts`
--
ALTER TABLE `atps_list_contacts`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `atp_id` (`atp_id`);

--
-- Indexes for table `atps_list_faculties`
--
ALTER TABLE `atps_list_faculties`
  ADD PRIMARY KEY (`faculty_id`);

--
-- Indexes for table `atps_list_faculties_types`
--
ALTER TABLE `atps_list_faculties_types`
  ADD PRIMARY KEY (`faculty_type_id`);

--
-- Indexes for table `atps_list_logs`
--
ALTER TABLE `atps_list_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `atps_list_pass`
--
ALTER TABLE `atps_list_pass`
  ADD PRIMARY KEY (`pass_id`);

--
-- Indexes for table `atps_list_qualifications`
--
ALTER TABLE `atps_list_qualifications`
  ADD PRIMARY KEY (`qualification_id`);

--
-- Indexes for table `atps_list_qualifications_faculties`
--
ALTER TABLE `atps_list_qualifications_faculties`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `atps_list_requests`
--
ALTER TABLE `atps_list_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `atps_list_status`
--
ALTER TABLE `atps_list_status`
  ADD PRIMARY KEY (`atp_status_id`);

--
-- Indexes for table `atps_list_types`
--
ALTER TABLE `atps_list_types`
  ADD PRIMARY KEY (`atp_type_id`);

--
-- Indexes for table `atps_prog_register_req`
--
ALTER TABLE `atps_prog_register_req`
  ADD PRIMARY KEY (`form_id`);

--
-- Indexes for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`token_id`);

--
-- Indexes for table `a_registration_phases`
--
ALTER TABLE `a_registration_phases`
  ADD PRIMARY KEY (`phase_id`);

--
-- Indexes for table `a_registration_phases_todos`
--
ALTER TABLE `a_registration_phases_todos`
  ADD PRIMARY KEY (`todo_id`),
  ADD KEY `phase_id` (`phase_id`);

--
-- Indexes for table `employees_list`
--
ALTER TABLE `employees_list`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `country_id` (`nationality_id`),
  ADD KEY `gender_id` (`gender_id`),
  ADD KEY `timezone_id` (`timezone_id`),
  ADD KEY `title_id` (`title_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `employees_list_creds`
--
ALTER TABLE `employees_list_creds`
  ADD PRIMARY KEY (`employee_credential_id`);

--
-- Indexes for table `employees_list_departments`
--
ALTER TABLE `employees_list_departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `employees_list_designations`
--
ALTER TABLE `employees_list_designations`
  ADD PRIMARY KEY (`designation_id`);

--
-- Indexes for table `employees_list_pass`
--
ALTER TABLE `employees_list_pass`
  ADD PRIMARY KEY (`pass_id`),
  ADD KEY `studen_id` (`employee_id`);

--
-- Indexes for table `employees_list_services`
--
ALTER TABLE `employees_list_services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `employees_list_staus`
--
ALTER TABLE `employees_list_staus`
  ADD PRIMARY KEY (`staus_id`);

--
-- Indexes for table `employees_notifications`
--
ALTER TABLE `employees_notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `employees_services`
--
ALTER TABLE `employees_services`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `feedback_forms`
--
ALTER TABLE `feedback_forms`
  ADD PRIMARY KEY (`form_id`);

--
-- Indexes for table `feedback_forms_answers`
--
ALTER TABLE `feedback_forms_answers`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `hr_approvals`
--
ALTER TABLE `hr_approvals`
  ADD PRIMARY KEY (`approval_id`);

--
-- Indexes for table `hr_attendance`
--
ALTER TABLE `hr_attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `hr_certificates`
--
ALTER TABLE `hr_certificates`
  ADD PRIMARY KEY (`certificate_id`);

--
-- Indexes for table `hr_disp_actions`
--
ALTER TABLE `hr_disp_actions`
  ADD PRIMARY KEY (`da_id`);

--
-- Indexes for table `hr_disp_actions_status`
--
ALTER TABLE `hr_disp_actions_status`
  ADD PRIMARY KEY (`da_status_id`);

--
-- Indexes for table `hr_disp_actions_types`
--
ALTER TABLE `hr_disp_actions_types`
  ADD PRIMARY KEY (`da_type_id`);

--
-- Indexes for table `hr_disp_actions_warnings`
--
ALTER TABLE `hr_disp_actions_warnings`
  ADD PRIMARY KEY (`da_warning_id`);

--
-- Indexes for table `hr_documents`
--
ALTER TABLE `hr_documents`
  ADD PRIMARY KEY (`document_id`);

--
-- Indexes for table `hr_documents_types`
--
ALTER TABLE `hr_documents_types`
  ADD PRIMARY KEY (`document_type_id`);

--
-- Indexes for table `hr_employees_leaves`
--
ALTER TABLE `hr_employees_leaves`
  ADD PRIMARY KEY (`leave_id`);

--
-- Indexes for table `hr_employees_leave_status`
--
ALTER TABLE `hr_employees_leave_status`
  ADD PRIMARY KEY (`leave_status_id`);

--
-- Indexes for table `hr_employees_leave_types`
--
ALTER TABLE `hr_employees_leave_types`
  ADD PRIMARY KEY (`leave_type_id`);

--
-- Indexes for table `hr_employees_permissions`
--
ALTER TABLE `hr_employees_permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `hr_employees_permissions_status`
--
ALTER TABLE `hr_employees_permissions_status`
  ADD PRIMARY KEY (`permission_status_id`);

--
-- Indexes for table `hr_exit_interviews`
--
ALTER TABLE `hr_exit_interviews`
  ADD PRIMARY KEY (`interview_id`);

--
-- Indexes for table `hr_exit_interviews_answers`
--
ALTER TABLE `hr_exit_interviews_answers`
  ADD PRIMARY KEY (`answer_id`);

--
-- Indexes for table `hr_exit_interviews_questions`
--
ALTER TABLE `hr_exit_interviews_questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `hr_performance`
--
ALTER TABLE `hr_performance`
  ADD PRIMARY KEY (`performance_id`);

--
-- Indexes for table `local_routes_atp_reg`
--
ALTER TABLE `local_routes_atp_reg`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `local_routes_default`
--
ALTER TABLE `local_routes_default`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `local_routes_external`
--
ALTER TABLE `local_routes_external`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `local_routes_hr`
--
ALTER TABLE `local_routes_hr`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `local_routes_it`
--
ALTER TABLE `local_routes_it`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `local_routes_rc`
--
ALTER TABLE `local_routes_rc`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `local_routes_root`
--
ALTER TABLE `local_routes_root`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `m_communications_list`
--
ALTER TABLE `m_communications_list`
  ADD PRIMARY KEY (`communication_id`);

--
-- Indexes for table `m_communications_list_status`
--
ALTER TABLE `m_communications_list_status`
  ADD PRIMARY KEY (`communication_status_id`);

--
-- Indexes for table `m_communications_list_types`
--
ALTER TABLE `m_communications_list_types`
  ADD PRIMARY KEY (`communication_type_id`);

--
-- Indexes for table `m_operational_projects`
--
ALTER TABLE `m_operational_projects`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `m_operational_projects_kpis`
--
ALTER TABLE `m_operational_projects_kpis`
  ADD PRIMARY KEY (`linked_kpi_id`);

--
-- Indexes for table `m_operational_projects_milestones`
--
ALTER TABLE `m_operational_projects_milestones`
  ADD PRIMARY KEY (`milestone_id`);

--
-- Indexes for table `m_strategic_plans`
--
ALTER TABLE `m_strategic_plans`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `m_strategic_plans_external_maps`
--
ALTER TABLE `m_strategic_plans_external_maps`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `m_strategic_plans_internal_maps`
--
ALTER TABLE `m_strategic_plans_internal_maps`
  ADD PRIMARY KEY (`local_map_id`);

--
-- Indexes for table `m_strategic_plans_internal_maps_tasks`
--
ALTER TABLE `m_strategic_plans_internal_maps_tasks`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `m_strategic_plans_kpis`
--
ALTER TABLE `m_strategic_plans_kpis`
  ADD PRIMARY KEY (`kpi_id`);

--
-- Indexes for table `m_strategic_plans_kpis_freqs`
--
ALTER TABLE `m_strategic_plans_kpis_freqs`
  ADD PRIMARY KEY (`kpi_frequncy_id`);

--
-- Indexes for table `m_strategic_plans_milestones`
--
ALTER TABLE `m_strategic_plans_milestones`
  ADD PRIMARY KEY (`milestone_id`);

--
-- Indexes for table `m_strategic_plans_objectives`
--
ALTER TABLE `m_strategic_plans_objectives`
  ADD PRIMARY KEY (`objective_id`);

--
-- Indexes for table `m_strategic_plans_objective_types`
--
ALTER TABLE `m_strategic_plans_objective_types`
  ADD PRIMARY KEY (`objective_type_id`);

--
-- Indexes for table `m_strategic_plans_themes`
--
ALTER TABLE `m_strategic_plans_themes`
  ADD PRIMARY KEY (`theme_id`);

--
-- Indexes for table `m_strategic_studies`
--
ALTER TABLE `m_strategic_studies`
  ADD PRIMARY KEY (`study_id`);

--
-- Indexes for table `m_strategic_studies_pages`
--
ALTER TABLE `m_strategic_studies_pages`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `ss_list`
--
ALTER TABLE `ss_list`
  ADD PRIMARY KEY (`ss_id`);

--
-- Indexes for table `ss_list_cats`
--
ALTER TABLE `ss_list_cats`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `ss_list_status`
--
ALTER TABLE `ss_list_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `support_tickets_list`
--
ALTER TABLE `support_tickets_list`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `support_tickets_list_cats`
--
ALTER TABLE `support_tickets_list_cats`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `support_tickets_list_status`
--
ALTER TABLE `support_tickets_list_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `sys_countries`
--
ALTER TABLE `sys_countries`
  ADD PRIMARY KEY (`country_id`),
  ADD KEY `continent_id` (`continent_id`);

--
-- Indexes for table `sys_countries_cities`
--
ALTER TABLE `sys_countries_cities`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `sys_countries_cities_areas`
--
ALTER TABLE `sys_countries_cities_areas`
  ADD PRIMARY KEY (`area_id`);

--
-- Indexes for table `sys_errors`
--
ALTER TABLE `sys_errors`
  ADD PRIMARY KEY (`error_id`);

--
-- Indexes for table `sys_lists`
--
ALTER TABLE `sys_lists`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `sys_lists_colors`
--
ALTER TABLE `sys_lists_colors`
  ADD PRIMARY KEY (`color_id`);

--
-- Indexes for table `sys_list_priorities`
--
ALTER TABLE `sys_list_priorities`
  ADD PRIMARY KEY (`priority_id`);

--
-- Indexes for table `sys_list_status`
--
ALTER TABLE `sys_list_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `sys_logs`
--
ALTER TABLE `sys_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `sys_settings`
--
ALTER TABLE `sys_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `sys_timezones_list`
--
ALTER TABLE `sys_timezones_list`
  ADD PRIMARY KEY (`timezone_id`);

--
-- Indexes for table `tasks_list`
--
ALTER TABLE `tasks_list`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `users_list`
--
ALTER TABLE `users_list`
  ADD PRIMARY KEY (`record_id`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users_list_themes`
--
ALTER TABLE `users_list_themes`
  ADD PRIMARY KEY (`user_theme_id`);

--
-- Indexes for table `z_assets_list`
--
ALTER TABLE `z_assets_list`
  ADD PRIMARY KEY (`asset_id`);

--
-- Indexes for table `z_assets_list_assign`
--
ALTER TABLE `z_assets_list_assign`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `z_assets_list_cats`
--
ALTER TABLE `z_assets_list_cats`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `z_assets_list_status`
--
ALTER TABLE `z_assets_list_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `z_groups_list`
--
ALTER TABLE `z_groups_list`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `z_groups_list_files`
--
ALTER TABLE `z_groups_list_files`
  ADD PRIMARY KEY (`file_id`);

--
-- Indexes for table `z_groups_list_members`
--
ALTER TABLE `z_groups_list_members`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `z_groups_list_posts`
--
ALTER TABLE `z_groups_list_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `z_groups_list_roles`
--
ALTER TABLE `z_groups_list_roles`
  ADD PRIMARY KEY (`group_role_id`);

--
-- Indexes for table `z_messages_list`
--
ALTER TABLE `z_messages_list`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indexes for table `z_messages_list_files`
--
ALTER TABLE `z_messages_list_files`
  ADD PRIMARY KEY (`file_id`);

--
-- Indexes for table `z_messages_list_posts`
--
ALTER TABLE `z_messages_list_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `atps_electronic_systems`
--
ALTER TABLE `atps_electronic_systems`
  MODIFY `platform_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `atps_form_init`
--
ALTER TABLE `atps_form_init`
  MODIFY `form_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `atps_info_request`
--
ALTER TABLE `atps_info_request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `atps_info_request_evs`
--
ALTER TABLE `atps_info_request_evs`
  MODIFY `evidence_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `atps_learners_statistics`
--
ALTER TABLE `atps_learners_statistics`
  MODIFY `statistic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `atps_list`
--
ALTER TABLE `atps_list`
  MODIFY `atp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `atps_list_categories`
--
ALTER TABLE `atps_list_categories`
  MODIFY `atp_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `atps_list_contacts`
--
ALTER TABLE `atps_list_contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `atps_list_faculties`
--
ALTER TABLE `atps_list_faculties`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `atps_list_faculties_types`
--
ALTER TABLE `atps_list_faculties_types`
  MODIFY `faculty_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `atps_list_logs`
--
ALTER TABLE `atps_list_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `atps_list_pass`
--
ALTER TABLE `atps_list_pass`
  MODIFY `pass_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `atps_list_qualifications`
--
ALTER TABLE `atps_list_qualifications`
  MODIFY `qualification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `atps_list_qualifications_faculties`
--
ALTER TABLE `atps_list_qualifications_faculties`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `atps_list_requests`
--
ALTER TABLE `atps_list_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `atps_list_status`
--
ALTER TABLE `atps_list_status`
  MODIFY `atp_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `atps_list_types`
--
ALTER TABLE `atps_list_types`
  MODIFY `atp_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `atps_prog_register_req`
--
ALTER TABLE `atps_prog_register_req`
  MODIFY `form_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `a_registration_phases`
--
ALTER TABLE `a_registration_phases`
  MODIFY `phase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `a_registration_phases_todos`
--
ALTER TABLE `a_registration_phases_todos`
  MODIFY `todo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `employees_list`
--
ALTER TABLE `employees_list`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `employees_list_creds`
--
ALTER TABLE `employees_list_creds`
  MODIFY `employee_credential_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `employees_list_departments`
--
ALTER TABLE `employees_list_departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `employees_list_designations`
--
ALTER TABLE `employees_list_designations`
  MODIFY `designation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employees_list_pass`
--
ALTER TABLE `employees_list_pass`
  MODIFY `pass_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `employees_list_services`
--
ALTER TABLE `employees_list_services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10008;

--
-- AUTO_INCREMENT for table `employees_list_staus`
--
ALTER TABLE `employees_list_staus`
  MODIFY `staus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees_notifications`
--
ALTER TABLE `employees_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_services`
--
ALTER TABLE `employees_services`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `feedback_forms`
--
ALTER TABLE `feedback_forms`
  MODIFY `form_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `feedback_forms_answers`
--
ALTER TABLE `feedback_forms_answers`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hr_approvals`
--
ALTER TABLE `hr_approvals`
  MODIFY `approval_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hr_attendance`
--
ALTER TABLE `hr_attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `hr_certificates`
--
ALTER TABLE `hr_certificates`
  MODIFY `certificate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hr_disp_actions`
--
ALTER TABLE `hr_disp_actions`
  MODIFY `da_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hr_disp_actions_status`
--
ALTER TABLE `hr_disp_actions_status`
  MODIFY `da_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hr_disp_actions_types`
--
ALTER TABLE `hr_disp_actions_types`
  MODIFY `da_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `hr_disp_actions_warnings`
--
ALTER TABLE `hr_disp_actions_warnings`
  MODIFY `da_warning_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hr_documents`
--
ALTER TABLE `hr_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hr_documents_types`
--
ALTER TABLE `hr_documents_types`
  MODIFY `document_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hr_employees_leaves`
--
ALTER TABLE `hr_employees_leaves`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hr_employees_leave_status`
--
ALTER TABLE `hr_employees_leave_status`
  MODIFY `leave_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hr_employees_leave_types`
--
ALTER TABLE `hr_employees_leave_types`
  MODIFY `leave_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hr_employees_permissions`
--
ALTER TABLE `hr_employees_permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_employees_permissions_status`
--
ALTER TABLE `hr_employees_permissions_status`
  MODIFY `permission_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hr_exit_interviews`
--
ALTER TABLE `hr_exit_interviews`
  MODIFY `interview_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hr_exit_interviews_answers`
--
ALTER TABLE `hr_exit_interviews_answers`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hr_exit_interviews_questions`
--
ALTER TABLE `hr_exit_interviews_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hr_performance`
--
ALTER TABLE `hr_performance`
  MODIFY `performance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `local_routes_atp_reg`
--
ALTER TABLE `local_routes_atp_reg`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `local_routes_default`
--
ALTER TABLE `local_routes_default`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `local_routes_external`
--
ALTER TABLE `local_routes_external`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `local_routes_hr`
--
ALTER TABLE `local_routes_hr`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `local_routes_it`
--
ALTER TABLE `local_routes_it`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `local_routes_rc`
--
ALTER TABLE `local_routes_rc`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `local_routes_root`
--
ALTER TABLE `local_routes_root`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `m_communications_list`
--
ALTER TABLE `m_communications_list`
  MODIFY `communication_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_communications_list_status`
--
ALTER TABLE `m_communications_list_status`
  MODIFY `communication_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `m_communications_list_types`
--
ALTER TABLE `m_communications_list_types`
  MODIFY `communication_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `m_operational_projects`
--
ALTER TABLE `m_operational_projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `m_operational_projects_kpis`
--
ALTER TABLE `m_operational_projects_kpis`
  MODIFY `linked_kpi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `m_operational_projects_milestones`
--
ALTER TABLE `m_operational_projects_milestones`
  MODIFY `milestone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_strategic_plans`
--
ALTER TABLE `m_strategic_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `m_strategic_plans_external_maps`
--
ALTER TABLE `m_strategic_plans_external_maps`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `m_strategic_plans_internal_maps`
--
ALTER TABLE `m_strategic_plans_internal_maps`
  MODIFY `local_map_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_strategic_plans_internal_maps_tasks`
--
ALTER TABLE `m_strategic_plans_internal_maps_tasks`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_strategic_plans_kpis`
--
ALTER TABLE `m_strategic_plans_kpis`
  MODIFY `kpi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `m_strategic_plans_kpis_freqs`
--
ALTER TABLE `m_strategic_plans_kpis_freqs`
  MODIFY `kpi_frequncy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `m_strategic_plans_milestones`
--
ALTER TABLE `m_strategic_plans_milestones`
  MODIFY `milestone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_strategic_plans_objectives`
--
ALTER TABLE `m_strategic_plans_objectives`
  MODIFY `objective_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `m_strategic_plans_objective_types`
--
ALTER TABLE `m_strategic_plans_objective_types`
  MODIFY `objective_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `m_strategic_plans_themes`
--
ALTER TABLE `m_strategic_plans_themes`
  MODIFY `theme_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `m_strategic_studies`
--
ALTER TABLE `m_strategic_studies`
  MODIFY `study_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `m_strategic_studies_pages`
--
ALTER TABLE `m_strategic_studies_pages`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ss_list`
--
ALTER TABLE `ss_list`
  MODIFY `ss_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ss_list_cats`
--
ALTER TABLE `ss_list_cats`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ss_list_status`
--
ALTER TABLE `ss_list_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `support_tickets_list`
--
ALTER TABLE `support_tickets_list`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets_list_cats`
--
ALTER TABLE `support_tickets_list_cats`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `support_tickets_list_status`
--
ALTER TABLE `support_tickets_list_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sys_countries`
--
ALTER TABLE `sys_countries`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT for table `sys_countries_cities`
--
ALTER TABLE `sys_countries_cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sys_countries_cities_areas`
--
ALTER TABLE `sys_countries_cities_areas`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `sys_errors`
--
ALTER TABLE `sys_errors`
  MODIFY `error_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_lists`
--
ALTER TABLE `sys_lists`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `sys_lists_colors`
--
ALTER TABLE `sys_lists_colors`
  MODIFY `color_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sys_list_priorities`
--
ALTER TABLE `sys_list_priorities`
  MODIFY `priority_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sys_list_status`
--
ALTER TABLE `sys_list_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sys_logs`
--
ALTER TABLE `sys_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_settings`
--
ALTER TABLE `sys_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sys_timezones_list`
--
ALTER TABLE `sys_timezones_list`
  MODIFY `timezone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=598;

--
-- AUTO_INCREMENT for table `tasks_list`
--
ALTER TABLE `tasks_list`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_list`
--
ALTER TABLE `users_list`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `users_list_themes`
--
ALTER TABLE `users_list_themes`
  MODIFY `user_theme_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `z_assets_list`
--
ALTER TABLE `z_assets_list`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `z_assets_list_assign`
--
ALTER TABLE `z_assets_list_assign`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `z_assets_list_cats`
--
ALTER TABLE `z_assets_list_cats`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `z_assets_list_status`
--
ALTER TABLE `z_assets_list_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `z_groups_list`
--
ALTER TABLE `z_groups_list`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `z_groups_list_files`
--
ALTER TABLE `z_groups_list_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `z_groups_list_members`
--
ALTER TABLE `z_groups_list_members`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `z_groups_list_posts`
--
ALTER TABLE `z_groups_list_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `z_groups_list_roles`
--
ALTER TABLE `z_groups_list_roles`
  MODIFY `group_role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `z_messages_list`
--
ALTER TABLE `z_messages_list`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `z_messages_list_files`
--
ALTER TABLE `z_messages_list_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `z_messages_list_posts`
--
ALTER TABLE `z_messages_list_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
