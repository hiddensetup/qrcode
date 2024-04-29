<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class Link extends Model {

    public function get_link_full_url($link, $user, $domains = null) {

        /* Detect the URL of the link */
        if($link->domain_id) {

            /* Get available custom domains */
            if(!$domains) {
                $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($user);
            }

            if(isset($domains[$link->domain_id])) {
                $link->full_url = $domains[$link->domain_id]->scheme . $domains[$link->domain_id]->host . '/' . $link->url . '/';
            }

        } else {

            $link->full_url = SITE_URL . $link->url . '/';

        }

        return $link->full_url;
    }

    public function get_full_links_by_user_id($user_id) {

        /* Get the user links */
        $links = [];

        /* Try to check if the user posts exists via the cache */
        $cache_instance = cache()->getItem('links?user_id=' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $links_result = database()->query("SELECT `links`.*, `domains`.`scheme`, `domains`.`host` FROM `links` LEFT JOIN `domains` ON `links`.`domain_id` = `domains`.`domain_id` WHERE `links`.`user_id` = {$user_id}");
            while($row = $links_result->fetch_object()) {
                $row->full_url = $row->domain_id ? $row->scheme . $row->host . '/' . $row->url : SITE_URL . $row->url;
                $links[$row->link_id] = $row;
            }

            cache()->save(
                $cache_instance->set($links)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('user_id=' . $user_id)
            );

        } else {

            /* Get cache */
            $links = $cache_instance->get();

        }

        return $links;

    }

    public function delete($link_id) {

        $link = db()->where('link_id', $link_id)->getOne('links', ['link_id', 'user_id']);

        if(!$link) return;

        /* Delete the link */
        db()->where('link_id', $link_id)->delete('links');

        /* Clear cache */
        cache()->deleteItemsByTag('link_id=' . $link_id);
        cache()->deleteItem('links?user_id=' . $link->user_id);
        cache()->deleteItem('links_total?user_id=' . $link->user_id);

    }

}
