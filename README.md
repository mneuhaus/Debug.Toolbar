## Debug Toolbar for FLOW3

This is an Debugging Tool for FLOW3 heavily inspired by http://symfony.com/blog/towards-symfony-2-1-the-new-web-debug-toolbar.
Consider this a Prototype for now, although it's already quite usable.

![FDT](http://dl.dropbox.com/u/314491/FDT.png)


### Short Demo:

http://youtu.be/iRF7QGO3J3k

### Requirements

You need to install the PhpProfiler: https://github.com/sandstorm/PhpProfiler

### Profiling some Classes

You can add some RegEx's to your Settings to enable Profiling of some Classes and Methods:

    Debug:
      Profiling:
        Classes:
          - Foo\ContentManagement.*->getClassAnnotations.*
          - Foo\ContentManagement\Controller.*

For Changes to take affect you need to clear you cache!


The Icons and the general look is currently heavily based on the Symfony2 WDT and will in time be polished to integrate into the FLOW3 Style. If you would like to help style this baby feel free to drop me a line apocalip@gmail.com

## Icons License
https://github.com/symfony/symfony/blob/master/src/Symfony/Bundle/WebProfilerBundle/Resources/ICONS_LICENSE.txt