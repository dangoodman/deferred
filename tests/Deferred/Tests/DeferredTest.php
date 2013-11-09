<?php
    namespace Deferred\Tests;

    use Deferred\Deferred;



    class DeferredTest extends \PHPUnit_Framework_TestCase
    {
        public function testCallsCallbackOnDestruction()
        {
            $deferredDestroying = false;
            $self = $this;
            $callbackCalled = false;

            $deferred = new Deferred(function() use($self, &$deferredDestroying, &$callbackCalled)
            {
                $self->assertTrue($deferredDestroying);
                $callbackCalled = true;
            });
            $this->assertFalse($callbackCalled);

            $deferredDestroying = true;
            unset($deferred);
            $this->assertTrue($callbackCalled);
        }
    }
?>