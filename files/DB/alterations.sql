
----------------updated in standard,suburban,ostrich,keralapremium,travelERP_version2 and arc--------------

--table vehicles--
ALTER TABLE `vehicles`  ADD `vehicle_percentage` INT(11) NOT NULL AFTER `tax_renewal_date`,  ADD `driver_percentage` INT(11) NOT NULL AFTER `vehicle_percentage`;

--trip expense--
ALTER TABLE `trip_vouchers`  ADD `trip_expense` TEXT NOT NULL AFTER `vehicle_trip_amount`;


--supplier group table
CREATE TABLE IF NOT EXISTS `supplier_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `value` int(11) DEFAULT NULL,
  `organisation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `organisation_id` (`organisation_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

--supplier group id in vehicles
ALTER TABLE `vehicles`  ADD `supplier_group_id` INT(11) NOT NULL AFTER `vehicle_owner_id`;


--Company setting for accounts need following changes for company id '1'--
--table prefix change with company id--
--For creating a new company , do not update the followings--
INSERT INTO `1_chart_master` (`account_code`, `account_code2`, `account_name`, `account_type`, `inactive`) VALUES
('2040', '', 'Driver Bata', '4', 0),('2041', '', 'Night Halt', '4', 0);
INSERT INTO `1_sys_prefs` (`name`, `category`, `type`, `length`, `value`) VALUES
('default_driver_bata_act', 'glsetup.items', 'varchar', 15, '2040'),('default_night_halt_act', 'glsetup.items', 'varchar', 15, '2041');


--****need to be updated 24/02/2015********

INSERT INTO `1_sys_prefs` (`name`, `category`, `type`, `length`, `value`) VALUES
('invoice_template', 'setup.company', 'int', 11, '107');



INSERT INTO `1_sys_prefs` (`name`, `category`, `type`, `length`, `value`) VALUES
('tax_no', 'setup.company', 'varchar', 25, ''),
('pan_no', 'setup.company', 'varchar', 25, '');





ALTER TABLE `trip_vouchers` CHANGE `driver_payment_percentage` `driver_payment_percentage` INT(11) NOT NULL;
ALTER TABLE `trip_vouchers` CHANGE `vehicle_payment_percentage` `vehicle_payment_percentage` INT(11) NOT NULL;

ALTER TABLE `organisations`  ADD `quotation_template` VARCHAR(25) NOT NULL;


--****need to be updated 24/02/2015********
ALTER TABLE `drivers`  ADD `driver_status_id` INT(11) NOT NULL AFTER `minimum_working_days`






