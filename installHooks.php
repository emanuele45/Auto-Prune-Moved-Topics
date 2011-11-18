<?php
/**
 * Auto Prune Moved Topics (apmt)
 *
 * @package apmt
 * @author emanuele
 * @copyright 2011 emanuele, Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 0.1.0
 */

if (!defined('SFM') && file_exists(dirname(__FILE__ . '/SSI.php')))
	require_once(dirname(__FILE__ . '/SSI.php'));
elseif (file_exists('./SSI.php'))
	require_once('./SSI.php');
else
	die('No SMF, no SSI, where do you want to go?');

$scheduledTaskFunctionName = 'apmt_prunetopics';
if (empty($context['uninstalling']))
{
	$integration_function = 'add_integration_function';
	$smcFunc['db_insert']('',
		'{db_prefix}scheduled_tasks',
		array('next_time' => 'int', 'time_offset' => 'int', 'time_regularity' => 'int', 'time_unit' => 'string-1', 'disabled' => 'int', 'task' => 'string'),
		array (0, 0, 15, 'm', 0, $scheduledTaskFunctionName),
		array('id_task')
	);
}
else
{
	$integration_function = 'remove_integration_function';
	$smcFunc['db_query']('', '
		DELETE FROM {db_prefix}scheduled_tasks
		WHERE task = {string:task_func}',
		array(
			'task_func' => $scheduledTaskFunctionName
	));
}

$integration_function('integrate_admin_include', '$sourcedir/Subs-AutoPruneMoved.php');
$integration_function('integrate_general_mod_settings', 'apmt_add_settings');

updateSettings(array(
	'apmt_taskFrequency' => 15,
	'apmt_numberOfBoards' => 5,
));

?>