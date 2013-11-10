[![Build Status](https://travis-ci.org/dangoodman/deferred.png)](https://travis-ci.org/dangoodman/deferred)


Deferred
========

Deferred callback execution based on RAII.

Usage example:
```php
    function download($url, $toFile)
    {
        // Temporary increase memory limit for this function
        // $restoreMemoryLimit automatically called upon function exit
        $prevMemoryLimit = ini_get('memory_limit');
        ini_set('memory_limit', '256M');
        $restoreMemoryLimit = new Deferred(function() use($prevMemoryLimit) {
            ini_set('memory_limit', $prevMemoryLimit);
        });

        $contents = file_get_contents($url);
        if (!$contents) {
            throw new \RuntimeException("Couldn't fetch url contents");
        }

        if (file_put_contents($toFile, $contents) === false) {
            throw new \RuntimeException("Couldn't save url contents to file '{$toFile}'");
        }

        return $contents;
    }
```

Compare it with following example without deferreds:
```php
    function downloadWithouDeferred($url, $toFile)
    {
        $prevMemoryLimit = ini_get('memory_limit');
        ini_set('memory_limit', '256M');

        $contents = file_get_contents($url);
        if (!$contents) {
            ini_set('memory_limit', $prevMemoryLimit);
            throw new \RuntimeException("Couldn't fetch url contents");
        }

        if (file_put_contents($toFile, $contents) === false) {
            ini_set('memory_limit', $prevMemoryLimit);
            throw new \RuntimeException("Couldn't save url contents to file '{$toFile}'");
        }

        ini_set('memory_limit', $prevMemoryLimit);

        return $contents;
    }
```