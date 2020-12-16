<?php namespace Adnduweb\Ci4Media\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use CodeIgniter\Exceptions\FrameworkException;

class MediasException extends \RuntimeException implements ExceptionInterface
{
	public static function forDirFail($dir)
	{
		return new static(lang('Medias.dirFail', [$dir]));
	}
	public static function forChunkDirFail($dir)
	{
		return new static(lang('Medias.chunkDirFail', [$dir]));
	}

	public static function forNoChunks($dir)
	{
		return new static(lang('Medias.noChunks', [$dir]));
	}

	public static function forNewFileFail($file)
	{
		return new static(lang('Medias.newFileFail', [$file]));
	}

	public static function forWriteFileFail($file)
	{
		return new static(lang('Medias.writeFileFail', [$file]));
	}
}
