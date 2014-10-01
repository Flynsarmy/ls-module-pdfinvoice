<?php
	class FlynsarmyPDFInvoice_Invoice_Helper
	{
		/**
		 * Generates a PDF invoice for a given order ID.
		 *
		 * @param  Shop_Order  $order Order to generate invoice for
		 * @param  string $save_dir   Output directory. Leave blank to output to stdout
		 *
		 * @return DOMPDF             PDF object
		 */
		public static function generate( Shop_Order $order, $save_dir='' )
		{
			// Use temp dir if no save dir supplied
			if ( empty($save_dir) )
				$save_dir = PATH_APP.'/temp';
			// Create save dir if it doesn't already exist
			if ( !is_dir($save_dir) )
				mkdir($save_dir);

			// Taken from shop_orders::invoice()
			$company_info = Shop_CompanyInformation::get();
			$invoice_info = $company_info->get_invoice_template();
			$template_id = isset($invoice_info['template_id']) ? $invoice_info['template_id'] : null;
			// $invoice_template_css = isset($invoice_info['css']) ? $invoice_info['css'] : array();
			$display_due_date = strlen($company_info->invoice_due_date_interval);

			// Taken from invoice.htm partial for above controller method
			$invoice_date = $order->get_invoice_date();

			$has_bundles = false;
			foreach ($order->items as $item)
			{
				if ($item->bundle_master_order_item_id) {
					$has_bundles = true;
					break;
				}
			}

			$controller = new Phpr_Controller();
			ob_start();
			$controller->render_partial(dirname(__FILE__).'/../partials/invoice.htm', array(
				'template_id' => $template_id,
				'order_id'=>$order->id,
				'order'=>$order,
				'invoice_date'=>$invoice_date,
				'company_info' => $company_info,
				'display_due_date' => $display_due_date,
				'due_date'=>$company_info->get_invoice_due_date($invoice_date),
				'display_tax_included'=>Shop_CheckoutData::display_prices_incl_tax($order),
				'has_bundles'=>$has_bundles,

				'custom_css' => FlynsarmyPDFInvoice_Configuration::create()->custom_css,
			));
			$html = ob_get_clean();

			// Replace relative URLs with absolute so DomPDF can remotely grab them
			$html = preg_replace("/(href|src)=(['\"])\//", "$1=$2".root_url('', true), $html);

			// Required for asset downloads
			if ( !defined('DOMPDF_ENABLE_REMOTE') ) define('DOMPDF_ENABLE_REMOTE', true);
			if ( !defined('DOMPDF_ENABLE_AUTOLOAD') ) define('DOMPDF_ENABLE_AUTOLOAD', false);

			require_once dirname(__FILE__)."/../vendor/autoload.php";
			require_once(dirname(__FILE__).'/../vendor/dompdf/dompdf/dompdf_config.inc.php');
			require_once(dirname(__FILE__).'/../vendor/dompdf/dompdf/include/autoload.inc.php');
			require_once(dirname(__FILE__).'/../vendor/phenx/php-font-lib/classes/Font.php');

			$dompdf = new DOMPDF();
			$dompdf->load_html( $html );
			$dompdf->set_paper('a4', "portrait");
			$dompdf->set_base_path(PATH_APP);
			$dompdf->render();

			return $dompdf;

			$dompdf->stream('test.pdf', array('Attachment' => 0));
			exit;
		}
	}