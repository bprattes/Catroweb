<?php

namespace Tests\PhpUnit\Project\CatrobatFile;

use App\DB\Entity\Project\Program;
use App\Project\CatrobatFile\ProgramFlavorEventSubscriber;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @internal
 * @covers  \App\Project\CatrobatFile\ProgramFlavorEventSubscriber
 */
class ProgramFlavorEventSubscriberTest extends TestCase
{
  private ProgramFlavorEventSubscriber $program_flavor_listener;

  private RequestStack $stack;

  protected function setUp(): void
  {
    $this->stack = new RequestStack();
    $this->program_flavor_listener = new ProgramFlavorEventSubscriber($this->stack);
  }

  public function testInitialization(): void
  {
    $this->assertInstanceOf(ProgramFlavorEventSubscriber::class, $this->program_flavor_listener);
  }

  public function testSetsTheFlavorOfAProgramBasedOnItsRequestFlavor(): void
  {
    $program = new Program();
    $request = new Request();

    $request->attributes->set('flavor', 'pocketcode');
    $this->stack->push($request);
    $this->program_flavor_listener->checkFlavor($program);
    Assert::assertEquals('pocketcode', $program->getFlavor());

    $request->attributes->set('flavor', 'pocketphiro');
    $this->stack->push($request);
    $this->program_flavor_listener->checkFlavor($program);
    Assert::assertEquals('pocketphiro', $program->getFlavor());
  }
}
