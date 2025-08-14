-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Generation Time: Aug 11, 2025 at 02:07 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clean_homepro`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '123');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','approved','rejected','completed','cancelled','conformed') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `customer_id`, `provider_id`, `customer_name`, `customer_email`, `appointment_date`, `appointment_time`, `status`, `created_at`) VALUES
(2, 12, 8, 'sankalpani hrath', 'sankalapani@gmail.com', '2025-07-08', '08:00:00', 'cancelled', '2025-06-15 16:19:06'),
(3, 11, 9, 'wasana sewwandi', 'wasana@gmail.com', '2025-07-10', '17:52:00', 'cancelled', '2025-06-15 16:23:07'),
(4, 12, 10, 'sankalpani', 'sankalapani@gmail.com', '2025-08-20', '15:55:00', 'cancelled', '2025-06-15 16:26:10'),
(6, 11, 5, 'madu', 'madu@gmail.com', '2025-06-20', '08:13:00', 'cancelled', '2025-06-16 10:43:39'),
(7, 11, 10, 'wasana', 'wasana@gmail.com', '2025-06-12', '12:00:00', 'completed', '2025-06-17 07:26:19'),
(8, 11, 4, 'wasana', 'wasana@gmail.com', '2025-06-13', '00:21:00', 'completed', '2025-06-29 18:48:46');

-- --------------------------------------------------------

--
-- Table structure for table `service_provider`
--

CREATE TABLE `service_provider` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `profile_completed` tinyint(1) DEFAULT 0,
  `profile_image` varchar(255) DEFAULT NULL,
  `sip_receipt_image` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_provider`
--

INSERT INTO `service_provider` (`id`, `name`, `email`, `phone`, `address`, `category`, `experience`, `description`, `price`, `profile_completed`, `profile_image`, `sip_receipt_image`, `status`, `admin_message`) VALUES
(4, 'dilshan wijesekara', 'dilshan@gmail.com', '0751078823', '15 Canal Road, Panadura  ', 'Plumbing', '3', 'I am a skilled plumbing professional specializing in tap installation and maintenance services. With hands-on experience in fitting kitchen, bathroom, and outdoor taps, I ensure leak-free and long-lasting results. I use quality tools and materials to provide reliable and efficient service. Whether it is a new tap installation, replacement, or repair, I am committed to delivering neat, timely, and affordable solutions for your home or office.', 2500.00, 1, 'profile_684eeb5bae7251.39285629.jpg', 'sip_684eeb5bae7398.04136950.pdf', 'approved', NULL),
(5, 'Ruwan Jayasuriya ', 'ruwan@gmail.com', '0751558823', '10 Lake Road, Colombo', 'Cleaning', '4', 'I am ruwan, a dedicated cleaning professional specializing in bathroom cleaning services for homes and businesses. I use high-grade disinfectants and cleaning tools to remove stains, soap scum, and bacteria, ensuring a hygienic and sparkling clean bathroom. My goal is to deliver cleanliness and comfort with every service.\r\nServices Offered:\r\nDeep bathroom cleaning\r\nToilet and sink sanitizing\r\nTile and grout cleaning\r\nMirror and glass cleaning\r\nMold and mildew removal\r\nEco-friendly cleaning on request\r\n\r\n', 2000.00, 1, 'profile_684eed25a46eb1.00374157.jpg', 'sip_684eed25a46f76.56990695.pdf', 'approved', NULL),
(6, 'Ashani Bandara ', 'ashani@gmail.com', '0751558823', '255 Grand Park, Mahawa  ', 'Cleaning', '5', 'I am a professional cleaning service provider with over 5 years of experience in residential and commercial cleaning. I offer deep cleaning, regular house cleaning, office cleaning, and post-construction cleaning services. My goal is to deliver a spotless and fresh environment using eco-friendly products and reliable techniques. Customer satisfaction and hygiene are my top priorities.', 3500.00, 1, 'profile_684eedc17bec54.91659360.jpg', 'sip_684eedc17bed32.71006388.pdf', 'approved', NULL),
(7, 'Ramya Silva', 'ramya@gmail.com', '0781008823', '525 Bowdaloka Street, Kandy', 'Cleaning', '5', 'I am Ramaya Silva, a professional laundry specialist with over 5 years of experience in washing, drying, and ironing all types of garments with care and efficiency. I use high-quality detergents and offer pickup and delivery within your area. Whether it&#039;s daily laundry or special fabric care, I ensure your clothes are handled with the utmost professionalism.\r\nServices Offered:\r\nRegular clothes washing\r\nDelicate and fabric-specific washing\r\nIroning and folding\r\nPickup &amp; delivery options\r\nExpress service (same-day)\r\nLanguages Spoken: Sinhala, English\r\n', 2700.00, 1, 'profile_684eee3d82a0b5.09626575.jpg', 'sip_684eee3d82a204.14867536.pdf', 'approved', NULL),
(8, 'Chathura Senanayake ', 'chathura@gmail.com', '0751478823', '87 Flower Road, Gampaha', 'Painting', '2', 'I am chathura, a dedicated cleaning professional specializing in painter services for homes and businesses. I use high-grade disinfectants and cleaning tools to remove stains, soap scum, and bacteria, ensuring a hygienic and sparkling clean bathroom. My goal is to deliver cleanliness and comfort with every service.\r\nServices Offered:\r\nDeep bathroom cleaning\r\nToilet and sink sanitizing\r\nTile and grout cleaning\r\nMirror and glass cleaning\r\nMold and mildew removal\r\nEco-friendly cleaning on request\r\n', 2500.00, 1, 'profile_684eef4edfde01.52063320.jpg', 'sip_684eef4edfdf26.85631690.pdf', 'approved', NULL),
(9, 'Sunil Madushanka ', 'sunil@gmail.com', '0752345673', '12 River View, Anuradhapura', 'Electric', '8', 'I am a skilled plumbing professional specializing in tap installation and maintenance services. With hands-on experience in fitting kitchen, bathroom, and outdoor taps, I ensure leak-free and long-lasting results. I use quality tools and materials to provide reliable and efficient service. Whether it is a new tap installation, replacement, or repair, I am committed to delivering neat, timely, and affordable solutions for your home or office.', 2500.00, 1, 'profile_684ef0a4767796.17930238.jpg', 'sip_684ef0a4767899.50590916.pdf', 'approved', NULL),
(10, 'Isuru Nuwan ', 'isuru@gmail.com', '0761558823', '56 Lake Side, Kegalle', 'Shifting', '7', 'I am a skilled plumbing professional specializing in tap installation and maintenance services. With hands-on experience in fitting kitchen, bathroom, and outdoor taps, I ensure leak-free and long-lasting results. I use quality tools and materials to provide reliable and efficient service. Whether it is a new tap installation, replacement, or repair, I am committed to delivering neat, timely, and affordable solutions for your home or office', 4500.00, 1, 'profile_684ef15fe39820.57258338.jpg', 'sip_684ef15fe39912.12994734.pdf', 'approved', NULL),
(17, 'mandira', 'mandira@gmail.com', '0751078823', 'kandy', 'Cleaning', '2', 'gbbbgfbgfbf', 2500.00, 1, 'profile_684fd85e988c53.42596544.jpg', 'sip_684fd85e988d93.53927481.jpg', 'approved', NULL),
(18, 'madu', 'madu@gmail.com', '0751078823', 'kurunegala', 'Cleaning', '2', 'ddd', 3500.00, 1, 'profile_684ff5c9a2e524.13416333.jpg', 'sip_684ff5c9a2e871.46246749.pdf', 'approved', NULL),
(19, 'kasuni', 'k@mail.com', '0751078823', 'gbgbv', 'Plumbing', '8', 'hghg', 2000.00, 1, 'profile_6851112029f412.62584402.jpg', 'sip_6851112029f582.96708074.pdf', 'approved', NULL),
(20, '', '', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'approved', NULL),
(21, 'sadu', 'abc@gmail.com', '0751078823', 'hgn', 'Repair', '2', 'fvcv', 2000.00, 1, 'profile_6851120cadc166.42378366.jpg', 'sip_6851120cadc228.79596465.pdf', 'approved', NULL),
(22, '', '', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'approved', NULL),
(23, '', '', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'approved', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','service_provider') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(4, 'Dilshan Wijesekara ', 'dilshan@gmail.com', '$2y$10$fu0UlOfxfRegj0HC5Cozy.gwoUict8XoEknPUIE50/PCYD5Df1VIe', 'service_provider', '2025-06-15 15:47:25'),
(5, 'Ruwan Jayasuriya ', 'ruwan@gmail.com', '$2y$10$hQ72VNZ9fEJIM2CfOPBS7eqYg6ApNwJInGA1SIxJ/1rPbMOEvlV46', 'service_provider', '2025-06-15 15:55:09'),
(6, 'Ashani Bandara ', 'ashani@gmail.com', '$2y$10$eTBcWXxq48U0aFUMEGkfY.9WwoFyOPWNuEs21D1na6AAnc1xURO2q', 'service_provider', '2025-06-15 15:57:11'),
(7, 'Ramya Silva ', 'ramya@gmail.com', '$2y$10$pqvWM8e.u9nnYRf1bWLqyu/F6xr.N/1iSlTbIDUuk4nCpbBIyYQi2', 'service_provider', '2025-06-15 15:59:48'),
(8, 'Chathura Senanayake ', 'chathura@gmail.com', '$2y$10$SjWK0T8eyHhWJCMJcFV9luHOYSox9MyQg5M5aG9PiyI7VvcE1QV5i', 'service_provider', '2025-06-15 16:04:01'),
(9, 'Sunil Madushanka ', 'sunil@gmail.com', '$2y$10$BICsGsQzB6qB9bk4Op6s8uhKREZgdYIzmgJEkMezKIeyAZCoFNawK', 'service_provider', '2025-06-15 16:09:42'),
(10, 'Isuru Nuwan ', 'isuru@gmail.com', '$2y$10$netNDOXccZcAlaOXVT2bwuTUZpdo12SJ40W0ks/ayHS7zsHAuiQYS', 'service_provider', '2025-06-15 16:12:45'),
(11, 'wasana sewwandi', 'wasana@gmail.com', '$2y$10$KMMhR729xBAyaYTaYYNPEO.a/U.oAOoEjVcOUk2BcbMZVUkvkd9Pa', 'customer', '2025-06-15 16:16:18'),
(12, 'sankalpani erath', 'sankalapani@gmail.com', '$2y$10$HImcU1NLa3Y9AtFMYwQMwO85za3xg7X3uF5cqDwWUMHL/0SzcnJli', 'customer', '2025-06-15 16:17:47'),
(14, 'amandi', 'amandi@mail.com', '$2y$10$5ZOoUFLRgxxwahQJqtquF.VJBOTynF9qKjZEDJ5mbSdYudBPBdqZK', 'customer', '2025-06-16 04:37:44'),
(16, 'samith', 'samith@gmail.com', '$2y$10$Gd3jOTvfjHuWSuJPZglJauh.UbVkBHWVbgGKUh68Y7w8IvNlIcLdO', 'customer', '2025-06-16 08:24:54'),
(17, 'mandira', 'mandira@gmail.com', '$2y$10$bsRgnsu6STAjpOvTa0LYf.vUSE5RiLR7ihXBfW8ezaL45uZzShxPm', 'service_provider', '2025-06-16 08:36:24'),
(18, 'madu', 'madu@gmail.com', '$2y$10$Ek7un7wqoVYF/cCVdN7JueHGmGRrfd1DXxmooeKLjEgeUldCx59oC', 'service_provider', '2025-06-16 10:44:24'),
(19, 'kasuni', 'k@gmail.com', '$2y$10$MUaV6lie6UGJ6bCy7RAjOetaJiB/nZGmlz7BE8UPl0Skqvdyr/Mg2', 'service_provider', '2025-06-17 06:51:46'),
(20, 'ammu', 'as@mail.com', '$2y$10$8dRTWkXdBRhCnQoVfEMezOr.NWdRkEIZeSznCQ/z/zyxXhywhJ4YK', 'service_provider', '2025-06-17 06:55:56'),
(21, 'abc', 'abc@gmail.com', '$2y$10$hKHMEOhOmlaMPNVIgcQSQ.loD34cx9VPqru4fWseYASOgrTGryW7O', 'service_provider', '2025-06-17 06:57:42'),
(22, 'sara', 'sara@gmail.com', '$2y$10$PAx9l17IMT6QI4PSD4iznuEac2kTB6AA5dznf8MHwgQUqdoIix/re', 'service_provider', '2025-06-17 07:09:00'),
(23, 'rawidu', 'rawidu@gmail.com', '$2y$10$t9OX27ZeO5R3TpHNgZV7Kez4/W8itN9hrGSjQhE9AmClcYu1s5zu.', 'service_provider', '2025-06-29 18:31:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_provider`
--
ALTER TABLE `service_provider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
