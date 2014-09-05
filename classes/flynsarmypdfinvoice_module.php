<?php

class FlynsarmyPDFInvoice_Module extends Core_ModuleBase
{

	/**
	 * Creates the module information object
	 * @return Core_ModuleInfo
	 */
	protected function createModuleInfo()
	{
		return new Core_ModuleInfo(
			"PDF Invoices",
			"Generate Invoice PDFs to send to customers",
			"Flynsarmy"
		);
	}

	public function listSettingsItems()
	{
		return array(
			array(
				'icon'=>'/modules/flynsarmypdfinvoice/resources/images/file-pdf-o_f90605_48.png',
				'title'=>'PDF Invoices',
				'url'=>'/flynsarmypdfinvoice/settings',
				'description'=>'Customize your PDF invoices.',
				'sort_id'=>30,
				'section'=>'eCommerce',
			),
		);
	}

	// public function subscribeEvents()
	// {
	// 	Backend::$events->addEvent('core:onBeforeEmailSendToCustomer', $this, 'before_email_send_to_customer');
	// }

	// function before_email_send_to_customer($customer, $subject, $message_text, $customer_email, $customer_name, $custom_data, $reply_to, $email_template)
	// {

	// }
}
?>