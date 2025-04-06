<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represents a language.
 */
class Language implements M3U8Serializable, JsonSerializable
{
	/**
	 * The language 2 letter code.
	 *
	 * @var string
	 */
	public string $shortCode;

	/**
	 * A list of right-to-left languages.
	 *
	 * @var array
	 */
    protected static array $rtlLanguages = [ 'ar', 'he', 'fa', 'ur' ];

	/**
	 * A map of language codes to language names.
	 *
	 * @var array
	 */
	protected static array $languageMap =
	[
		'aa' => [ 'Afar', '🇩🇯' ],
		'ab' => [ 'Abkhazian', '🏳️' ],
		'ae' => [ 'Avestan', '❓' ],
		'af' => [ 'Afrikaans', '🇿🇦' ],
		'ak' => [ 'Akan', '🇬🇭' ],
		'am' => [ 'Amharic', '🇪🇹' ],
		'an' => [ 'Aragonese', '🇪🇸' ],
		'ar' => [ 'Arabic', '🇸🇦' ],
		'as' => [ 'Assamese', '🇮🇳' ],
		'av' => [ 'Avar', '🇷🇺' ],
		'ay' => [ 'Aymara', '🇧🇴' ],
		'az' => [ 'Azerbaijani', '🇦🇿' ],
		'ba' => [ 'Bashkirian', '🇷🇺' ],
		'be' => [ 'Belarusian', '🇧🇾' ],
		'bg' => [ 'Bulgarian', '🇧🇬' ],
		'bh' => [ 'Biharish', '🇮🇳' ],
		'bi' => [ 'Bislama', '🇻🇺' ],
		'bm' => [ 'Bambara', '🇲🇱' ],
		'bn' => [ 'Bengali', '🇧🇩' ],
		'bo' => [ 'Tibetan', '🇨🇳' ],
		'br' => [ 'Breton', '🇫🇷' ],
		'bs' => [ 'Bosnian', '🇧🇦' ],
		'ca' => [ 'Catalan', '🇪🇸' ],
		'ce' => [ 'Chechen', '🇷🇺' ],
		'ch' => [ 'Chamorro', '🇲🇵' ],
		'co' => [ 'Corsican', '🇫🇷' ],
		'cr' => [ 'Cree', '🇨🇦' ],
		'cs' => [ 'Czech', '🇨🇿' ],
		'cu' => [ 'Church Slavonic', '🏳️' ],
		'cv' => [ 'Chuvash', '🇷🇺' ],
		'cy' => [ 'Welsh', '🇬🇧' ],
		'da' => [ 'Danish', '🇩🇰' ],
		'de' => [ 'German', '🇩🇪' ],
		'dv' => [ 'Divehi', '🇲🇻' ],
		'dz' => [ 'Dzongkha', '🇧🇹' ],
		'ee' => [ 'Ewe', '🇬🇭' ],
		'el' => [ 'Greek', '🇬🇷' ],
		'en' => [ 'English', '🇬🇧' ],
		'eo' => [ 'Esperanto', '🌐' ],
		'es' => [ 'Spanish', '🇪🇸' ],
		'et' => [ 'Estonian', '🇪🇪' ],
		'eu' => [ 'Basque', '🇪🇸' ],
		'fa' => [ 'Persian', '🇮🇷' ],
		'ff' => [ 'Fulah', '🇸🇳' ],
		'fi' => [ 'Finnish', '🇫🇮' ],
		'fj' => [ 'Fijian', '🇫🇯' ],
		'fo' => [ 'Faroese', '🇫🇴' ],
		'fr' => [ 'French', '🇫🇷' ],
		'fy' => [ 'Western Frisian', '🇳🇱' ],
		'ga' => [ 'Irish', '🇮🇪' ],
		'gd' => [ 'Scottish Gaelic', '🏴‍☠️' ],
		'gl' => [ 'Galician', '🇪🇸' ],
		'gn' => [ 'Guarani', '🇧🇷' ],
		'gu' => [ 'Gujarati', '🇮🇳' ],
		'gv' => [ 'Manx', '🇬🇧' ],
		'ha' => [ 'Hausa', '🇳🇬' ],
		'he' => [ 'Hebrew', '🇮🇱' ],
		'hi' => [ 'Hindi', '🇮🇳' ],
		'ho' => [ 'Hiri Motu', '🇵🇬' ],
		'hr' => [ 'Croatian', '🇭🇷' ],
		'ht' => [ 'Haitian Creole', '🇭🇹' ],
		'hu' => [ 'Hungarian', '🇭🇺' ],
		'hy' => [ 'Armenian', '🇦🇲' ],
		'hz' => [ 'Herero', '🇿🇦' ],
		'ia' => [ 'Interlingua', '🌐' ],
		'id' => [ 'Indonesian', '🇮🇩' ],
		'ie' => [ 'Interlingue', '🌐' ],
		'ig' => [ 'Igbo', '🇳🇬' ],
		'ii' => [ 'Sichuan Yi', '🇨🇳' ],
		'ik' => [ 'Inupiaq', '🇺🇸' ],
		'io' => [ 'Ido', '🌐' ],
		'is' => [ 'Icelandic', '🇮🇸' ],
		'it' => [ 'Italian', '🇮🇹' ],
		'iu' => [ 'Inuktitut', '🇨🇦' ],
		'ja' => [ 'Japanese', '🇯🇵' ],
		'jv' => [ 'Javanese', '🇮🇩' ],
		'ka' => [ 'Georgian', '🇬🇪' ],
		'kg' => [ 'Kongo', '🇨🇩' ],
		'ki' => [ 'Kikuyu', '🇰🇪' ],
		'kj' => [ 'Kuanyama', '🇦🇴' ],
		'kk' => [ 'Kazakh', '🇰🇿' ],
		'kl' => [ 'Greenlandic', '🇬🇱' ],
		'km' => [ 'Khmer', '🇰🇭' ],
		'kn' => [ 'Kannada', '🇮🇳' ],
		'ko' => [ 'Korean', '🇰🇷' ],
		'kr' => [ 'Kanuri', '🇳🇬' ],
		'ks' => [ 'Kashmiri', '🇮🇳' ],
		'ku' => [ 'Kurdish', '🇹🇷' ],
		'kv' => [ 'Komi', '🇷🇺' ],
		'kw' => [ 'Cornish', '🇬🇧' ],
		'ky' => [ 'Kyrgyz', '🇰🇬' ],
		'la' => [ 'Latin', '🌐' ],
		'lb' => [ 'Luxembourgish', '🇱🇺' ],
		'lg' => [ 'Ganda', '🇺🇬' ],
		'li' => [ 'Limburgan', '🇧🇪' ],
		'ln' => [ 'Lingala', '🇨🇩' ],
		'lo' => [ 'Lao', '🇱🇸' ],
		'lt' => [ 'Lithuanian', '🇱🇹' ],
		'lu' => [ 'Luba-Katanga', '🇨🇩' ],
		'lv' => [ 'Latvian', '🇱🇻' ],
		'mg' => [ 'Malagasy', '🇲🇬' ],
		'mh' => [ 'Marshallese', '🇲🇭' ],
		'mi' => [ 'Maori', '🇳🇿' ],
		'mk' => [ 'Macedonian', '🇲🇰' ],
		'ml' => [ 'Malayalam', '🇮🇳' ],
		'mn' => [ 'Mongolian', '🇲🇳' ],
		'mr' => [ 'Marathi', '🇮🇳' ],
		'ms' => [ 'Malay', '🇲🇾' ],
		'mt' => [ 'Maltese', '🇲🇹' ],
		'my' => [ 'Burmese', '🇲🇲' ],
		'na' => [ 'Nauru', '🇳🇷' ],
		'nb' => [ 'Norwegian Bokmål', '🇳🇴' ],
		'nd' => [ 'North Ndebele', '🇿🇼' ],
		'ne' => [ 'Nepali', '🇳🇵' ],
		'ng' => [ 'Ndonga', '🇦🇴' ],
		'nl' => [ 'Flemish', '🇧🇪' ],
		'nn' => [ 'Norwegian Nynorsk', '🇳🇴' ],
		'no' => [ 'Norwegian', '🇳🇴' ],
		'nr' => [ 'South Ndebele', '🇿🇦' ],
		'nv' => [ 'Navajo', '🇺🇸' ],
		'ny' => [ 'Chichewa', '🇲🇼' ],
		'oc' => [ 'Occitan', '🇫🇷' ],
		'oj' => [ 'Ojibwa', '🇨🇦' ],
		'om' => [ 'Oromo', '🇪🇹' ],
		'or' => [ 'Oriya', '🇮🇳' ],
		'os' => [ 'Ossetian', '🇷🇺' ],
		'pa' => [ 'Punjabi', '🇮🇳' ],
		'pi' => [ 'Pali', '🌐' ],
		'pl' => [ 'Polish', '🇵🇱' ],
		'ps' => [ 'Pashto', '🇦🇫' ],
		'pt' => [ 'Portuguese', '🇵🇹' ],
		'qu' => [ 'Quechua', '🇵🇪' ],
		'rm' => [ 'Romansh', '🇨🇭' ],
		'rn' => [ 'Rundi', '🇰🇼' ],
		'ro' => [ 'Romanian', '🇷🇴' ],
		'ru' => [ 'Russian', '🇷🇺' ],
		'rw' => [ 'Kinyarwanda', '🇷🇼' ],
		'sa' => [ 'Sanskrit', '🌐' ],
		'sc' => [ 'Sardinian', '🇮🇹' ],
		'sd' => [ 'Sindhi', '🇵🇰' ],
		'se' => [ 'Northern Sami', '🇸🇪' ],
		'sg' => [ 'Sango', '🇨🇫' ],
		'si' => [ 'Sinhala', '🇱🇰' ],
		'sk' => [ 'Slovak', '🇸🇰' ],
		'sl' => [ 'Slovenian', '🇸🇮' ],
		'sm' => [ 'Samoan', '🇼🇸' ],
		'sn' => [ 'Shona', '🇿🇼' ],
		'so' => [ 'Somali', '🇸🇴' ],
		'sq' => [ 'Albanian', '🇦🇱' ],
		'sr' => [ 'Serbian', '🇷🇸' ],
		'ss' => [ 'Swati', '🇿🇦' ],
		'st' => [ 'Southern Sotho', '🇿🇦' ],
		'su' => [ 'Sundanese', '🇮🇩' ],
		'sv' => [ 'Swedish', '🇸🇪' ],
		'sw' => [ 'Swahili', '🇰🇪' ],
		'ta' => [ 'Tamil', '🇮🇳' ],
		'te' => [ 'Telugu', '🇮🇳' ],
		'tg' => [ 'Tajik', '🇹🇯' ],
		'th' => [ 'Thai', '🇹🇭' ],
		'ti' => [ 'Tigrinya', '🇪🇷' ],
		'tk' => [ 'Turkmen', '🇹🇲' ],
		'tl' => [ 'Tagalog', '🇵🇭' ],
		'tn' => [ 'Tswana', '🇿🇦' ],
		'to' => [ 'Tonga', '🇹🇴' ],
		'tr' => [ 'Turkish', '🇹🇷' ],
		'ts' => [ 'Tsonga', '🇿🇦' ],
		'tt' => [ 'Tatar', '🇷🇺' ],
		'tw' => [ 'Twi', '🇬🇭' ],
		'ty' => [ 'Tahitian', '🇵🇬' ],
		'ug' => [ 'Uighur', '🇨🇳' ],
		'uk' => [ 'Ukrainian', '🇺🇦' ],
		'ur' => [ 'Urdu', '🇵🇰' ],
		'uz' => [ 'Uzbek', '🇺🇿' ],
		've' => [ 'Venda', '🇿🇦' ],
		'vi' => [ 'Vietnamese', '🇻🇳' ],
		'vo' => [ 'Volapük', '🏳️‍🌈' ],
		'wa' => [ 'Walloon', '🇧🇪' ],
		'wo' => [ 'Wolof', '🇸🇳' ],
		'xh' => [ 'Xhosa', '🇿🇦' ],
		'yi' => [ 'Yiddish', '🇩🇪' ],
		'yo' => [ 'Yoruba', '🇳🇬' ],
		'za' => [ 'Zhuang', '🇨🇳' ],
		'zh' => [ 'Chinese', '🇨🇳' ],
		'zu' => [ 'Zulu', '🇿🇦' ],
    ];

	/**
	 * Constructs a Language object with a given language code.
	 *
	 * @param string $shortCode The language 2 letter code.
	 */
	public function __construct( string $shortCode )
	{
		$this->shortCode = strtolower( $shortCode );
	}

	/**
	 * Retrieves the language 2 letter code.
	 *
	 * @return string The language code.
	 */
    public function getShortCode(): string
    {
        return $this->shortCode;
    }

    /**
     * Retrieves the full language name for the given language code.
     *
     * @return string|null The full language name if found, null otherwise.
     */
    public function getName(): ?string
    {
        return $this->isValid()
			? self::$languageMap[ $this->shortCode ][ 0 ]
			: null;
    }
    
    /**
     * Determines the text direction based on the language code.
     *
     * @return string 'rtl' if the language is right-to-left, 'ltr' otherwise.
     */
    public function getDirection(): string
    {
        return $this->isRightToLeft() ? 'rtl' : 'ltr';
    }

    /**
     * Checks if the language is a right-to-left language.
     *
     * @return bool true if the language is a right-to-left language, false otherwise.
     */
	public function isRightToLeft(): bool
    {
        return in_array( $this->shortCode, self::$rtlLanguages );
    }
    
    /**
     * Retrieves the flag emoji for the given language code.
     *
     * @return string|null The flag emoji if found, null otherwise.
     */
	public function getFlagEmoji(): ?string
    {
        return $this->isValid()
			? self::$languageMap[ $this->shortCode ][ 1 ]
			: null;
    }

    /**
     * Checks if the language is a valid language code.
     *
     * @return bool true if the language code is valid, false otherwise.
     */
    public function isValid(): bool
    {
        return isset( self::$languageMap[ $this->shortCode ]);
    }

    /**
     * Checks if the given language is the same as this language.
     *
     * @param Language $other The language to compare with.
     * @return bool true if the languages are equal, false otherwise.
     */
    public function equals( Language $other ): bool
    {
        return $this->shortCode === $other->getShortCode();
    }

    /**
     * Converts the language object to an associative array.
     *
     * @return array
     */
	public function toArray(): array
    {
        return [
			'name' => $this->getName(),
            'shortCode' => $this->shortCode,
            'direction' => $this->getDirection(),
            'emoji' => $this->getFlagEmoji(),
        ];
    }

	/**
	 * Converts the language code to an M3U8 compatible string format.
	 *
	 * @return string The M3U8 formatted language string.
	 */
	public function toM3U8(): string
	{
		return 'LANGUAGE="' . $this->getShortCode() . '"';
	}

	/**
	 * @inheritDoc
	 * 
	 * Serializes the language code to a value that can be serialized natively by json_encode().
	 * 
	 * @return string The language 2 letter code.
	 */
	public function jsonSerialize(): string
	{
		return $this->shortCode;
	}

    /**
     * Converts the language object to a string.
     *
     * @return string The language 2 letter code.
     */
	public function __toString(): string
    {
        return $this->shortCode;
    }
}
