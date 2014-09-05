<?

	class FlynsarmyPDFInvoice_Configuration extends Core_Configuration_Model
	{
		public $record_code = 'flynsarmypdfinvoice_configuration';

		public static function create()
		{
			$configObj = new self;
			return $configObj->load();
		}

		protected function build_form()
		{
			$this->add_field('custom_css', 'Custom CSS', 'full', db_text)->tab('Appearance')->size('giant')->cssClasses('code')->language('css')->renderAs(frm_code_editor)->saveCallback('save_code')->comment("Add custom CSS here to change the way your invoice looks.", 'above');
		}
	}

?>