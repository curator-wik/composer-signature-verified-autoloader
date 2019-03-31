<?php

namespace Curator\ComposerSAPlugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\InstallerEvent;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

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

  public function injectValidatingAutoloader(InstallerEvent $event) {
    $this->io->write('Intercepted post-autoload-dump event');
  }
}