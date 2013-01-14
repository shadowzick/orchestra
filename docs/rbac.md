# Role Based Access Control

Orchestra Platform Role Based Access Control is powered  by Hybrid Bundle ACL classes. It gives you the ability to create custom ACL metrics which is unique to each of your extensions. In most other solutions, you are either restrict to file based configuration for ACL or only allow to define a single metric for your entire application.

This simplicity would later become an issue depends on how many extensions do you have within your application.

## Create a new ACL Metric.

	// Imagine we have a foo extension.
    Orchestra\Acl::make('foo')->attach(Orchestra\Core::memory());

Above configuration is all you need in extensions' start file.

## Define actions for a metric.

Since an ACL metric is defined for each extension, it is best to define ACL actions using a migration file.

	<?php
	
	class Foo_Define_Acl {
		
		public function __construct()
		{
			if ( ! Orchestra\Installer::status())
			{
				throw new Exception(
					"Orchestra Platform is not installed."
				);
			}
		}

		public function up()
		{
			$role    = Orchestra\Model\Role::admin();
			$acl     = Orchestra\Acl::make('foo');
			$actions = array(
				'manage foobar',
				'view foobar',
			);

			$acl->add_action($actions);
			$acl->allow($role->name, $actions);
		}

		public function down()
		{
			// nothing to do here.
		}
	
	}