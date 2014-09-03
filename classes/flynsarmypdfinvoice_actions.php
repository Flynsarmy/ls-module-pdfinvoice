<?php
	/**
	 * @has_documentable_methods
	 */
	class FlynsarmyPDFInvoice_Actions extends Cms_ActionScope
	{
		public function generate_invoice_pdf()
		{
			$order_id = intval($this->request_param(0));
			$order = Shop_Order::create()->find( $order_id );

			// This should never happen but check anyway
			if ( !$order )
				return;

			$admin_user = Phpr::$security->getUser();
			$frontend_user = Phpr::$frontend_security->getUser();
			// If not admin and not owner of the order, permission denied
			if ( !$admin_user && (!$frontend_user || $frontend_user->id != $order->customer_id) )
				return;

			$dompdf = FlynsarmyPDFInvoice_Invoice_Helper::generate( $order );
			$dompdf->stream('invoice_'.$order_id.'.pdf', array('Attachment' => 1));
			exit;
		}
	}