<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Models\Barcode;

class AdminBarcodes extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id', 'type'], ['name', 'value'], ['last_datetime', 'name', 'datetime']));
        $filters->set_default_order_by('barcode_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `barcodes` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/barcodes?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $barcodes = [];
        $barcodes_result = database()->query("
            SELECT
                `barcodes`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `barcodes`
            LEFT JOIN
                `users` ON `barcodes`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('barcodes')}
                {$filters->get_sql_order_by('barcodes')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $barcodes_result->fetch_object()) {
            $barcodes[] = $row;
        }

        /* Export handler */
        process_export_csv($barcodes, 'include', ['barcode_id', 'user_id', 'project_id', 'type', 'name', 'value', 'last_datetime', 'datetime'], sprintf(l('admin_barcodes.title')));
        process_export_json($barcodes, 'include', ['barcode_id', 'user_id', 'project_id', 'type', 'name', 'value', 'embedded_data', 'settings','last_datetime', 'datetime'], sprintf(l('admin_barcodes.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        $available_barcodes = require APP_PATH . 'includes/barcodes.php';

        /* Main View */
        $data = [
            'barcodes' => $barcodes,
            'filters' => $filters,
            'pagination' => $pagination,
            'available_barcodes' => $available_barcodes,
        ];

        $view = new \Altum\View('admin/barcodes/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/barcodes');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/barcodes');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/barcodes');
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $barcode_id) {
                        /* Delete the barcode */
                        (new Barcode())->delete($barcode_id);
                    }

                    break;

                case 'download':

                    $files = [];

                    foreach($_POST['selected'] as $barcode_id) {
                        if($barcode = db()->where('barcode_id', $barcode_id)->getOne('barcodes', ['barcode'])) {
                            $files[$barcode->barcode] = \Altum\Uploads::get_path('barcodes');
                        }
                    }

                    \Altum\Uploads::download_files_as_zip($files, l('global.download'));

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/barcodes');
    }

    public function delete() {

        $barcode_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$barcode = db()->where('barcode_id', $barcode_id)->getOne('barcodes', ['barcode_id', 'name'])) {
            redirect('admin/barcodes');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the barcode */
            (new Barcode())->delete($barcode->barcode_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $barcode->name . '</strong>'));

        }

        redirect('admin/barcodes');
    }

    public function transfer() {

        if(empty($_POST)) {
            redirect('admin/barcodes');
        }

        $barcode_id = (int) $_POST['barcode_id'];
        $_POST['email'] = mb_substr(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), 0, 320);

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$barcode = db()->where('barcode_id', $barcode_id)->getOne('barcodes', ['barcode_id', 'user_id', 'name'])) {
            redirect('admin/barcodes');
        }

        if(!$current_user = db()->where('user_id', $barcode->user_id)->getOne('users', ['user_id', 'email'])) {
            redirect('admin/barcodes');
        }

        if(!$new_user = db()->where('email', $_POST['email'])->getOne('users', ['user_id', 'email'])) {
            redirect('admin/barcodes');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Update the database */
            db()->where('barcode_id', $barcode->barcode_id)->update('barcodes', [
                'user_id' => $new_user->user_id,
            ]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('transfer_modal.success_message'), '<strong>' . input_clean($barcode->name) . '</strong>', '<strong>' . input_clean($current_user->email) . '</strong>', '<strong>' . input_clean($new_user->email) . '</strong>'));

            /* Redirect */
            redirect('admin/barcodes');

        }

        redirect('admin/barcodes');
    }

}
