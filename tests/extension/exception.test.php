<?php namespace Orchestra\Tests\Extension;

\Bundle::start('orchestra');

class ExceptionsTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test exception can be thrown.
	 *
	 * @expectedException \Orchestra\Extension\FilePermissionException
	 */
	public function testFilePermissionExceptionCanBeThrown()
	{
		throw new \Orchestra\Extension\FilePermissionException();
	}

	/**
	 * Test exception can be thrown.
	 *
	 * @expectedException \Orchestra\Extension\UnresolvedException
	 */
	public function testUnresolvedExceptionCanBeThrown()
	{
		throw new \Orchestra\Extension\UnresolvedException(array());
	}

	/**
	 * Test exception contain proper dependencies.
	 *
	 * @test
	 */
	public function testUnresolvedException()
	{
		$expected = array(
			'oneauth',
			'cello',
		);

		try
		{
			throw new \Orchestra\Extension\UnresolvedException($expected);
		}
		catch (\Orchestra\Extension\UnresolvedException $e)
		{
			$this->assertEquals("Unable to resolve dependencies", $e->getMessage());
			$this->assertEquals($expected, $e->getDependencies());
		}
	}
}
