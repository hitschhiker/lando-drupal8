<?php

/**
 * @file
 * Contains \Drupal\observations\Entity\Observation.
 */

namespace Drupal\observations\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\observations\ObservationInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Observation entity.
 *
 * @ingroup observations
 *
 * @ContentEntityType(
 *   id = "observation",
 *   label = @Translation("Observation"),
 *   handlers = {
 *     "view_builder" = "Drupal\observations\Entity\ObservationViewBuilder",
 *     "list_builder" = "Drupal\observations\ObservationListBuilder",
 *     "views_data" = "Drupal\observations\Entity\ObservationViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\observations\Form\ObservationForm",
 *       "add" = "Drupal\observations\Form\ObservationForm",
 *       "edit" = "Drupal\observations\Form\ObservationForm",
 *       "delete" = "Drupal\observations\Form\ObservationDeleteForm",
 *     },
 *     "access" = "Drupal\observations\ObservationAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\observations\ObservationHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "observation",
 *   admin_permission = "administer observation entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/beobachtung/{observation}",
 *     "add-form" = "/beobachtung/melden",
 *     "edit-form" = "/beobachtung/{observation}/bearbeiten",
 *     "delete-form" = "/beobachtung/{observation}/loeschen",
 *     "collection" = "/beobachtung/liste",
 *   },
 *   field_ui_base_route = "observation.settings"
 * )
 */
class Observation extends ContentEntityBase implements ObservationInterface {
  use EntityChangedTrait;
  
  public static $new = 1;
  public static $process = 2;
  public static $ready = 3;
  public static $transmitted = 4;
  public static $forwarded = 5;
  
  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Observation entity.'))
      ->setReadOnly(TRUE);
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Observation entity.'))
      ->setReadOnly(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));
    
    $fields['date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Date of observation'))
      ->setRequired(TRUE)
      ->setDefaultValue(array(
          'default_date' => 'now',
          'default_date_type' => 'now',
      ))
      ->setSetting("datetime_type", "date")
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'datetime_default',
        'weight' => 1,
        'settings' => array(
            'format_type' => 'medium',
            'timezone_override' => '',
        )
      ))
      ->setDisplayOptions('form', array(
        'type' => 'datetime_default',
        'weight' => 1
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['time'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Approximate time of observation (if possible)'))
      ->setDescription(t('At what time have you done the observation (an approximate time is enough)?'))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 2
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 2
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['number'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Observed Individuals'))
      ->setDefaultValue(1)
      ->setRequired(TRUE)
      ->setDescription(t('How many bearded vultures could you observe?'))
      ->setSettings(array(
          'min' => 1,
          'max' => null,
          'prefix' => '',
          'suffix' => '',
          
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'number_integer',
        'weight' => 3,
        'settings' => array(
            'prefix_suffix' => false,
            'thousand_separator' => '',
        )
      ))
      ->setDisplayOptions('form', array(
        'type' => 'number',
        'weight' => 3
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['observation_marks'] = BaseFieldDefinition::create('field_collection')
      ->setRequired(FALSE)
      ->setCardinality(-1)
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'field_collection_items',
        'weight' => 4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'field_collection_embed',
        'weight' => 4
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['individuals_ibm'] = BaseFieldDefinition::create('field_collection')
      ->setRequired(FALSE)
      ->setCardinality(-1)
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'field_collection_items',
        'weight' => 4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'field_collection_embed',
        'weight' => 4
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['obs_image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Images'))
      ->setDescription(t('Upload images to help us identify the observed individuals.'))
      ->setRequired(FALSE)
      ->setCardinality(5)
        ->setSettings(array(
            'file_directory' => 'obs_images/[date:custom:Y]-[date:custom:m]',
            'file_extensions' => 'png gif jpg jpeg',
            'alt_field' => false,
            'title_field' => false,
            'handler' => 'default:file',
          
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'image',
        'weight' => 5,
        'settings' => array(
            'image_style' => '',
            'image_link' => '',
        )
        
      ))
      ->setDisplayOptions('form', array(
        'type' => 'image_image',
        'weight' => 5,
        'settings' => array(
            'progress_indicator' => 'throbber',
            'preview_image_style' => 'thumbnail',
        )
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['position'] = BaseFieldDefinition::create('geolocation')
      ->setRequired(FALSE)
      ->setLabel(t('Where did you see the bearded vulture(s)?'))
      ->setDescription(t('Location of bird, if the bird was flying please indicate the location where you first spotted it.'))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'geolocation_map',
        'weight' => 6,
        'settings' => array(
             'type' => 'google.maps.MapTypeId.TERRAIN',
             'zoom' => 5,
             'height' => '400px',
            'width' => '100%',
            'title' => '',
            'info_text'=> 'Lat: %lat Lng: %lng',
            'info_auto_display' => true,
        )
      ))
      ->setDisplayOptions('form', array(
        'type' => 'geolocation_googlegeocoder',
        'weight' => 6
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['remarks'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('General remarks'))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'basic_string',
        'weight' => 7
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textarea',
        'weight' => 7,
        'settings' => array(
            'rows' => 5
        )
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 10
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 10
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['firstname'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Firstname'))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 11
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 11
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['address'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Address'))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 12
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 12
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['plz'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Zip-Code'))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 13
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 13
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['location'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Town'))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 14
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 14
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['country'] = BaseFieldDefinition::create('country')
      ->setLabel(t('Country'))
      ->setDefaultValue('CH')
      ->setSetting('selectable_countries', array(
          "CH" => "CH",
          "AT" => "AT",
          "DE" => "DE", 
          "FR" => "FR",
          "IT" => "IT",
          "LI" => "LI",
          "LI" => "LU",
          "NL" => "NL",
          "ES" => "ES",
          "PT" => "PT",
          "BE" => "BE" 
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'country_default',
        'weight' => 15
      ))
      ->setDisplayOptions('form', array(
        'type' => 'country_default',
        'weight' => 15
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
  
    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'basic_string',
        'weight' => 9
      ))
      ->setDisplayOptions('form', array(
        'type' => 'email_default',
        'weight' => 9
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Phone'))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 17
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 17
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code for the Observation entity.'));
    
    $fields['remarks_admin'] = BaseFieldDefinition::create('string_long')
      ->setLabel('Bemerkungen Kontrolle')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'basic_string',
        'weight' => 22
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textarea',
        'weight' => 22,
        'settings' => array(
            'rows' => 5
        )
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['status'] = BaseFieldDefinition::create('list_integer')
      ->setLabel(t('Status'))
      ->setRequired(TRUE)
      ->setDefaultValue(Observation::$new)
      ->setSettings(array(
        'allowed_values' => array(
            Observation::$new => 'neu',
            Observation::$process => 'in Bearbeitung',
            Observation::$ready => 'Freigegeben f&uuml;r Transfer IBM',
            Observation::$transmitted => 'in IBM eingetragen',
            Observation::$forwarded => 'an Partner weitergeleitet',
        ),
      ))
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'list_default',
        'weight' => 21,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => 21,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['quality'] = BaseFieldDefinition::create('list_integer')
      ->setLabel('Qualität')
      ->setSettings(array(
        'allowed_values' => array(
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
        ),
      ))
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 32,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => 32,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['distance'] = BaseFieldDefinition::create('integer')
      ->setLabel('min. Distanz')
      ->setRequired(FALSE)
      ->setSettings(array(
          'min' => 1,
          'max' => null,
          'prefix' => '',
          'suffix' => '',
          
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'number_integer',
        'weight' => 3,
        'settings' => array(
            'prefix_suffix' => false,
            'thousand_separator' => '',
        )
      ))
      ->setDisplayOptions('form', array(
        'type' => 'number',
        'weight' => 3
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
 
    return $fields;
  }

}
