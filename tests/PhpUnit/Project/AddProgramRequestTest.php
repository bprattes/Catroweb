<?php

namespace Tests\PhpUnit\Project;

use App\DB\Entity\User\User;
use App\Project\AddProgramRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @internal
 * @covers  \App\Project\AddProgramRequest
 */
class AddProgramRequestTest extends TestCase
{
  private File $file;

  private AddProgramRequest $add_program_request;

  private User|MockObject $user;

  protected function setUp(): void
  {
    $this->user = $this->createMock(User::class);
    fopen('/tmp/PhpUnitTest', 'w');
    $this->file = new File('/tmp/PhpUnitTest');
    $this->add_program_request = new AddProgramRequest($this->user, $this->file);
  }

  public function testInitialization(): void
  {
    $this->assertInstanceOf(AddProgramRequest::class, $this->add_program_request);
  }

  public function testHoldsAUser(): void
  {
    $new_user = $this->createMock(User::class);
    $this->assertSame($this->user, $this->add_program_request->getUser());
    $this->add_program_request->setUser($new_user);
    $this->assertSame($new_user, $this->add_program_request->getUser());
  }

  public function testHoldsAFile(): void
  {
    $new_file = $this->file;
    $this->assertSame($this->file, $this->add_program_request->getProgramFile());
    $this->add_program_request->setProgramFile($new_file);
    $this->assertSame($new_file, $this->add_program_request->getProgramFile());
  }

  public function testHoldsAnIp(): void
  {
    $this->assertSame('127.0.0.1', $this->add_program_request->getIp());
  }

  public function testHasALanguage(): void
  {
    $this->assertNull($this->add_program_request->getLanguage());
    $this->add_program_request->setLanguage('de');
    $this->assertSame('de', $this->add_program_request->getLanguage());
  }

  public function testHasAFlavor(): void
  {
    $this->assertSame('pocketcode', $this->add_program_request->getFlavor());
  }
}
