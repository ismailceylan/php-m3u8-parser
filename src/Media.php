<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represents a media in a master playlist.
 */
class Media implements M3U8Serializable, JsonSerializable
{
	/**
	 * The properties of the stream.
	 * 
	 * @var array
	 */
	public array $nonStandardProps = [];

	/**
	 * The default attribute of the media.
	 *
	 * @var Boolean|null
	 */
	public ?Boolean $default = null;

	/**
	 * The autoSelect attribute of the media.
	 *
	 * @var Boolean|null
	 */
	public ?Boolean $autoSelect = null;

	/**
	 * The forced attribute of the media.
	 *
	 * @var Name|null
	 */
	public ?Name $name = null;
	
	/**
	 * The language attribute of the media.
	 *
	 * @var Language|null
	 */
	public ?Language $language = null;

	/**
	 * The type attribute of the media.
	 *
	 * @var MediaType|null
	 */
	public ?MediaType $type = null;

	/**
	 * The group-ID attribute of the media.
	 *
	 * @var GroupId|null
	 */
	public ?GroupId $groupId = null;

	/**
	 * The URI attribute of the media.
	 *
	 * @var Uri|null
	 */
	public ?Uri $uri = null;

	/**
	 * The options of the media.
	 *
	 * @var integer
	 */
	private int $options;

	/**
	 * Constructs a Media object from a raw M3U8 media syntax.
	 *
	 * @param string $rawMediaSyntax The raw M3U8 EXT-X-MEDIA syntax.
	 * @param int $options The options of the media.
	 */
	public function __construct( string $rawMediaSyntax = '', int $options = 0 )
	{
		$this->options = $options;

		if( $rawMediaSyntax )
		{
			$this->parseRawSyntax( $rawMediaSyntax );
		}
	}

	/**
	 * Sets the default attribute of the media.
	 *
	 * @param string|bool $value The value of the default attribute.
	 * @return self
	 */
	public function setDefault( string|bool $value ): self
	{
		$this->default = new Boolean( $value, 'DEFAULT' );
		return $this;
	}

	/**
	 * Sets the AUTOSELECT attribute of the media.
	 *
	 * @param string|bool $value The value of the AUTOSELECT attribute.
	 * @return self
	 */
	public function setAutoSelect( string|bool $value ): self
	{
		$this->autoSelect = new Boolean( $value, 'AUTOSELECT' );
		return $this;
	}

	/**
	 * Sets the NAME attribute of the media.
	 *
	 * @param string $value The value of the NAME attribute.
	 * @return self
	 */
	public function setName( string $value ): self
	{
		$this->name = new Name( $value );
		return $this;
	}

	/**
	 * Sets the LANGUAGE attribute of the media.
	 *
	 * @param string $value The value of the LANGUAGE attribute.
	 * @return self
	 */
	public function setLanguage( string $value ): self
	{
		$this->language = new Language( $value );
		return $this;
	}

	/**
	 * Sets the TYPE attribute of the media.
	 *
	 * @param string $value The value of the TYPE attribute.
	 * @return self
	 */
	public function setType( string $value ): self
	{
		$this->type = new MediaType( $value );
		return $this;
	}

	/**
	 * Sets the GROUP-ID attribute of the media.
	 *
	 * @param string $value The value of the GROUP-ID attribute.
	 * @return self
	 */
	public function setGroupId( string $value ): self
	{
		$this->groupId = new GroupId( $value );
		return $this;
	}

    /**
     * Sets the URI attribute of the media.
     *
     * @param string $value The URI to set for the media.
     * @return self Returns the instance of the Media class.
     */
	public function setUri( string $value ): self
	{
		$this->uri = new Uri( $value );
		return $this;
	}

	/**
	 * Checks if the media is on the same group as the given group-id.
	 *
	 * @param GroupId|null $target The target group-id to compare with.
	 * @return bool True if the media is on the same group as the target, false otherwise.
	 */
	public function isOnSameGroup( ?GroupId $target = null ): bool
	{
		if( ! $target )
		{
			return false;
		}

		return $target->isEqual( $this->groupId );
	}

	/**
	 * Parses a raw M3U8 EXT-X-MEDIA syntax and sets the properties of the Media instance.
	 *
	 * @param string $rawMediaSyntax The raw M3U8 EXT-X-MEDIA syntax.
	 * @return void
	 */
	public function parseRawSyntax( string $rawMediaSyntax ): void
	{
		$tag = new AttributedTag( $rawMediaSyntax );

		foreach( $tag->attributes as $key => $value )
		{
			$key = strtoupper( $key );

			if( $key === 'DEFAULT' )
			{
				$this->setDefault( $value );
			}
			else if( $key === 'AUTOSELECT' )
			{
				$this->setAutoSelect( $value );
			}
			else if( $key === 'NAME' )
			{
				$this->setName( $value );
			}
			else if( $key === 'LANGUAGE' )
			{
				$this->setLanguage( $value );
			}
			else if( $key === 'TYPE' )
			{
				$this->setType( $value );
			}
			else if( $key === 'GROUP-ID' )
			{
				$this->setGroupId( $value );
			}
			else if( $key === 'URI' )
			{
				$this->setUri( $value );
			}
			else
			{
				$this->nonStandardProps[ $key ] = $value;
			}
		}
	}

	/**
	 * Returns the content of the media as a M3U8 string.
	 *
	 * @return string
	 */
	public function toM3U8(): string
	{
		$data = [];

		if( $this->type )
		{
			$data[] = $this->type->toM3U8();
		}

		if( $this->name )
		{
			$data[] = $this->name->toM3U8();
		}

		if( $this->language )
		{
			$data[] = $this->language->toM3U8();
		}

		if( $this->default )
		{
			$data[] = $this->default->toM3U8();
		}

		if( $this->autoSelect )
		{
			$data[] = $this->autoSelect->toM3U8();
		}

		if( $this->groupId )
		{
			$data[] = $this->groupId->toM3U8();
		}

		if( $this->uri )
		{
			$data[] = $this->uri->toM3U8();
		}

		return '#EXT-X-MEDIA:' . implode( ',', $data );
	}

	/**
	 * Converts the media to a value that can be serialized natively by json_encode().
	 *
	 * @return array The media's properties.
	 */
	public function jsonSerialize(): array
	{
		$data =
		[
			'default' => $this->default,
			'autoSelect' => $this->autoSelect,
			'name' => $this->name,
			'language' => $this->language,
			'type' => $this->type,
			'uri' => $this->uri,
		];

		if( ! ( $this->options & MasterPlaylist::HideNonStandardPropsInJson ))
		{
			$data[ 'nonStandardProps' ] = $this->nonStandardProps;
		}

		if( ! ( $this->options & MasterPlaylist::HideGroupsInJson ))
		{
			$data[ 'groupId' ] = $this->groupId;
		}

		if( $this->options & MasterPlaylist::HideNullValuesInJson )
		{
			if( $data[ 'default' ] === null )
			{
				unset( $data[ 'default' ]);
			}

			if( $data[ 'autoSelect' ] === null )
			{
				unset( $data[ 'autoSelect' ]);
			}

			if( $data[ 'name' ] === null )
			{
				unset( $data[ 'name' ]);
			}

			if( $data[ 'language' ] === null )
			{
				unset( $data[ 'language' ]);
			}

			if( $data[ 'type' ] === null )
			{
				unset( $data[ 'type' ]);
			}

			if( $data[ 'uri' ] === null )
			{
				unset( $data[ 'uri' ]);
			}
		}

		return $data;
	}
}
