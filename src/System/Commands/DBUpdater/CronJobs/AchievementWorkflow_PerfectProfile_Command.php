<?php

namespace App\System\Commands\DBUpdater\CronJobs;

use App\DB\Entity\User\Achievements\Achievement;
use App\DB\Entity\User\User;
use App\User\Achievements\AchievementManager;
use App\User\UserManager;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AchievementWorkflow_PerfectProfile_Command extends Command
{
  public function __construct(protected UserManager $user_manager, protected AchievementManager $achievement_manager)
  {
    parent::__construct();
  }

  protected function configure(): void
  {
    $this->setName('catrobat:workflow:achievement:perfect_profile')
      ->setDescription('Unlocking perfect_profile user achievements')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $this->addPerfectProfileAchievementToEveryUser($output);

    return 0;
  }

  protected function addPerfectProfileAchievementToEveryUser(OutputInterface $output): void
  {
    $user_achievements = $this->achievement_manager->findUserAchievementsOfAchievement(Achievement::PERFECT_PROFILE);
    $excluded_user_id_list = array_map(fn ($user_achievement) => $user_achievement->getUser()->getId(), $user_achievements);
    $user_ID_list = $this->user_manager->getUserIDList();
    $user_id_list = array_values(array_diff($user_ID_list, $excluded_user_id_list));

    foreach ($user_id_list as $user_id) {
      /* @var User|null $user */
      $user = $this->user_manager->find($user_id);
      if (!is_null($user)) {
        try {
          $this->achievement_manager->unlockAchievementPerfectProfile($user);
        } catch (Exception $exception) {
          $output->writeln($exception->getMessage());
        }
      }
    }
  }
}
