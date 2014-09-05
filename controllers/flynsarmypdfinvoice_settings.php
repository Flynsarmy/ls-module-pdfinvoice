<?

	class FlynsarmyPDFInvoice_Settings extends Backend_SettingsController
	{
		public $app_tab = 'system';
		public $app_page = 'settings';
		public $app_module_name = 'System';

		protected $access_for_groups = array(Users_Groups::admin);
		public $implement = 'Db_FormBehavior';

		public $form_model_class = 'FlynsarmyPDFInvoice_Configuration';
		public $form_redirect = null;

		public function __construct()
		{
			parent::__construct();
		}

		public function index()
		{
			$this->app_page_title = 'PDF Invoice Settings';

			try
			{
				$obj = new $this->form_model_class;
				$this->viewData['form_model'] = $obj->load();
			}
			catch (exception $ex)
			{
				$this->_controller->handlePageError($ex);
			}
		}

		protected function index_onSave()
		{
			try
			{
				$obj = new $this->form_model_class;
				$obj = $obj->load();

				$obj->save(post($this->form_model_class, array()), $this->formGetEditSessionKey());

				echo Backend_Html::flash_message('Configuration saved.');
			}
			catch (Exception $ex)
			{
				Phpr::$response->ajaxReportException($ex, true, true);
			}
		}
	}

?>