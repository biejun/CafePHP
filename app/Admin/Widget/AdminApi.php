<?php

namespace App\Admin\Widget
{
	use Coffee\Foundation\Widget;

	class AdminApi extends Widget
	{

		public function hello($tt)
		{
			echo $tt;
		}
	}
}