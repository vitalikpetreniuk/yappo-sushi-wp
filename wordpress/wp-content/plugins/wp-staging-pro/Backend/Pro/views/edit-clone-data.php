<?php

/**
 * @see WPStaging\Backend\Administrator::ajaxEditCloneData
 *
 * @var object $clone
 */

use WPStaging\Framework\Facades\Escape;
use WPStaging\Framework\Facades\Sanitize;

if (!defined("WPINC")) {
    die();
}

$cloneName = isset($_POST["clone"]) ? Sanitize::sanitizeString($_POST["clone"]) : '';

?>
<input type="hidden" id="wpstg-edit-clone-data-clone-id" name="wpstg-clone-id" value="<?php echo esc_attr($cloneName); ?>">
<div class="wpstg-form-horizontal wpstg-clone">
    <div>
        <h3><?php esc_html_e('Edit Clone Data', 'wp-staging');?></h3>
        <?php echo sprintf(
            Escape::escapeHtml(__('Update the values below only if you moved your staging site to another server and WP STAGING lost connection to the clone site. Don\'t update these values if you are unsure. This can break the pushing capability. <a href="%s" target="_blank">Read More</a>.', 'wp-staging')),
            'https://wp-staging.com/docs/reconnect-staging-site-to-production-website/'
        ); ?>
    </div>
    &nbsp;
    <div class="wpstg-form-row">
        <label id="wpstg-edit-clone-data-clone-name-label" for="wpstg-edit-clone-data-clone-name">
            <?php esc_html_e("Site Name", "wp-staging"); ?>
        </label>
        <input type="text" id="wpstg-edit-clone-data-clone-name" name="wpstg-clone-name" value="<?php
        echo isset($clone['cloneName']) ? esc_html($clone['cloneName']) : esc_html($clone['directoryName']) ?>">
    </div>
    <div class="wpstg-form-row">
        <label id="wpstg-edit-clone-data-directory-name-label" for="wpstg-edit-clone-data-directory-name">
            <?php esc_html_e("Subdirectory Name", "wp-staging"); ?>
        </label>
        <input type="text" id="wpstg-edit-clone-data-directory-name" name="wpstg-directory-name" value="<?php
        echo esc_html($clone['directoryName']) ?>">
    </div>
    <div class="wpstg-form-row">
        <label id="wpstg-edit-clone-data-path-label" for="wpstg-edit-clone-data-path">
            <?php
            esc_html_e("Target Directory", "wp-staging"); ?>
        </label>
        <input type="text" id="wpstg-edit-clone-data-path" name="wpstg-path" value="<?php
        echo esc_html($clone['path']) ?>">
    </div>
    <div class="wpstg-form-row">
        <label id="wpstg-edit-clone-data-url-label" for="wpstg-edit-clone-data-url">
            <?php esc_html_e("Target Hostname", "wp-staging"); ?>
        </label>
        <input type="text" id="wpstg-edit-clone-data-url" name="wpstg-url" value="<?php
        echo esc_html($clone['url']) ?>">
    </div>
    <div class="wpstg-form-row">
        <label id="wpstg-edit-clone-data-prefix-label" for="wpstg-edit-clone-data-prefix">
            <?php esc_html_e("Database Table Prefix", "wp-staging"); ?>
            <br>
            <?php esc_html_e("(Used if site uses live database)", "wp-staging"); ?>
        </label>
        <input type="text" id="wpstg-edit-clone-data-prefix" name="wpstg-prefix" value="<?php
        echo esc_html($clone['prefix']) ?>">
    </div>

    <h3><?php esc_html_e('External Database Access Data', 'wp-staging'); ?></h3>
    <div class="wpstg-form-row">
        <?php esc_html_e("The values below are used when the staging site is created in an external,", "wp-staging"); ?>
        <br />
        <?php esc_html_e("separate database and not in the same one as the live site.", "wp-staging") ?>
    </div>
    &nbsp;
    <div class="wpstg-form-row">
        <label id="wpstg-edit-clone-data-database-user-label" for="wpstg-edit-clone-data-database-user">
            <?php esc_html_e("Database User", "wp-staging"); ?>
        </label>
        <input type="text" class="wpstg-edit-clone-db-inputs" id="wpstg-edit-clone-data-database-user" name="wpstg-database-user" value="<?php
        echo esc_html($clone['databaseUser']) ?>">
    </div>
    <div class="wpstg-form-row">
        <label id="wpstg-edit-clone-data-database-password-label" for="wpstg-edit-clone-data-database-password">
            <?php esc_html_e("Database Password", "wp-staging"); ?>
        </label>
        <input type="password" class="wpstg-edit-clone-db-inputs" id="wpstg-edit-clone-data-database-password" name="wpstg-database-password" value="<?php
        echo esc_html($clone['databasePassword']) ?>">
    </div>
    <div class="wpstg-form-row">
        <label id="wpstg-edit-clone-data-database-database-label" for="wpstg-edit-clone-data-database-database">
            <?php esc_html_e("Database Name", "wp-staging"); ?>
        </label>
        <input type="text" class="wpstg-edit-clone-db-inputs" id="wpstg-edit-clone-data-database-database" name="wpstg-database-database" value="<?php
        echo esc_html($clone['databaseDatabase']) ?>">
    </div>
    <div class="wpstg-form-row">
        <label id="wpstg-edit-clone-data-database-server-label" for="wpstg-edit-clone-data-database-server">
            <?php esc_html_e("Database Hostname", "wp-staging"); ?>
        </label>
        <input type="text" class="wpstg-edit-clone-db-inputs" id="wpstg-edit-clone-data-database-server" name="wpstg-database-server" value="<?php
        echo esc_html($clone['databaseServer']) ?>">
    </div>
    <div class="wpstg-form-row">
        <label id="wpstg-edit-clone-data-database-prefix-label" for="wpstg-edit-clone-data-database-prefix">
            <?php esc_html_e("Database Table Prefix", "wp-staging"); ?>
        </label>
        <input type="text" class="wpstg-edit-clone-db-inputs" id="wpstg-edit-clone-data-database-prefix" name="wpstg-database-prefix" value="<?php
        echo esc_html($clone['databasePrefix']) ?>">
    </div>
    <div class="wpstg-form-row">
        <label id="wpstg-edit-clone-data-database-ssl-label" for="wpstg-edit-clone-data-database-ssl">
            <?php esc_html_e("Database Use SSL", "wp-staging"); ?>
        </label>
        <input type="checkbox" id="wpstg-edit-clone-data-database-ssl" name="wpstg-database-ssl" value="true" class="wpstg-checkbox" <?php echo !empty($clone['databaseSsl']) ? "checked" : '';?>>
    </div>
</div>
<div id="wpstg-db-connect-output"></div>
<p></p>
<button type="button" class="wpstg-prev-step-link wpstg-button--primary">
    <?php
    esc_html_e("Back", "wp-staging") ?>
</button>
<button type="button" id="wpstg-save-clone-data" class="wpstg-button--primary wpstg-button--blue">
    <?php esc_html_e('Save Clone Data', 'wp-staging'); ?>
</button>
<p></p>
