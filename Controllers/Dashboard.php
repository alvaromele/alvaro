<?php 

// 	class Dashboard extends Controllers
// {

// 	public function __construct()
// 	{
// 		parent::__construct();

// 		// session_start();
// 		// session_regenerate_id(true);
// 		// if (empty($_SESSION['login'])) {
// 		// 	header('location: ' . base_url() . '/login');
// 		// }
// 		// getPermisos(MDASHBOARD);
// 	}

// 	public function dashboard()
// 	{
// 		$data['page_etiqueta'] = "Dashboard - Almacen";
// 		$data['page_titulo'] = "Dashboard - Almacen";
// 		$data["page_nombre"] = "dashboard";
// 		$data['page_funciones_js'] = "funciones_dashboard.js";
// 		$this->views->getView($this,"home",$data);
// 	}
// }

class Dashboard extends Controllers{
		public function __construct()
		{
			parent::__construct();
		}

		public function dashboard()
		{
			$data['page_etiqueta'] = "Dashboard";
			$data['page_titulo'] = "Dashboard";
			$data["page_nombre"] = "dashboard";
			$data['page_funciones_js'] = "funciones_dashboard.js";
			$this->views->getVista($this,"dashboard",$data);
		}
}
