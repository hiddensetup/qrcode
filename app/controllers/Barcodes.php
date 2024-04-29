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

class Barcodes extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        if(!settings()->codes->barcodes_is_enabled) {
            redirect('not-found');
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['project_id', 'type'], ['name', 'value'], ['last_datetime', 'name', 'datetime']));
        $filters->set_default_order_by('barcode_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `barcodes` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('barcodes?' . $filters->get_get() . '&page=%d')));

        /* Get the barcodes list for the user */
        $barcodes = [];
        $barcodes_result = database()->query("SELECT * FROM `barcodes` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
        while($row = $barcodes_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $barcodes[] = $row;
        }

        /* Export handler */
        process_export_csv($barcodes, 'include', ['barcode_id', 'user_id', 'project_id', 'type', 'name', 'value', 'last_datetime', 'datetime'], sprintf(l('barcodes.title')));
        process_export_json($barcodes, 'include', ['barcode_id', 'user_id', 'project_id', 'type', 'name', 'value', 'embedded_data', 'settings','last_datetime', 'datetime'], sprintf(l('barcodes.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Existing projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        $available_barcodes = require APP_PATH . 'includes/enabled_barcodes.php';

        /* Prepare the View */
        $data = [
            'barcodes'            => $barcodes,
            'total_barcodes'      => $total_rows,
            'pagination'          => $pagination,
            'filters'             => $filters,
            'projects'            => $projects,
            'available_barcodes'  => $available_barcodes,
        ];

        $view = new \Altum\View('barcodes/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function duplicate() {
        \Altum\Authentication::guard();

        if(!settings()->codes->barcodes_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.barcodes')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('barcodes');
        }

        if(empty($_POST)) {
            redirect('barcodes');
        }

        /* Make sure that the user didn't exceed the limit */
        $total_rows = db()->where('user_id', $this->user->user_id)->getValue('barcodes', 'COUNT(*)') ?? 0;
        if($this->user->plan_settings->barcodes_limit != -1 && $total_rows >= $this->user->plan_settings->barcodes_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('barcodes');
        }

        $barcode_id = (int) $_POST['barcode_id'];

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');
        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('barcodes');
        }

        /* Verify the main resource */
        if(!$barcode = db()->where('barcode_id', $barcode_id)->where('user_id', $this->user->user_id)->getOne('barcodes')) {
            redirect('barcodes');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Duplicate the files */
            $barcode_image = \Altum\Uploads::copy_uploaded_file($barcode->barcode, \Altum\Uploads::get_path('barcodes'), \Altum\Uploads::get_path('barcodes'));

            /* Insert to database */
            $barcode_id = db()->insert('barcodes', [
                'user_id' => $this->user->user_id,
                'project_id' => $barcode->project_id,
                'name' => $barcode->name . ' - ' . l('global.duplicated'),
                'type' => $barcode->type,
                'barcode' => $barcode_image,
                'settings' => $barcode->settings,
                'datetime' => \Altum\Date::$date,
            ]);

            /* Clear the cache */
            cache()->deleteItem('barcodes_total?user_id=' . $this->user->user_id);
            cache()->deleteItem('barcodes_dashboard?user_id=' . $this->user->user_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . input_clean($barcode->name) . '</strong>'));

            /* Redirect */
            redirect('barcode-update/' . $barcode_id);

        }

        redirect('barcodes');
    }

    public function bulk() {

        \Altum\Authentication::guard();

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('barcodes');
        }

        if(empty($_POST['selected'])) {
            redirect('barcodes');
        }

        if(!isset($_POST['type'])) {
            redirect('barcodes');
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    /* Team checks */
                    if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.barcodes')) {
                        Alerts::add_info(l('global.info_message.team_no_access'));
                        redirect('barcodes');
                    }

                    foreach($_POST['selected'] as $barcode_id) {
                        if($barcode = db()->where('barcode_id', $barcode_id)->where('user_id', $this->user->user_id)->getOne('barcodes', ['barcode'])) {
                            /* Delete the barcode */
                            (new Barcode())->delete($barcode_id);
                        }
                    }

                    break;

                case 'download':

                    $files = [];

                    foreach($_POST['selected'] as $barcode_id) {
                        if($barcode = db()->where('barcode_id', $barcode_id)->where('user_id', $this->user->user_id)->getOne('barcodes', ['barcode'])) {
                            $files[$barcode->barcode] = \Altum\Uploads::get_path('barcodes');
                        }
                    }

                    \Altum\Uploads::download_files_as_zip($files, l('global.download'));

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('barcodes');
    }

    public function delete() {
        \Altum\Authentication::guard();

        if(!settings()->codes->barcodes_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.barcodes')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('barcodes');
        }

        if(empty($_POST)) {
            redirect('barcodes');
        }

        $barcode_id = (int) query_clean($_POST['barcode_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('barcodes');
        }

        /* Make sure the vcard id is created by the logged in user */
        if(!$barcode = db()->where('barcode_id', $barcode_id)->where('user_id', $this->user->user_id)->getOne('barcodes', ['barcode_id', 'name'])) {
            redirect('barcodes');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            (new Barcode())->delete($barcode->barcode_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $barcode->name . '</strong>'));

            redirect('barcodes');

        }

        redirect('barcodes');
    }

}
