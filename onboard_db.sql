-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2025 at 04:51 AM
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
-- Database: `onboard_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_login_attempts`
--

CREATE TABLE `admin_login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `email`, `full_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'Administrator', 1, '2025-09-19 15:50:33', '2025-09-19 15:50:33');

-- --------------------------------------------------------

--
-- Table structure for table `audiences`
--

CREATE TABLE `audiences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `age_range` varchar(100) DEFAULT NULL,
  `gender` enum('পুরুষ','মহিলা','উভয়','উল্লেখ_করতে_চাই_না','') DEFAULT '',
  `location` varchar(255) DEFAULT NULL,
  `profession_income` varchar(255) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `interests` text DEFAULT NULL,
  `problems` text DEFAULT NULL,
  `social_media` varchar(255) DEFAULT NULL,
  `online_behavior` text DEFAULT NULL,
  `multiple_target_audiences` text DEFAULT NULL,
  `purchase_influencers` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audiences`
--

INSERT INTO `audiences` (`id`, `submission_id`, `age_range`, `gender`, `location`, `profession_income`, `education`, `interests`, `problems`, `social_media`, `online_behavior`, `multiple_target_audiences`, `purchase_influencers`) VALUES
(1, 1, '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `audience_content_formats`
--

CREATE TABLE `audience_content_formats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `content_format` enum('ভিডিও','ব্লগ_পোস্ট','ইনফোগ্রাফিক','পডকাস্ট','ছবি','কেস_স্টাডি','ওয়েবিনার') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audience_devices`
--

CREATE TABLE `audience_devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `device` enum('মোবাইল','ল্যাপটপ','ডেস্কটপ','ট্যাবলেট') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget_tech_future`
--

CREATE TABLE `budget_tech_future` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `project_budget` varchar(120) DEFAULT NULL,
  `budget_priorities` text DEFAULT NULL,
  `results_timeline` varchar(120) DEFAULT NULL,
  `reporting_preference` enum('সাপ্তাহিক','পাক্ষিক','মাসিক','প্রয়োজন_অনুযায়ী','') DEFAULT '',
  `need_website_admin_access` enum('হ্যাঁ','না','পরে_জানাবো','') DEFAULT '',
  `need_social_media_access` enum('হ্যাঁ','না','পরে_জানাবো','') DEFAULT '',
  `tracking_code_setup` enum('হ্যাঁ','না','') DEFAULT '',
  `tracking_code_access_needed` varchar(120) DEFAULT NULL,
  `future_products_services` text DEFAULT NULL,
  `tech_usage_plans` text DEFAULT NULL,
  `ecommerce_payment_experience` enum('বর্তমানে_ব্যবহার_করছি','ভবিষ্যতে_আগ্রহী','না_আগ্রহ_নেই','') DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budget_tech_future`
--

INSERT INTO `budget_tech_future` (`id`, `submission_id`, `project_budget`, `budget_priorities`, `results_timeline`, `reporting_preference`, `need_website_admin_access`, `need_social_media_access`, `tracking_code_setup`, `tracking_code_access_needed`, `future_products_services`, `tech_usage_plans`, `ecommerce_payment_experience`) VALUES
(1, 1, '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `company_profiles`
--

CREATE TABLE `company_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `facebook_page` varchar(255) DEFAULT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `business_type` enum('একক_মালিকানা','পার্টনারশিপ','প্রাইভেট_লিমিটেড','পাবলিক_লিমিটেড','এনজিও','অন্যান্য','') DEFAULT '',
  `business_start_date` varchar(50) DEFAULT NULL,
  `business_details` text DEFAULT NULL,
  `main_products_services` text DEFAULT NULL,
  `usp` text DEFAULT NULL,
  `team_structure` text DEFAULT NULL,
  `financial_status` text DEFAULT NULL,
  `revenue_sources` text DEFAULT NULL,
  `seasonal_impact` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_profiles`
--

INSERT INTO `company_profiles` (`id`, `submission_id`, `company_name`, `website`, `facebook_page`, `whatsapp_number`, `address`, `business_type`, `business_start_date`, `business_details`, `main_products_services`, `usp`, `team_structure`, `financial_status`, `revenue_sources`, `seasonal_impact`) VALUES
(1, 1, 'dfsdfsd', '', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `competition_analysis`
--

CREATE TABLE `competition_analysis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `strengths_weaknesses` text DEFAULT NULL,
  `marketing_likes` text DEFAULT NULL,
  `product_differentiation` text DEFAULT NULL,
  `competitor_target_audience` text DEFAULT NULL,
  `pricing_strategy` text DEFAULT NULL,
  `customer_service` text DEFAULT NULL,
  `market_share` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `competition_analysis`
--

INSERT INTO `competition_analysis` (`id`, `submission_id`, `strengths_weaknesses`, `marketing_likes`, `product_differentiation`, `competitor_target_audience`, `pricing_strategy`, `customer_service`, `market_share`) VALUES
(1, 1, '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `competitors`
--

CREATE TABLE `competitors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `contact_name` varchar(150) NOT NULL,
  `contact_title` varchar(150) NOT NULL,
  `email` varchar(190) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `alternate_phone` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `submission_id`, `contact_name`, `contact_title`, `email`, `phone`, `alternate_phone`) VALUES
(1, 1, 'fdsfsdfs', 'fdsfsdafsadf', 'sozibalahi@gmail.com', '01774226088', '');

-- --------------------------------------------------------

--
-- Table structure for table `finalization`
--

CREATE TABLE `finalization` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `additional_info` text DEFAULT NULL,
  `how_found_agency` varchar(255) DEFAULT NULL,
  `questions_for_agency` text DEFAULT NULL,
  `submission_date` date DEFAULT NULL,
  `terms_agreed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finalization`
--

INSERT INTO `finalization` (`id`, `submission_id`, `additional_info`, `how_found_agency`, `questions_for_agency`, `submission_date`, `terms_agreed`) VALUES
(1, 1, '', '', '', '2025-09-19', 1);

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `goal_text` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marketing_branding`
--

CREATE TABLE `marketing_branding` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `current_activities` text DEFAULT NULL,
  `successful_activities` text DEFAULT NULL,
  `less_successful_activities` text DEFAULT NULL,
  `monthly_budget` varchar(100) DEFAULT NULL,
  `ad_budget_allocation` text DEFAULT NULL,
  `in_house_team` text DEFAULT NULL,
  `previous_agency_experience` text DEFAULT NULL,
  `analytics_tool_installed` tinyint(1) DEFAULT NULL,
  `analytics_tool_name` varchar(255) DEFAULT NULL,
  `crm_used` tinyint(1) DEFAULT NULL,
  `crm_name` varchar(255) DEFAULT NULL,
  `website_traffic_sources` text DEFAULT NULL,
  `email_marketing_list` text DEFAULT NULL,
  `social_media_engagement` text DEFAULT NULL,
  `cac_understanding` text DEFAULT NULL,
  `marketing_automation_tools` text DEFAULT NULL,
  `brand_guideline` enum('হ্যাঁ','না','') DEFAULT '',
  `brand_personality` varchar(255) DEFAULT NULL,
  `existing_marketing_materials` text DEFAULT NULL,
  `blog_status` enum('হ্যাঁ','না','') DEFAULT '',
  `blog_frequency` varchar(100) DEFAULT NULL,
  `brand_tone_of_voice` varchar(150) DEFAULT NULL,
  `content_themes_pillars` text DEFAULT NULL,
  `preferred_visuals` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marketing_branding`
--

INSERT INTO `marketing_branding` (`id`, `submission_id`, `current_activities`, `successful_activities`, `less_successful_activities`, `monthly_budget`, `ad_budget_allocation`, `in_house_team`, `previous_agency_experience`, `analytics_tool_installed`, `analytics_tool_name`, `crm_used`, `crm_name`, `website_traffic_sources`, `email_marketing_list`, `social_media_engagement`, `cac_understanding`, `marketing_automation_tools`, `brand_guideline`, `brand_personality`, `existing_marketing_materials`, `blog_status`, `blog_frequency`, `brand_tone_of_voice`, `content_themes_pillars`, `preferred_visuals`) VALUES
(1, 1, '', '', '', '', '', '', '', NULL, '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `objectives`
--

CREATE TABLE `objectives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `business_vision` text DEFAULT NULL,
  `business_mission` text DEFAULT NULL,
  `core_values` text DEFAULT NULL,
  `main_challenges` text DEFAULT NULL,
  `agency_expectations` text DEFAULT NULL,
  `marketing_kpis` text DEFAULT NULL,
  `past_failures` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `objectives`
--

INSERT INTO `objectives` (`id`, `submission_id`, `business_vision`, `business_mission`, `core_values`, `main_challenges`, `agency_expectations`, `marketing_kpis`, `past_failures`) VALUES
(1, 1, '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `created_at`, `updated_at`) VALUES
(1, '2025-09-19 15:12:35', '2025-09-19 15:12:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_login_attempts`
--
ALTER TABLE `admin_login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ip_time` (`ip_address`,`attempt_time`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `audiences`
--
ALTER TABLE `audiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_audiences_submission` (`submission_id`);

--
-- Indexes for table `audience_content_formats`
--
ALTER TABLE `audience_content_formats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_submission_format` (`submission_id`,`content_format`);

--
-- Indexes for table `audience_devices`
--
ALTER TABLE `audience_devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_submission_device` (`submission_id`,`device`);

--
-- Indexes for table `budget_tech_future`
--
ALTER TABLE `budget_tech_future`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_budget_tech_future_submission` (`submission_id`);

--
-- Indexes for table `company_profiles`
--
ALTER TABLE `company_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_company_submission` (`submission_id`);

--
-- Indexes for table `competition_analysis`
--
ALTER TABLE `competition_analysis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_competition_analysis_submission` (`submission_id`);

--
-- Indexes for table `competitors`
--
ALTER TABLE `competitors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_competitors_submission` (`submission_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contact_submission` (`submission_id`),
  ADD KEY `idx_contacts_email` (`email`);

--
-- Indexes for table `finalization`
--
ALTER TABLE `finalization`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_finalization_submission` (`submission_id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_goals_submission` (`submission_id`);

--
-- Indexes for table `marketing_branding`
--
ALTER TABLE `marketing_branding`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_marketing_branding_submission` (`submission_id`);

--
-- Indexes for table `objectives`
--
ALTER TABLE `objectives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_objectives_submission` (`submission_id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_login_attempts`
--
ALTER TABLE `admin_login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audiences`
--
ALTER TABLE `audiences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audience_content_formats`
--
ALTER TABLE `audience_content_formats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audience_devices`
--
ALTER TABLE `audience_devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budget_tech_future`
--
ALTER TABLE `budget_tech_future`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company_profiles`
--
ALTER TABLE `company_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `competition_analysis`
--
ALTER TABLE `competition_analysis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `competitors`
--
ALTER TABLE `competitors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `finalization`
--
ALTER TABLE `finalization`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marketing_branding`
--
ALTER TABLE `marketing_branding`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `objectives`
--
ALTER TABLE `objectives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audiences`
--
ALTER TABLE `audiences`
  ADD CONSTRAINT `fk_audiences_submission` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `audience_content_formats`
--
ALTER TABLE `audience_content_formats`
  ADD CONSTRAINT `fk_audience_formats_submission` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `audience_devices`
--
ALTER TABLE `audience_devices`
  ADD CONSTRAINT `fk_audience_devices_submission` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `budget_tech_future`
--
ALTER TABLE `budget_tech_future`
  ADD CONSTRAINT `fk_budget_tech_future_submission` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_profiles`
--
ALTER TABLE `company_profiles`
  ADD CONSTRAINT `fk_company_submission` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `competition_analysis`
--
ALTER TABLE `competition_analysis`
  ADD CONSTRAINT `fk_competition_analysis_submission` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `competitors`
--
ALTER TABLE `competitors`
  ADD CONSTRAINT `fk_competitors_submission` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `fk_contact_submission` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `finalization`
--
ALTER TABLE `finalization`
  ADD CONSTRAINT `fk_finalization_submission` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `fk_goals_submission` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marketing_branding`
--
ALTER TABLE `marketing_branding`
  ADD CONSTRAINT `fk_marketing_branding_submission` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `objectives`
--
ALTER TABLE `objectives`
  ADD CONSTRAINT `fk_objectives_submission` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
