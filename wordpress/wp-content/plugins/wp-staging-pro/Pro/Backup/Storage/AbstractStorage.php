<?php

namespace WPStaging\Pro\Backup\Storage;

use Exception;
use WPStaging\Framework\Security\DataEncryption;
use WPStaging\Vendor\GuzzleHttp\Client as GuzzleClient;

use function WPStaging\functions\debug_log;

abstract class AbstractStorage
{
    /** @var string */
    protected $identifier;

    /** @var string */
    protected $label;

    /** @var string */
    protected $error;

    abstract public function authenticate();
    abstract public function testConnection();
    abstract public function revoke();
    abstract public function cleanBackups();
    abstract public function getFiles();
    abstract public function updateSettings($settings);

    /**
     * Check if the storage is authenticated or not
     *
     * @return bool Returns true if the storage is authenticated, false otherwise
     */
    public function isAuthenticated()
    {
        $options = $this->getOptions();
        if (isset($options['isAuthenticated'])) {
            return $options['isAuthenticated'];
        }
        return false;
    }

    public function getOptions()
    {
        $optionName  = $this->getOptionName();
        $optionValue = get_option($optionName, []);
        return $this->decryptCredential($optionName, $optionValue);
    }

    private function getOptionName()
    {
        return 'wpstg_' . $this->identifier;
    }

    /**
     * Save the storage configuration
     *
     * @param $options
     *
     * @return bool Returns true if the value was updated, false otherwise
     */
    public function saveOptions($options = [])
    {
        $optionName  = $this->getOptionName();
        $optionValue = $options;

        if (apply_filters('wpstg.framework.security.dataEncryption', true)) {
            $optionValue = $this->encryptCredential($optionName, $options);
        }

        return update_option($optionName, $optionValue, false);
    }

    /**
     * Display storage success message
     *
     * @return void
     */
    public function showAuthenticateSuccessFailureMessage()
    {

        if (empty($_GET['auth-storage'])) {
            return;
        }

        $userDataToDisplay = '';
        if ($this->label === 'Google Drive' && isset($_GET['userDataToDisplay'])) {
            $userDataToDisplay = filter_input(INPUT_GET, 'userDataToDisplay');
        }

        switch ($_GET['auth-storage']) {
            case 'true':
                ?>
                <div class="notice notice-success is-dismissible">
                    <p>
                        <?php printf(esc_html__('The %s storage is authenticated successfully! %s', 'wp-staging'), $this->label, esc_html($userDataToDisplay)); ?>
                    </p>
                </div>
                <?php
                break;
            case 'false':
                ?>
                <div class="wpstg--notice wpstg--error is-dismissible">
                    <p>
                        <?php printf(esc_html__('The %s storage authentication failed!', 'wp-staging'), $this->label); ?>
                    </p>
                </div>
                <?php
                break;
        }
    }

    /**
     * Whether Guzzle available to work
     *
     * @return bool
     */
    public function isGuzzleAvailable()
    {
        try {
            $http = new GuzzleClient([
                "verify" => $this->getCertPath()
            ]);
        } catch (Exception $ex) {
            debug_log($ex->getMessage());
            return false;
        }

        return true;
    }

    /** @return string */
    public function getCertPath()
    {
        return WPSTG_PLUGIN_DIR . 'Pro/Backup/cacert.pem';
    }

    /** @return string */
    public function getError()
    {
        return $this->error;
    }

    /** @return array */
    private function getCredentialOptionKeys()
    {
        return [
            'wpstg_googledrive'        => [
                'googleClientId',
                'googleClientSecret'
            ],
            'wpstg_sftp'               => [
                'username',
                'password',
                'key',
                'passphrase'
            ],
            'wpstg_amazons3'           => [
                'accessKey',
                'secretKey'
            ],
            'wpstg_digitalocean-space' => [
                'accessKey',
                'secretKey'
            ],
            'wpstg_wasabi'             => [
                'accessKey',
                'secretKey'
            ],
            'wpstg_generic-s3'         => [
                'accessKey',
                'secretKey'
            ]
        ];
    }

    /** @return object */
    private function dataEncryption()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new DataEncryption();
        }
        return $inst;
    }

    /** @return bool */
    public function isEncrypted()
    {
        $optionName     = $this->getOptionName();
        $optionValue    = $this->getOptions();
        $credentialKeys = $this->getCredentialOptionKeys();
        if (!empty($optionValue) && is_array($optionValue) && array_key_exists($optionName, $credentialKeys)) {
            foreach ($credentialKeys[$optionName] as $key) {
                if (!empty($optionValue[$key]) && $this->dataEncryption()->isEncrypted($optionValue[$key])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param string $optionName
     * @param mixed $optionValue
     * @return array
     */
    private function encryptCredential($optionName, $optionValue)
    {
        $credentialKeys = $this->getCredentialOptionKeys();
        if ($optionValue === '' || !array_key_exists($optionName, $credentialKeys)) {
            return $optionValue;
        }

        if (empty($optionValue) || !is_array($optionValue)) {
            return $optionValue;
        }

        foreach ($credentialKeys[$optionName] as $key) {
            if (!empty($optionValue[$key])) {
                $optionValue[$key] = $this->dataEncryption()->encrypt($optionValue[$key]);
            }
        }

        return $optionValue;
    }

    /**
     * @param string $optionName
     * @param mixed $optionValue
     * @return array
     */
    private function decryptCredential($optionName, $optionValue)
    {
        $credentialKeys = $this->getCredentialOptionKeys();
        if ($optionValue === '' || !array_key_exists($optionName, $credentialKeys)) {
            return $optionValue;
        }

        if (empty($optionValue) || !is_array($optionValue)) {
            return $optionValue;
        }

        foreach ($credentialKeys[$optionName] as $key) {
            if (!empty($optionValue[$key])) {
                $optionValue[$key] = $this->dataEncryption()->decrypt($optionValue[$key]);
            }
        }

        return $optionValue;
    }
}
