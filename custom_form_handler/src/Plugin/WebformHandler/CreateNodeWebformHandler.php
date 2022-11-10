<?php

namespace Drupal\custom_form_handler\Plugin\WebformHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Create a new node entity from a webform submission.
 *
 * @WebformHandler(
 *   id = "Create a node",
 *   label = @Translation("Create a node"),
 *   category = @Translation("Entity Creation"),
 *   description = @Translation("Creates a new node from Webform Submissions."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */

class CreateNodeWebformHandler extends WebformHandlerBase {

  /**
   * {@inheritdoc}
   */

  // Function to be fired after submitting the Webform.
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    // Get an array of the values from the submission.
    $values = $webform_submission->getData();

    $node_args = [
      'type' => 'event',
      'langcode' => 'en',
      'created' => time(),
      'changed' => time(),
      'uid' => 1,
      'moderation_state' => 'draft',
      'title' => $values['event_name'],
      'field_start_date' => $values['start_date'],
      'field_end_date' => $values['end_date'],
      'field_location' => $values['location'],
      'field_website' => $values['website'],
      'body' => [
        'value' => $values['event_description'],
        'format' => 'full_html'
      ]
    ];

    $node = Node::create($node_args);
    $node->save();

    $node_fr = $node->addTranslation('fr');
    $node_fr->title = $values['event_name'];
    $node_fr->body->value = $values['event_description'];
    $node_fr->body->format = 'full_html';
    $node_fr->field_start_date = $values['start_date'];
    $node_fr->field_end_date = $values['end_date'];
    $node_fr->field_location = $values['location'];
    $node_fr->field_website = $values['website'];
    $node_fr->save();
  }
}