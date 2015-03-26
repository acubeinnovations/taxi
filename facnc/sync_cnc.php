<?php


	
	$path_to_root=".";

	$page_security = 'SA_OPEN';

	include_once("includes/session.inc");

	if(isset($_GET['NewBankPayment'])){

		meta_forward('gl/gl_bank.php','NewPayment=Yes');
	}
	elseif(isset($_GET['NewBankDeposit'])){

		meta_forward('gl/gl_bank.php','NewDeposit=Yes');
	}
	elseif(isset($_GET['BankTransfer'])){

		meta_forward('gl/bank_transfer.php');
	}
	elseif(isset($_GET['ReconcileBank'])){

		meta_forward('gl/bank_account_reconcile.php');
	}
	elseif(isset($_GET['CompanySetup'])){
		meta_forward('admin/company_preferences.php');
		
	}
	elseif(isset($_GET['DisplaySetup'])){
		meta_forward('admin/display_prefs.php');
		
	}
	elseif(isset($_GET['AccessSetup'])){
		meta_forward('admin/security_roles.php');
		
	}
	elseif(isset($_GET['FormSetup'])){
		meta_forward('admin/forms_setup.php');
		
	}
	elseif(isset($_GET['GlAccount'])){
		meta_forward('gl/manage/gl_accounts.php');
	}	
	elseif(isset($_GET['JournelEntry'])){
		meta_forward('gl/gl_journal.php','NewJournal=Yes');
	}
	elseif(isset($_GET['TaxType'])){
		meta_forward('taxes/tax_types.php');
		
	}
	elseif(isset($_GET['TaxGroups'])){
		meta_forward('taxes/tax_groups.php');
		
	}
	elseif(isset($_GET['PaymentTerms'])){
		meta_forward('admin/payment_terms.php');
		
	}
	elseif(isset($_GET['FiscalYear'])){
		meta_forward('admin/fiscalyears.php');
		
	}
	elseif(isset($_GET['SystemGl'])){
		meta_forward('admin/gl_setup.php');
		
	}
	elseif(isset($_GET['VoidTransaction'])){
		meta_forward('admin/void_transaction.php');
		
	}
	elseif(isset($_GET['Backup'])){
		meta_forward('admin/backups.php');
		
	}
	elseif(isset($_GET['NewDelivery'])){
		$param = 'NewDelivery='.$_GET['NewDelivery'];
		if(isset($_GET['TaxGroup'])){
			$param.= "&TaxGroup=".$_GET['TaxGroup'];
		}
		meta_forward('sales/sales_order_entry.php',$param);
		
	}elseif(isset($_GET['ModifyDelivery'])){

		$param = 'ModifyDelivery='.$_GET['ModifyDelivery'];
		if(isset($_GET['TaxGroup'])){
			$param.= "&TaxGroup=".$_GET['TaxGroup'];
		}
		meta_forward('sales/customer_delivery.php',$param);
		
	}
	elseif(isset($_GET['SalesDeliveries'])){
		meta_forward('sales/inquiry/sales_deliveries_view.php','OutstandingOnly=1');
		
	}
	elseif(isset($_GET['SupplierPayment'])){
		meta_forward('purchasing/supplier_payment.php','SupplierPayment='.$_GET['SupplierPayment']);
		
	}
	elseif(isset($_GET['DriverPaymentInquiry'])){
		meta_forward('purchasing/inquiry/supplier_inquiry.php','DriverPaymentInquiry='.$_GET['DriverPaymentInquiry']);
		
	}
	elseif(isset($_GET['OwnerPaymentInquiry'])){
		meta_forward('purchasing/inquiry/supplier_inquiry.php','OwnerPaymentInquiry='.$_GET['DriverPaymentInquiry']);
		
	}
	elseif(isset($_GET['CustomerPayment'])){
		meta_forward('sales/customer_payments.php','CustomerPayment='.$_GET['CustomerPayment']);
		
	}
	elseif(isset($_GET['CustomerTripAdvance'])){
		meta_forward('sales/customer_payments.php','CustomerTripAdvance='.$_GET['CustomerTripAdvance']);
		
	}
	elseif(isset($_GET['CustomerPaymentInquiry'])){
		meta_forward('sales/inquiry/customer_inquiry.php','CustomerPaymentInquiry='.$_GET['CustomerPaymentInquiry']);
		
	}
	elseif(isset($_GET['NewInvoice'])){
		meta_forward('sales/sales_order_entry.php','NewInvoice=0');
		
	}
	elseif(isset($_GET['CustomerTransactions'])){
		meta_forward('sales/inquiry/customer_inquiry.php');
		
	}
	elseif(isset($_GET['DriverTransactions'])){
		meta_forward('purchasing/inquiry/supplier_inquiry.php','DriverTransactions=Yes');
		
	}
	elseif(isset($_GET['OwnerTransactions'])){
		meta_forward('purchasing/inquiry/supplier_inquiry.php','OwnerTransactions=Yes');
		
	}
	elseif(isset($_GET['SalesInvoices'])){
		meta_forward('sales/inquiry/customer_inquiry.php','SalesInvoices=Yes');
		
	}
	elseif(isset($_GET['BankAccounts'])){
		meta_forward('gl/manage/bank_accounts.php');
		
	}
	elseif(isset($_GET['GlAccounts'])){
		meta_forward('gl/manage/gl_accounts.php');
		
	}
	elseif(isset($_GET['GlAccountGroups'])){
		meta_forward('gl/manage/gl_account_types.php');
		
	}
	elseif(isset($_GET['GlAccountClasses'])){
		meta_forward('gl/manage/gl_account_classes.php');
		
	}
	elseif(isset($_GET['TrialBalance'])){
		meta_forward('gl/inquiry/gl_trial_balance.php');
		
	}
	elseif(isset($_GET['BalanceSheet'])){
		meta_forward('gl/inquiry/balance_sheet.php');
		
	}
	elseif(isset($_GET['ProfitLoss'])){
		meta_forward('gl/inquiry/profit_loss.php');
		
	}



	
	
	
	


	
				

?>
