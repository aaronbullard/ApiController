<?php namespace Aaronbullard\Api;

abstract class Transformer{

	public function transformArray(array $items)
	{
		return array_map([$this, 'transform'], $items);
	}

	public abstract function transform($item);

}