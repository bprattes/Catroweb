<?php

namespace App\Admin\Projects\ApproveProjects;

use App\DB\Entity\Project\Program;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApproveProjectsController extends CRUDController
{
  public function approveAction(): RedirectResponse
  {
    /** @var Program|null $object */
    $object = $this->admin->getSubject();
    $object->setApproved(true);
    $object->setVisible(true);
    $this->admin->update($object);
    $this->addFlash('sonata_flash_success', $object->getName().' approved. '.$this->getRemainingProgramCount().' remaining.');

    return new RedirectResponse($this->getRedirectionUrl());
  }

  public function skipAction(): RedirectResponse
  {
    $object = $this->admin->getSubject();
    $this->addFlash('sonata_flash_warning', $object->getName().' skipped');

    return new RedirectResponse($this->getRedirectionUrl());
  }

  public function invisibleAction(): RedirectResponse
  {
    /** @var Program|null $object */
    $object = $this->admin->getSubject();
    if (null === $object) {
      throw new NotFoundHttpException(sprintf('unable to find the object'));
    }
    $object->setApproved(true);
    $object->setVisible(false);
    $this->admin->update($object);

    $this->addFlash('sonata_flash_success', $object->getName().' set to invisible'.$this->getRemainingProgramCount().' remaining.');

    return new RedirectResponse($this->getRedirectionUrl());
  }

  private function getRedirectionUrl(): string
  {
    $nextId = $this->getNextRandomApproveProgramId();
    if (null == $nextId) {
      return $this->admin->generateUrl('list');
    }

    return $this->admin->generateUrl('show', ['id' => $nextId]);
  }

  private function getNextRandomApproveProgramId(): ?string
  {
    $data_grid = $this->admin->getDatagrid();

    $objects = [...$data_grid->getResults()];
    if (empty($objects[0])) {
      return null;
    }
    $object_key = array_rand($objects);

    /** @var Program $object */
    $object = $objects[$object_key];

    return $object->getId();
  }

  private function getRemainingProgramCount(): int
  {
    $datagrid = $this->admin->getDatagrid();
    $objects = $datagrid->getResults();

    return count([...$objects]);
  }
}
