<?php

namespace Iceylan\M3U8;

use Closure;
use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represent a stream.
 */
class Stream implements M3U8Serializable, JsonSerializable
{
	/**
	 * The properties of the stream.
	 * 
	 * @var array
	 */
	public array $nonStandardProps = [];

	/**
	 * The audios of the stream.
	 *
	 * @var ObjectSet
	 */
	public ObjectSet $audios;

	/**
	 * The subtitles of the stream.
	 *
	 * @var ObjectSet
	 */
	public ObjectSet $subtitles;

	/**
	 * The URI of the stream.
	 * 
	 * @var Uri|null
	 */
	public ?Uri $uri = null;

	/**
	 * The bandwidth of the stream.
	 *
	 * @var Bandwidth|null
	 */
	public ?Bandwidth $bandwidth = null;

	/**
	 * The average bandwidth of the stream.
	 *
	 * @var Bandwidth|null
	 */
	public ?Bandwidth $averageBandwidth = null;

	/**
	 * The resolution of the stream.
	 *
	 * @var Resolution|null
	 */
	public ?Resolution $resolution = null;

	/**
	 * The codecs of the stream.
	 *
	 * @var CodecList|null
	 */
	public ?CodecList $codecs = null;

	/**
	 * The program ID of the stream.
	 *
	 * @var ProgramID|null
	 */
	public ?ProgramID $programID = null;
	
	/**
	 * The frame rate of the stream.
	 *
	 * @var FrameRate|null
	 */
	public ?FrameRate $frameRate = null;

	/**
	 * The audio group of the stream.
	 *
	 * @var GroupId|null
	 */
	public ?GroupId $audioGroup = null;

	/**
	 * The subtitle group of the stream.
	 *
	 * @var GroupId|null
	 */
	public ?GroupId $subtitleGroup = null;

	/**
	 * The sync medias callback.
	 *
	 * @var Closure
	 */
	private Closure $syncMedias;

	/**
	 * The options of the stream.
	 *
	 * @var integer
	 */
	private int $options;

	/**
	 * Construct a stream from a raw M3U8 stream syntax.
	 *
	 * @param string $rawStreamSyntax The raw M3U8 EXT-X-STREAM-INF syntax.
	 * @param Closure $syncMedias The callback to sync the medias.
	 * @param int $options The options of the stream.
	 */
	public function __construct( string $rawStreamSyntax = '', Closure $syncMedias, int $options = 0 )
	{
		$this->audios = new ObjectSet;
		$this->subtitles = new ObjectSet;
		$this->syncMedias = $syncMedias;
		$this->options = $options;

		if( $rawStreamSyntax )
		{
			$this->parseRawSyntax( $rawStreamSyntax );
		}
	}

	/**
	 * Parses the given M3U8 EXT-X-STREAM-INF syntax and
	 * sets the properties of the stream instance.
	 *
	 * @param string $rawStreamSyntax The raw M3U8 EXT-X-STREAM-INF syntax.
	 * @return self Returns the instance of the Stream class.
	 */
	public function parseRawSyntax( string $rawStreamSyntax ): self
	{
		$tag = new AttributedTag( $rawStreamSyntax );

		foreach( $tag->attributes as $key => $value )
		{
			$key = strtoupper( $key );

			if( $key === 'BANDWIDTH' )
			{
				$this->setBandwidth( $value );
			}
			else if( $key === 'AVERAGE-BANDWIDTH' )
			{
				$this->setAverageBandwidth( $value );
			}
			else if( $key === 'RESOLUTION' )
			{
				$this->resolution = new Resolution( $value );
			}
			else if( $key === 'CODECS' )
			{
				$this->setCodecs( ...$value );
			}
			else if( $key === 'PROGRAM-ID' )
			{
				$this->setProgramID( $value );
			}
			else if( $key === 'FRAME-RATE' )
			{
				$this->setFrameRate( $value );
			}
			else if( $key === 'AUDIO' )
			{
				$this->setAudioGroup( $value );
			}
			else if( $key === 'SUBTITLES' )
			{
				$this->setSubtitleGroup( $value );
			}
			else
			{
				$this->nonStandardProps[ $key ] = $value;
			}
		}

		return $this;
	}

    /**
     * Sets the URI attribute of the stream.
     *
     * @param string $value The URI to set for the stream.
     * @return self Returns the instance of the Stream class.
     */
	public function setUri( string $value ): self
	{
		$this->uri = new Uri( $value );
		return $this;
	}

	/**
	 * Sets the resolution of the stream.
	 *
	 * @param string|int $width The width of the resolution.
	 * @param string|int $height The height of the resolution.
	 * @return self Returns the instance of the Stream class.
	 */
	public function setResolution( string|int $width, string|int $height ): self
	{
		$this->resolution = new Resolution( "{$width}x{$height}" );
		return $this;
	}

	/**
	 * Sets the bandwidth of the stream.
	 *
	 * @param int $bandwidth The bandwidth value in bits per second.
	 * @return self Returns the instance of the Stream class.
	 */
	public function setBandwidth( int $bandwidth ): self
	{
		$this->bandwidth = new Bandwidth( $bandwidth );
		return $this;
	}

	/**
	 * Sets the average bandwidth of the stream.
	 *
	 * @param int $averageBandwidth The average bandwidth value in bits per second.
	 * @return self Returns the instance of the Stream class.
	 */
	public function setAverageBandwidth( int $averageBandwidth ): self
	{
		$this->averageBandwidth = new Bandwidth( $averageBandwidth );
		return $this;
	}

	/**
	 * Sets the codecs of the stream.
	 *
	 * @param string ...$codecs The codecs to set. The codecs should be given as strings
	 * @return self Returns the instance of the Stream class.
	 */
	public function setCodecs( ...$codecs ): self
	{
		$this->codecs = new CodecList( $codecs );
		return $this;
	}

	/**
	 * Sets the program ID of the stream.
	 *
	 * @param string $programID The program ID to set for the stream.
	 * @return self Returns the instance of the Stream class.
	 */
	public function setProgramID( string $programID ): self
	{
		$this->programID = new ProgramID( $programID );
		return $this;
	}

	/**
	 * Sets the frame rate of the stream.
	 *
	 * @param int|float|string $frameRate The frame rate to set for the stream.
	 * @return self Returns the instance of the Stream class.
	 */
	public function setFrameRate( int|float|string $frameRate ): self
	{
		$this->frameRate = new FrameRate( $frameRate );
		return $this;
	}

	/**
	 * Sets the audio group of the stream.
	 *
	 * @param string $audioGroup The audio group to set for the stream.
	 * @return self Returns the instance of the Stream class.
	 */
	public function setAudioGroup( string $audioGroup ): self
	{
		$this->audioGroup = new GroupId( $audioGroup, 'AUDIO' );
		$this->syncMedias->__invoke( $this );
		
		return $this;
	}

	/**
	 * Sets the subtitle group of the stream.
	 *
	 * @param string $subtitleGroup The subtitle group to set for the stream.
	 * @return self Returns the instance of the Stream class.
	 */
	public function setSubtitleGroup( string $subtitleGroup ): self
	{
		$this->subtitleGroup = new GroupId( $subtitleGroup, 'SUBTITLES' );
		$this->syncMedias->__invoke( $this );
		
		return $this;
	}

	/**
	 * Adds a media to the stream, checking its type and group.
	 *
	 * If the media type is 'AUDIO' and it matches the stream's audio group,
	 * it is added to the audios array. If the media type is 'SUBTITLE'
	 * and it matches the stream's subtitle group, it is added to the
	 * subtitles array.
	 *
	 * @param Media $media The media to be added.
	 * @return self Returns the instance of the Stream class.
	 */
	public function push( Media $media ): self
	{
		if( $media->type == 'audio' && $media->isOnSameGroup( $this->audioGroup ))
		{
			$this->audios->set( $media );
		}

		if( $media->type == 'subtitle' && $media->isOnSameGroup( $this->subtitleGroup ))
		{
			$this->subtitles->set( $media );
		}

		return $this;
	}

	/**
	 * Converts the stream to a string in the M3U8 format.
	 *
	 * The M3U8 format is '#EXT-X-STREAM-INF:<program-id>,<resolution>,<bandwidth>,<codecs>'.
	 *
	 * @return string The stream in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		$data = [];

		if( $this->programID )
		{
			$data[] = $this->programID->toM3U8();
		}

		if( $this->bandwidth )
		{
			$data[] = $this->bandwidth->toM3U8();
		}

		if( $this->averageBandwidth )
		{
			$data[] = $this->averageBandwidth->toM3U8();
		}

		if( $this->frameRate )
		{
			$data[] = $this->frameRate->toM3U8();
		}

		if( $this->resolution )
		{
			$data[] = $this->resolution->toM3U8();
		}

		if( $this->codecs )
		{
			$data[] = $this->codecs->toM3U8();
		}

		if( $this->audioGroup )
		{
			$data[] = $this->audioGroup->toM3U8();
		}

		if( $this->subtitleGroup )
		{
			$data[] = $this->subtitleGroup->toM3U8();
		}

		return '#EXT-X-STREAM-INF:' . implode( ',', $data );
	}

	/**
	 * Converts the stream to a value that can be serialized natively by json_encode().
	 *
	 * The resulting value is an array that contains the stream's properties.
	 *
	 * @return array The stream's properties.
	 */
	public function jsonSerialize(): array
	{
		$data =
		[
			'uri' => $this->uri,
			'audios' => $this->audios,
			'subtitles' => $this->subtitles,
			'codecs' => $this->codecs,
			'bandwidth' => $this->bandwidth,
			'averageBandwidth' => $this->averageBandwidth,
			'resolution' => $this->resolution,
			'programID' => $this->programID,
			'frameRate' => $this->frameRate,
		];

		if( ! ( $this->options & MasterPlaylist::HideNonStandardPropsInJson ))
		{
			$data[ 'nonStandardProps' ] = $this->nonStandardProps;
		}

		if( ! ( $this->options & MasterPlaylist::HideGroupsInJson ))
		{
			$data[ 'audioGroup' ] = $this->audioGroup;
			$data[ 'subtitleGroup' ] = $this->subtitleGroup;
		}

		if( $this->options & MasterPlaylist::HideEmptyArraysInJson )
		{
			if( $data[ 'audios' ]->length() == 0 )
			{
				unset( $data[ 'audios' ]);
			}

			if( $data[ 'subtitles' ]->length() == 0 )
			{
				unset( $data[ 'subtitles' ]);
			}

			if( empty( $data[ 'nonStandardProps' ]))
			{
				unset( $data[ 'nonStandardProps' ]);
			}
		}

		if( $this->options & MasterPlaylist::HideNullValuesInJson )
		{
			if( $data[ 'uri' ] === null )
			{
				unset( $data[ 'uri' ]);
			}

			if( $data[ 'codecs' ] === null )
			{
				unset( $data[ 'codecs' ]);
			}
			
			if( $data[ 'bandwidth' ] === null )
			{
				unset( $data[ 'bandwidth' ]);
			}

			if( $data[ 'averageBandwidth' ] === null )
			{
				unset( $data[ 'averageBandwidth' ]);
			}

			if( $data[ 'resolution' ] === null )
			{
				unset( $data[ 'resolution' ]);
			}

			if( $data[ 'programID' ] === null )
			{
				unset( $data[ 'programID' ]);
			}

			if( $data[ 'frameRate' ] === null )
			{
				unset( $data[ 'frameRate' ]);
			}
		}

		return $data;
	}
}
