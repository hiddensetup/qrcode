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

class AdminBroadcastUpdate extends Controller {

    public function index() {

        $broadcast_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$broadcast = db()->where('broadcast_id', $broadcast_id)->getOne('broadcasts')) {
            redirect('admin/broadcasts');
        }

        if($broadcast->status == 'processing') {
            Alerts::add_error(l('admin_broadcast_update.error_message.processing'));
            redirect('admin/broadcasts');
        }

        $broadcast->settings = json_decode($broadcast->settings ?? '');
        $broadcast->users_ids = implode(',', json_decode($broadcast->users_ids));

        $plans = (new \Altum\Models\Plan())->get_plans();

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['subject'] = input_clean($_POST['subject'], 128);
            $_POST['segment'] = in_array($_POST['segment'], ['all', 'subscribers', 'custom', 'filter']) ? input_clean($_POST['segment']) : 'subscribers';
            $_POST['is_system_email'] = (int) isset($_POST['is_system_email']);

            $_POST['users_ids'] = trim($_POST['users_ids'] ?? '');
            if($_POST['users_ids']) {
                $_POST['users_ids'] = explode(',', $_POST['users_ids'] ?? '');
                if(count($_POST['users_ids'])) {
                    $_POST['users_ids'] = array_map(function ($user_id) {
                        return (int) $user_id;
                    }, $_POST['users_ids']);
                    $_POST['users_ids'] = array_unique($_POST['users_ids']);
                }
            }

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Preview email */
            if(isset($_POST['preview'])) {
                $_POST['preview_email'] = mb_substr(filter_var($_POST['preview_email'], FILTER_SANITIZE_EMAIL), 0, 320);

                $required_fields = ['subject', 'content', 'preview_email'];
                foreach($required_fields as $field) {
                    if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                        Alerts::add_field_error($field, l('global.error_message.empty_field'));
                    }
                }

                if(filter_var($_POST['preview_email'], FILTER_VALIDATE_EMAIL) == false) {
                    Alerts::add_field_error('preview_email', l('global.error_message.invalid_email'));
                }
            }

            /* Save draft or send */
            else {
                $required_fields = ['name', 'subject', 'content'];
                foreach($required_fields as $field) {
                    if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                        Alerts::add_field_error($field, l('global.error_message.empty_field'));
                    }
                }
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Preview email */
                if(isset($_POST['preview'])) {
                    $email_template = get_email_template(
                        [
                            '{{NAME}}' => $this->user->name,
                            '{{EMAIL}}' => $this->user->email,
                        ],
                        $_POST['subject'],
                        [
                            '{{NAME}}' => $this->user->name,
                            '{{EMAIL}}' => $this->user->email,
                        ],
                        convert_editorjs_json_to_html($_POST['content'])
                    );

                    send_mail($_POST['preview_email'], $email_template->subject, $email_template->body, ['is_broadcast' => true, 'is_system_email' => $_POST['is_system_email'], 'anti_phishing_code' => $this->user->anti_phishing_code, 'language' => $this->user->language], $_POST['preview_email']);

                    /* Set a nice success message */
                    Alerts::add_success(sprintf(l('admin_broadcast_create.success_message.preview'), '<strong>' . $_POST['preview_email'] . '</strong>'));
                }

                if(isset($_POST['save']) || isset($_POST['send'])) {
                    $settings = [
                        'is_system_email' => $_POST['is_system_email'],
                    ];

                    /* Get all the users needed */
                    switch($_POST['segment']) {
                        case 'all':
                            $users = db()->get('users', null, ['user_id']);
                            break;

                        case 'subscribers':
                            $users = db()->where('is_newsletter_subscribed', 1)->get('users', null, ['user_id']);
                            break;

                        case 'custom':
                            $users = db()->where('user_id', $_POST['users_ids'], 'IN')->get('users', null, ['user_id']);
                            break;

                        case 'filter':

                            $query = db();

                            $has_filters = false;

                            /* Is subscribed */
                            $_POST['filters_is_newsletter_subscribed'] = isset($_POST['filters_is_newsletter_subscribed']) ? (bool) $_POST['filters_is_newsletter_subscribed'] : 0;

                            if($_POST['filters_is_newsletter_subscribed']) {
                                $has_filters = true;
                                $query->where('is_newsletter_subscribed', 1);
                                $settings['filters_is_newsletter_subscribed'] = (int) $_POST['filters_is_newsletter_subscribed'];
                            }

                            /* Plans */
                            if(isset($_POST['filters_plans'])) {
                                $has_filters = true;
                                $query->where('plan_id', $_POST['filters_plans'], 'IN');
                                $settings['filters_plans'] = $_POST['filters_plans'];
                            }

                            /* Status */
                            if(isset($_POST['filters_status'])) {
                                $has_filters = true;
                                $query->where('status', $_POST['filters_status'], 'IN');
                                $settings['filters_status'] = $_POST['filters_status'];
                            }

                            /* Countries */
                            if(isset($_POST['filters_countries'])) {
                                $has_filters = true;
                                $query->where('country', $_POST['filters_countries'], 'IN');
                                $settings['filters_countries'] = $_POST['filters_countries'];
                            }

                            /* Continents */
                            if(isset($_POST['filters_continents'])) {
                                $has_filters = true;
                                $query->where('continent_code', $_POST['filters_continents'], 'IN');
                                $settings['filters_continents'] = $_POST['filters_continents'];
                            }

                            /* Source */
                            if(isset($_POST['filters_source'])) {
                                $has_filters = true;
                                $query->where('source', $_POST['filters_source'], 'IN');
                                $settings['filters_source'] = $_POST['filters_source'];
                            }

                            $users = $has_filters ? $query->get('users', null, ['user_id']) : [];

                            break;

                    }

                    $users_ids = [];
                    foreach($users as $user) {
                        $users_ids[] = $user->user_id;
                    }

                    if($broadcast->status == 'sent') {
                        /* Database query */
                        db()->where('broadcast_id', $broadcast->broadcast_id)->update('broadcasts', [
                            'name' => $_POST['name'],
                            'last_datetime' => \Altum\Date::$date,
                        ]);
                    }

                    else {
                        /* Database query */
                        db()->where('broadcast_id', $broadcast->broadcast_id)->update('broadcasts', [
                            'name' => $_POST['name'],
                            'subject' => $_POST['subject'],
                            'content' => $_POST['content'],
                            'segment' => $_POST['segment'],
                            'settings' => json_encode($settings),
                            'users_ids' => json_encode($users_ids),
                            'total_emails' => count($users_ids),
                            'status' => isset($_POST['save']) ? 'draft' : 'processing',
                            'last_datetime' => \Altum\Date::$date,
                        ]);
                    }


                    if(isset($_POST['save'])) {
                        /* Set a nice success message */
                        Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));
                    } else {
                        /* Set a nice success message */
                        Alerts::add_success(sprintf(l('admin_broadcast_create.success_message.send'), '<strong>' . $_POST['name'] . '</strong>'));

                        redirect('admin/broadcasts');
                    }

                }

                /* Refresh the page */
                redirect('admin/broadcast-update/' . $broadcast_id);

            }

        }

        /* Main View */
        $data = [
            'broadcast_id' => $broadcast_id,
            'broadcast' => $broadcast,
            'plans' => $plans,
        ];

        $view = new \Altum\View('admin/broadcast-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
