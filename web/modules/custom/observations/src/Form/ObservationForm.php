<?php

/**
 * @file
 * Contains \Drupal\observations\Form\ObservationForm.
 */

namespace Drupal\observations\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Observation edit forms.
 *
 * @ingroup observations
 */
class ObservationForm extends ContentEntityForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\observations\Entity\Observation */
    $form = parent::buildForm($form, $form_state);
    
    foreach($form['observation_marks']['widget'] AS $key => $value) {
      if(is_int($key))
        $form['observation_marks']['widget'][$key]['#theme'] = "observations_observed_marks_field_collection_item";
    }
    
    $form['observation_marks']['widget']['add_more']['#value'] = t("Add Individual");
    
    if(isset($form['individuals_ibm'])) {
    
      foreach($form['individuals_ibm']['widget'] AS $key => $value) {
        if(is_int($key))
          $form['individuals_ibm']['widget'][$key]['#theme'] = "observations-individuals-ibm-field-collection-item";
      }
      $form['individuals_ibm']['widget']['add_more']['#value'] = t("Add Individual (IBM)");
      
    }
    return $form;
  }
  
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    $entity = $this->getEntity();
    
    //perform validation
    if(sizeof($form_state->getValue('observation_marks'))-1 != $form_state->getValue('number')[0]['value']) {
      $form_state->setErrorByName('number', $this->t('Number of Observed Individuals is not equal to the number of Individuals.<br>Please add and fill out a characteristics form for each observed bird. You can add Individuals by clicking on the "ADD INDIVIDUAL" button.'));
    }

    $i = 1;
    
    foreach($form_state->getValue('observation_marks') AS $key => $item) {
      if(is_numeric($key)) {
        if(sizeof($item['field_list']) == 0) {
          $form_state->setError($form['observation_marks'], $this->t("Please choose at least one characteristics for Individual @nbr", array('@nbr' => $i)));
        }
      }
      $i++;  
    }
    
    if($form_state->getValue('position')[0]['lat'] == '' || $form_state->getValue('position')[0]['lng'] == '') {
          $form_state->setErrorByName('position', $this->t('Please chose the location where you did see the bearded vulture(s).'));
    }
    
    if($form_state->getValue('email')[0]['value'] == '' && $form_state->getValue('phone')[0]['value'] == '') {
          $form_state->setErrorByName('email', $this->t('Please provide a email adresse or a phone number.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    $params['entity'] = $this->entity;
    if($status == SAVED_NEW) {
      $mailManager = \Drupal::service('plugin.manager.mail');
      $mailManager->mail('observations', 'observation_added', 'fl@swild.ch, dh@swild.ch', 'en', $params, NULL, TRUE);
    }
   
    if(sizeof(explode('/', \Drupal::service('path.current')->getPath())) == 3) {
      $form_state->setRedirect('entity.node.canonical', ['node' => 491]);
    } else {
      $form_state->setRedirect('entity.observation.canonical', ['observation' => $this->entity->id()]);
    }
  }

}
