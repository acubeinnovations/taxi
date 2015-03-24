<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/

//________________fa constants____________
define('PT_MISC', 0);
define('PT_WORKORDER', 1);
define('PT_CUSTOMER', 2);
define('PT_SUPPLIER', 3);
define('PT_QUICKENTRY', 4);
define('PT_DIMESION', 5);
define('PT_DRIVER', 6);
define('PT_OWNER', 7);

//account types
define('CURRENT_ASTS',1);
define('INVENTORY_ASTS',2);
define('CAPITAL_ASTS',3);
define('CURRENT_LBTS',4);
define('LONG_TERM_LBTS',5);
define('SHARE_CAPITAL',6);
define('RETAIN_EARNINGS',7);
define('SALES_RVNE',8);
define('OTHER_RVNE',9);
define('COGS',10);
define('PAYROLL_EXPNS',11);
define('GA_EXPNS',12);

//___________________fa constants______________________________

//tour quotation templates
define('QUOTATION1','tour_quotation1');

$quotations = array(QUOTATION1 => 'Simple Quotation');

//payment types
define('CASH',1);
define('CREDIT',2);


define('PRODUCTION_MODE',1);
define('DEVELOPMENT_MODE',2);
define('PROJECT_MODE',DEVELOPMENT_MODE);

define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

define('PRODUCT_NAME','Taxi');

//user roles from user types table
define('SYSTEM_ADMINISTRATOR',1);
define('ORGANISATION_ADMINISTRATOR',2);
define('FRONT_DESK',3);
define('CUSTOMER',4);
define('DRIVER',5);
define('VEHICLE_OWNER',6);


define('SYSTEM_EMAIL','cnc@cnc.com');

define('USER_STATUS_ACTIVE',1);
define('USER_STATUS_SUSPENDED',2);
define('USER_STATUS_DISABLED',3);

define('STATUS_ACTIVE',1);
define('STATUS_INACTIVE',2);


define('CUSTOMER_REG_TYPE_PHONE_CALL',1);
define('CUSTOMER_REG_TYPE_APP',2);

define('TRIP_STATUS_PENDING',1);
define('TRIP_STATUS_CONFIRMED',2);
define('TRIP_STATUS_CANCELLED',3);
define('TRIP_STATUS_CUSTOMER_CANCELLED',4);
define('TRIP_STATUS_ON_TRIP',5);
define('TRIP_STATUS_TRIP_COMPLETED',6);
define('TRIP_STATUS_TRIP_PAYED',7);
define('TRIP_STATUS_TRIP_BILLED',8);

define('BEACON_LIGHT_RED',1);
define('BEACON_LIGHT_BLUE',2);

define('gINVALID',-1);

define('OWNED_VEHICLE',1);

//driver status
define('OWNED_DRIVER',1);
define('ATTACHED_DRIVER',2);
/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */
