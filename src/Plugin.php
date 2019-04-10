<?php

namespace Curator\ComposerSAPlugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Util\Filesystem;

class Plugin implements PluginInterface, EventSubscriberInterface {

  /** @var Composer $composer */
  protected $composer;
  /** @var IOInterface $io */
  protected $io;

  public function activate(Composer $composer, IOInterface $io) {
    $this->composer = $composer;
    $this->io = $io;
  }

  public static function getSubscribedEvents() {
    return array(
      'post-autoload-dump' => 'injectValidatingAutoloader'
    );
  }

  public function injectValidatingAutoloader(Event $event) {
    $this->io->write('Updating autoloader to perform verification...');
    $config = $this->composer->getConfig();
    $filesystem = new Filesystem();
    $vendorPath = $filesystem->normalizePath(realpath(realpath($config->get('vendor-dir'))));

    $class_loader_file = $vendorPath . DIRECTORY_SEPARATOR . 'composer' . DIRECTORY_SEPARATOR . 'ClassLoader.php';
    $class_loader_base_code = file_get_contents($class_loader_file);
    $original_snippets = ["\nclass ClassLoader\n", '    private $'];
    $replaced_snippets = ["\nclass LoaderBase \n", '    protected $'];
    $class_loader_base_code = str_replace($original_snippets, $replaced_snippets, $class_loader_base_code);
    file_put_contents($class_loader_file, $class_loader_base_code);

    $new_loader_code = file_get_contents(dirname(__FILE__) . '/ClassLoader.php');
    $new_loader_code = substr($new_loader_code, strpos($new_loader_code, "\nclass ClassLoader extends LoaderBase"));
    file_put_contents($class_loader_file, $new_loader_code, FILE_APPEND);
  }
}