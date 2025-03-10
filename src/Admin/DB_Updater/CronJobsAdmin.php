<?php

namespace App\Admin\DB_Updater;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

class CronJobsAdmin extends AbstractAdmin
{
  /**
   * {@inheritdoc}
   */
  protected $baseRouteName = 'admin_catrobat_adminbundle_cronjobsadmin';

  /**
   * {@inheritdoc}
   */
  protected $baseRoutePattern = 'cronjobs';

  protected function configureRoutes(RouteCollectionInterface $collection): void
  {
    $collection
      ->remove('export')
      ->remove('acl')
      ->remove('delete')
      ->remove('create')
      ->add('trigger_cron_jobs')
      ->add('reset_cron_job')
    ;
  }

  /**
   * {@inheritdoc}
   *
   * Fields to be shown on lists
   */
  protected function configureListFields(ListMapper $list): void
  {
    $list
      ->add('name')
      ->add('state')
      ->add('cron_interval')
      ->add('start_at')
      ->add('end_at')
      ->add('result_code')
      ->add(ListMapper::NAME_ACTIONS, null, [
        'actions' => [
          'reset_cron_job' => [
            'template' => 'Admin/CRUD/list__action_reset_cron_job.html.twig',
          ],
        ],
      ])
    ;
  }
}
