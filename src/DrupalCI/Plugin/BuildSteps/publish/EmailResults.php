<?php
/**
 * @file
 * Contains \DrupalCI\Plugin\Buildsteps\publish\EmailResults
 *
 * Processes "publish: email:" instructions from within a job definition.
 * Gathers the resulting job artifacts and pushes them to an email address.
 */

namespace DrupalCI\Plugin\Buildsteps\publish;
use DrupalCI\Plugin\PluginBase;

/**
 * @PluginID("email_results")
 */
class EmailResults extends PluginBase {

  /**
   * {@inheritdoc}
   */
  public function run() {
    echo 'run email_results';
  }

}