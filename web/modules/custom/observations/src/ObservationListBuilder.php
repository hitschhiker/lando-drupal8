<?php

/**
 * @file
 * Contains \Drupal\observations\ObservationListBuilder.
 */

namespace Drupal\observations;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Observation entities.
 *
 * @ingroup observations
 */
class ObservationListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['date'] = $this->t('Date');
    $header['time'] = $this->t('Time');
    $header['individuals'] = $this->t('Individuals');
    $header['observer'] = $this->t('Observer');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    
    /* @var $entity \Drupal\observations\Entity\Observation */
    $row['id'] = $this->l(
      $entity->id(),
      new Url(
        'entity.observation.canonical', array(
          'observation' => $entity->id(),
        )
      )
    );
    $row['date'] = $entity->get("date")->value;
    $row['time'] = $entity->get("time")->value;
    $row['individuals'] = $entity->get("number")->getValue()[0]['value'];
    
    $firstname = $entity->get("firstname")->getValue()[0]['value'];
    $name = $entity->get("name")->getValue()[0]['value'];
    $location = $entity->get("location")->getValue()[0]['value'];
    
    $observer = $firstname;
    if($name <> '')
      $observer .= " ".$name;
    if($location <> '')
      $observer .= ", ".$location;
    
    $row['observer'] = $observer; 
    return $row + parent::buildRow($entity);
  }

}
