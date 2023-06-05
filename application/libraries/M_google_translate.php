<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(FCPATH . 'vendor/autoload.php');

use Google\Cloud\Translate\V3\TranslationServiceClient;

$CI = &get_instance();

class M_google_translate {

    protected $project = 'core-dominion-314311';


	public function init() {
		global $CI;
		$google_config = json_decode($CI->tts_config->google, TRUE);
		try {
			$client = new TranslationServiceClient(['credentials' => json_decode(file_get_contents($google_config['config_file']), true)]);
		}
		catch (Exception $e) {
			log_message('error', $e->getMessage());
		}
		return $client;
	}

    public function get_supported_languages() {
        global $CI;
        $client = $this->init();
		$formattedParent = $client->locationName($this->project, 'global');
		$supported_languages = [];
		$languageCode = 'en';
		try {
			$response = $client->getSupportedLanguages($formattedParent,
				['displayLanguageCode' => $languageCode]);
			foreach ($response->getLanguages() as $language) {
				$supported_languages[$language->getLanguageCode()] = $language->getDisplayName();
			}
		} finally {
			$client->close();
		}
		return $supported_languages;
    }



	public function get_translated_text($translationArray) {
		global $CI;
        $client = $this->init();
		$formattedParent = $client->locationName($this->project, 'global');
		try {
			$contents = [];
			$sourceLanguageCode = $translationArray['source_lang'];
			$targetLanguageCode = $translationArray['target_lang'];
			$contents[] = $translationArray['contents'];
			$options = ['sourceLanguageCode' => $sourceLanguageCode];
			$response = $client->translateText($contents, $targetLanguageCode, $formattedParent, $options);
			$translatedString = '';
			foreach ($response->getTranslations() as $translations) {
				$translatedString = $translations->getTranslatedText();
			}
		} finally {
			$client->close();
		}
		return $translatedString;
	}

}
?>
