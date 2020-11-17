-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 17, 2020 at 08:34 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `henry`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(4, 'godfrey_mar@yahoo.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(10) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `photo` text NOT NULL,
  `price` int(15) NOT NULL,
  `on_hand` int(15) NOT NULL,
  `demand` int(15) NOT NULL,
  `on_ordered` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `description`, `photo`, `price`, `on_hand`, `demand`, `on_ordered`) VALUES
(6, 'asdsad', 'hehehe', '18311.png', 2000, 15, 25, 10),
(7, 'casio', 'for girls', '5209.jpg', 5000, 4, 46, 50);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL,
  `order_date` text NOT NULL,
  `delivery_date` text NOT NULL,
  `supplier` text NOT NULL,
  `status` text NOT NULL,
  `data` text NOT NULL,
  `original_quantity` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `order_date`, `delivery_date`, `supplier`, `status`, `data`, `original_quantity`) VALUES
(18, '7 December, 2017', '8 December, 2017', 'ceilos', 'Printed', '64,10,asdsad', '100'),
(19, '8 December, 2017', '9 December, 2017', 'ceilos', 'Printed', '64,0,asdsad', '5'),
(20, '10 December, 2017', '10 December, 2017', 'ashdhasdk', 'Printed', '1,0,3907675_fpx', '8'),
(21, '10 December, 2017', '10 December, 2017', 'Godfrey', 'Printed', '6,10,Gshock blak', '50'),
(22, '10 December, 2017', '11 December, 2017', 'mar', 'Printed', '7,50,casio', '100');

-- --------------------------------------------------------

--
-- Table structure for table `sales_orders`
--

CREATE TABLE `sales_orders` (
  `id` int(11) NOT NULL,
  `datee` text NOT NULL,
  `customer` text NOT NULL,
  `num` int(15) NOT NULL,
  `total_price` int(15) NOT NULL,
  `datas` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales_orders`
--

INSERT INTO `sales_orders` (`id`, `datee`, `customer`, `num`, `total_price`, `datas`) VALUES
(14, '17 November, 2020', 'Sample user', 2, 7000, '6,1,7,1'),
(15, '17 November, 2020', 'James Reid', 1, 8000, '6,4');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(15) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `telephone` text NOT NULL,
  `photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `address`, `telephone`, `photo`) VALUES
(2, 'Godfrey', 'rosario', '1232132131', '18100.jpg'),
(3, 'mar', 'rosario1', '123213213', '30750.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `sales_orders`
--
ALTER TABLE `sales_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
