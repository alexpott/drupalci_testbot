<?php
/**
 * @file
 * Contains \DrupalCI\Plugin\generic\Command
 *
 * Processes "[build_step]: command:" instructions from within a job definition.
 */

namespace DrupalCI\Plugin\generic;
use DrupalCI\Plugin\PluginBase;

/**
 * @PluginID("command")
 */
class Command extends PluginBase {

  /**
   * {@inheritdoc}
   */
  public function run($job, $data) {
    // Data format: 'command [arguments]' or array('command [arguments]', 'command [arguments]')
    // $data May be a string if one version required, or array if multiple
    // Normalize data to the array format, if necessary
    $data = is_array($data) ? $data : [$data];
    $docker = $job->getDocker();
    $manager = $docker->getContainerManager();

    if (!empty($data)) {
      // Check that we have a container to execute on
      $configs = $job->getExecContainers();
      foreach ($configs as $type => $containers) {
        foreach ($containers as $key => $container) {
          $id = $container['id'];
          $instance = $manager->find($id);
          $type = 1;
          $output = "";
          $short_id = substr($id, 0, 8);
          $job->output->writeln("<info>Executing on container instance $short_id:</info>");
          foreach ($data as $cmd) {
            $job->output->writeln("<fg=magenta>$cmd</fg=magenta>");
            $exec = explode(" ", $cmd);
            $exec_id = $manager->exec($instance, $exec, TRUE, TRUE, TRUE, TRUE);
            $job->output->writeln("<info>Command created as exec id " . substr($exec_id, 0, 8) . "</info>");
            $result=$manager->execstart($exec_id, function($output, $type) {
              fputs($type === 1 ? STDOUT : STDERR, $output);
            });
            $job->output->writeln($output);
            //Response stream is never read you need to simulate a wait in order to get output
            $result->getBody()->getContents();
            $job->output->writeln($result->__toString());
          }
        }
      }
    }
  }
}